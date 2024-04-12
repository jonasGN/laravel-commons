<?php

namespace Jonasgn\LaravelCommons\DTO;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonSerializable;

/**
 * Resposta de erro padronizada de acordo com o padrão Problem Details conforme a RFC 7807
 * https://datatracker.ietf.org/doc/html/rfc9457
 */
class ProblemDetails implements JsonSerializable
{
    private int $statusCode;
    private string $title;
    private string $details;
    private Exception $error;
    private string $instance;
    private array $meta;

    public function __construct(
        string $title,
        string $details,
        Exception $error,
        string $instance,
        int $statusCode,
        array $meta = []
    ) {
        $this->title = $title;
        $this->details = $details;
        $this->error = $error;
        $this->instance = $instance;
        $this->statusCode = $statusCode;
        $this->meta = $meta;
    }

    public static function new(
        string $title,
        string $details,
        Exception $exception,
        Request $request,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $meta = []
    ): ProblemDetails {
        return new ProblemDetails(
            title: $title,
            details: $details,
            error: $exception,
            instance: $request->path(),
            statusCode: $statusCode,
            meta: $meta,
        );
    }

    public function response(): Response
    {
        return response($this, $this->statusCode)
            ->header('Content-Type', 'application/problem+json')
            ->header('Content-Language', 'pt-BR');
    }

    public function jsonSerialize(): mixed
    {
        return array_merge([
            "type" => $this->_getTypeRef(),
            "title" => $this->title,
            "detail" => $this->details,
            "instance" => $this->instance,
        ], $this->meta, app()->isLocal() ? [
            'internal_exception' => $this->error::class,
            'internal_message' => $this->error->getMessage(),
            'internal_stack_trace' => $this->error->getTrace(),
        ] : []);
    }

    private function _getTypeRef(): string
    {
        return match ($this->statusCode) {
            400 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-400-bad-request',
            401 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-401-unauthorized',
            402 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-402-payment-required',
            403 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-403-forbidden',
            404 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-404-not-found',
            405 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-405-method-not-allowed',
            406 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-406-not-acceptable',
            407 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-407-proxy-authentication-re',
            408 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-408-request-timeout',
            409 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-409-conflict',
            410 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-410-gone',
            411 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-411-length-required',
            412 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-412-precondition-failed',
            413 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-413-content-too-large',
            414 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-414-uri-too-long',
            415 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-415-unsupported-media-type',
            416 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-416-range-not-satisfiable',
            417 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-417-expectation-failed',
            418 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-418-unused',
            421 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-421-misdirected-request',
            422 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-422-unprocessable-content',
            426 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-426-upgrade-required',
            500 => 'https://datatracker.ietf.org/doc/html/rfc9110#section-15.6.1',
            501 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-501-not-implemented',
            502 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-502-bad-gateway',
            503 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-503-service-unavailable',
            504 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-504-gateway-timeout',
            505 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-505-http-version-not-suppor',
            default => 'https://datatracker.ietf.org/doc/html/rfc9110#name-status-codes',
        };
    }
}
