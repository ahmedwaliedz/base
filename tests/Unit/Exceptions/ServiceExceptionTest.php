<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\ServiceException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ServiceExceptionTest extends TestCase
{
    public function test_default_status_code_is_400(): void
    {
        $exception = new ServiceException('Test message');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
    }

    public function test_for_model_returns_400_by_default(): void
    {
        $exception = ServiceException::forModel('Admin', 'store', 'Cannot store admin');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
        $this->assertEquals('Cannot store admin', $exception->getMessage());
        $this->assertEquals('Admin', $exception->getContext()['model']);
        $this->assertEquals('store', $exception->getContext()['action']);
    }

    public function test_for_model_can_use_custom_status_code(): void
    {
        $exception = ServiceException::forModel('Admin', 'delete', 'Cannot delete', Response::HTTP_CONFLICT);
        $this->assertEquals(Response::HTTP_CONFLICT, $exception->getStatusCode());
    }

    public function test_not_found_returns_404(): void
    {
        $exception = ServiceException::notFound('Admin', 123);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $exception->getStatusCode());
        $this->assertEquals('Admin with ID 123 not found', $exception->getMessage());
        $this->assertEquals('Admin', $exception->getContext()['model']);
        $this->assertEquals(123, $exception->getContext()['id']);
    }

    public function test_validation_returns_422(): void
    {
        $exception = ServiceException::validation('Invalid data', ['email' => 'Invalid']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $exception->getStatusCode());
        $this->assertEquals('Invalid data', $exception->getMessage());
        $this->assertEquals(['email' => 'Invalid'], $exception->getContext()['errors']);
    }

    public function test_unauthorized_returns_401(): void
    {
        $exception = ServiceException::unauthorized();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $exception->getStatusCode());
        $this->assertEquals('Unauthorized', $exception->getMessage());
        $this->assertEquals('unauthorized', $exception->getContext()['type']);
    }

    public function test_unauthorized_accepts_custom_message(): void
    {
        $exception = ServiceException::unauthorized('Token expired');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $exception->getStatusCode());
        $this->assertEquals('Token expired', $exception->getMessage());
    }

    public function test_forbidden_returns_403(): void
    {
        $exception = ServiceException::forbidden();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $exception->getStatusCode());
        $this->assertEquals('Forbidden', $exception->getMessage());
        $this->assertEquals('forbidden', $exception->getContext()['type']);
    }

    public function test_forbidden_accepts_custom_message(): void
    {
        $exception = ServiceException::forbidden('Not allowed to access this resource');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $exception->getStatusCode());
        $this->assertEquals('Not allowed to access this resource', $exception->getMessage());
    }

    public function test_get_context_returns_all_context_data(): void
    {
        $exception = ServiceException::forModel('Admin', 'update', 'Update failed', 422, ['id' => 5]);
        $context = $exception->getContext();

        $this->assertEquals('Admin', $context['model']);
        $this->assertEquals('update', $context['action']);
        $this->assertEquals(5, $context['id']);
    }

    public function test_all_status_codes_are_non_500(): void
    {
        $codes = [
            ServiceException::forModel('Model', 'action', 'msg')->getStatusCode(),
            ServiceException::notFound('Model', 1)->getStatusCode(),
            ServiceException::validation('msg', [])->getStatusCode(),
            ServiceException::unauthorized()->getStatusCode(),
            ServiceException::forbidden()->getStatusCode(),
        ];

        foreach ($codes as $code) {
            $this->assertLessThan(500, $code, "Status code {$code} should be non-5xx");
        }
    }
}