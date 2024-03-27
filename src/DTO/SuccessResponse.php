<?php

namespace Jonasgn\LaravelCommons\DTO;

use Exception;
use Illuminate\Http\Response;

/**
 * Resposta padronizada dos recursos da aplicação
 */
class SuccessResponse
{
    private int $statusCode;
    private ?Exception $exception;

    private function __construct(int $statusCode, ?Exception $exception = null)
    {
        $this->statusCode = $statusCode;
        $this->exception = $exception;
    }

    /**
     * Cria uma instância da classe a partir do construtor padrão.
     * Facilita a leitura do código
     */
    public static function new(int $statusCode, ?Exception $exception = null): SuccessResponse
    {
        return new SuccessResponse($statusCode, $exception);
    }

    /**
     * Cria uma nova instância de response já configurando a resposta para NO_CONTENT
     * @deprecated Utilize o método `noContent`
     */
    public static function withNoContent(): Response
    {
        return static::noContent();
    }

    public static function noContent(): Response
    {
        $dto = new SuccessResponse(Response::HTTP_NO_CONTENT);
        return $dto->toResponse(null);
    }

    /**
     * Cria um objeto de resposta HTTP padronizado, a partir dessa instância, para ser utilizado nas
     * respostas bem sucedidas da aplicação
     */
    public function toResponse(mixed $data): Response
    {
        $publicResponse = $this->_getDefaultResponseObject([
            'data' => $data,
            'error' => null,
        ]);
        $debugResponse = $this->_getDeveloperErrorDetails();

        return response(array_merge($publicResponse, $debugResponse), $this->statusCode);
    }

    /**
     * Método auxiliar para lidar com união de dados via array
     */
    public function toResponseWithMerge(...$data): Response
    {
        return $this->toResponse(array_merge(...$data));
    }

    /**
     * Cria um objeto de resposta HTTP padronizado, a partir dessa instância, para ser utilizado nos
     * erros da aplicação
     */
    public function toErrorResponse(string $cause, string $message): Response
    {
        $publicResponse = $this->_getDefaultResponseObject([
            'data' => null,
            'error' => [
                'cause' => $cause,
                'message' => $message,
            ]
        ]);
        $debugResponse = $this->_getDeveloperErrorDetails();

        return response(array_merge($publicResponse, $debugResponse), $this->statusCode);
    }

    /**
     * Retorna um objeto padrão, tanto para sucesso quanto para erros da aplicação
     */
    private function _getDefaultResponseObject(array $values): array
    {
        return array_merge([
            'status_code' => $this->statusCode,
            'moment' => now(),
        ], $values);
    }

    /**
     * Mostra os detalhes do erro apenas em modo de desenvolvimento.
     * Isso evita com que informações sensíveis sejam vazadas para o client em produção
     */
    private function _getDeveloperErrorDetails(): array
    {
        if (!app()->isLocal() || $this->exception == null) return [];

        return [
            'internal_exception_name' => $this->exception::class,
            'internal_code' => $this->exception->getCode(),
            'internal_message' => $this->exception->getMessage(),
            'internal_trace' => $this->exception->getTrace(),
        ];
    }
}
