<?php
namespace  App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ValidationResponseTrait
{
    use BaseResponseTrait;

    /**
     * Throw validation exception (used in FormRequest failedValidation)
     *
     * @param array $errors
     * @return never
     */
    public function respondValidationErrors(array $errors = []): mixed
    {
        throw \Illuminate\Validation\ValidationException::withMessages($errors);
    }

    /**
     * Render validation errors as JSON response (used in exception handler)
     *
     * @param array $errors
     * @param string|null $message
     * @return JsonResponse
     */
    public function renderValidationErrors(array $errors = [], ?string $message = null): JsonResponse
    {
        return $this->mainRespond([
            'status' => 'error',
            'message' => $message ?? __('validation.invalid_data'),
            'errors' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
