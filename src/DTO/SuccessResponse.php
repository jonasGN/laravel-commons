<?php

namespace Jonasgn\LaravelCommons\DTO;

use Illuminate\Http\Response;
use JsonSerializable;

/**
 * Resposta padronizada dos recursos da aplicação
 */
class SuccessResponse implements JsonSerializable
{
    private mixed $data;
    private int $statusCode;
    private array $meta;

    private function __construct(mixed $data, int $statusCode)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * Cria uma instância da classe a partir do construtor padrão.
     * Facilita a leitura do código
     */
    public static function new(mixed $data, int $statusCode = Response::HTTP_OK): SuccessResponse
    {
        return new SuccessResponse($data, $statusCode);
    }

    /**
     * Retorna um objeto de resposta padronizado
     */
    public function response(): Response
    {
        return response($this, $this->statusCode);
    }

    public function created(): Response
    {
        return $this->response($this, Response::HTTP_CREATED);
    }

    /**
     * Adiciona notas para os campos.
     * Ideal para avisos sobre o determinado campo, bem como para sinalizar obsolescência.
     */
    public function addFieldNotes(array $meta): void
    {
        $this->meta = ['field_notes' => $meta];
    }

    public static function noContent(): Response
    {
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Cria um objeto de resposta HTTP padronizado, a partir dessa instância, para ser utilizado nas
     * respostas bem sucedidas da aplicação
     */
    public function jsonSerialize(): mixed
    {
        return array_merge(
            [
                'status_code' => $this->statusCode,
                'moment' => now(),
                'data' => $this->data,
            ],
            isset($this->meta) ? ['meta' => $this->meta] : []
        );
    }
}
