<?php
namespace App\Services\Admin\Base;

use App\Exceptions\ServiceException;

class AuthenticatableBaseService extends CrudBaseService {

    public function __construct($model) {
        parent::__construct($model);
    }

    public function switchBlock($id) {
        $object = $this->model::findOrFail($id);
        if (! array_key_exists('is_blocked', $object->getAttributes()) && ! $object->offsetExists('is_blocked')) {
            throw ServiceException::forModel($this->model, 'switchBlock', 'This model does not support block status.');
        }
        $object->update(['is_blocked' => ! $object->is_blocked]);
        return $object->is_blocked;
    }
}