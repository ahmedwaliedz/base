<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ServiceException extends Exception
{
    protected int $statusCode;
    protected array $context = [];

    public function __construct(
        string $message = '',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $context = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->context = $context;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public static function forModel(
        string $model,
        string $action,
        string $message,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $extra = []
    ): static {
        return new static($message, $statusCode, array_merge([
            'model' => $model,
            'action' => $action,
        ], $extra));
    }

    public static function notFound(string $model, int|string $id): static
    {
        return new static(
            "{$model} with ID {$id} not found",
            Response::HTTP_NOT_FOUND,
            ['model' => $model, 'id' => $id]
        );
    }

    public static function validation(string $message, array $errors = []): static
    {
        return new static(
            $message,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            ['errors' => $errors]
        );
    }

    public static function unauthorized(string $message = 'Unauthorized'): static
    {
        return new static(
            $message,
            Response::HTTP_UNAUTHORIZED,
            ['type' => 'unauthorized']
        );
    }

    public static function forbidden(string $message = 'Forbidden'): static
    {
        return new static(
            $message,
            Response::HTTP_FORBIDDEN,
            ['type' => 'forbidden']
        );
    }
}