<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;

class AuthenticatableBaseController extends AdminBaseController {

    public function __construct($service) {
        parent::__construct($service);
    }

    public function switchBlock($id) {
        try {
            $result = $this->service->switchBlock($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_blocked' => $result,
            ]);
        } catch (ServiceException $e) {
            Log::warning('ServiceException in switchBlock', [
                'controller' => static::class,
                'model' => $this->modelBaseName,
                'id' => $id,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'context' => $e->getContext(),
            ]);
            return $this->respondWithFail($e->getMessage(), [], $e->getStatusCode());
        } catch (\Throwable $e) {
            Log::error('Unexpected error in switchBlock', [
                'controller' => static::class,
                'model' => $this->modelBaseName,
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->respondInternalError();
        }
    }
   
}