<?php
namespace  App\Traits\Response;
trait ValidationResponseTrait
{
    /**
     * @param array $errors
     * @return never
     */
    public function respondValidationErrors(array $errors = []): mixed
    {
        // This method is used to respond with a JSON response for validation errors.
        throw \Illuminate\Validation\ValidationException::withMessages($errors);
    }

}
