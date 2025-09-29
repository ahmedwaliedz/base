<?php
namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;

trait HasDynamicRelations {
    /**
     * Get all relation method names defined in the model.
     */
    public function getDefinedRelations(): array {
        return cache()->rememberForever('relations_' . static::class, function () {
            $class     = new ReflectionClass($this);
            $methods   = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $relations = [];

            foreach ($methods as $method) {
                if ($method->class !== get_class($this)) {
                    continue;
                }

                if ($method->getNumberOfRequiredParameters() > 0) {
                    continue;
                }

                try {
                    $return = $method->invoke($this);
                    if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                        $relations[] = $method->getName();
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }

            return $relations;
        });
    }
}
