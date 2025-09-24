<?php
namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;

trait HasDynamicRelations {
    /**
     * Get all relation method names defined in the model.
     */
    public function getDefinedRelations() : array {
        $class   = new ReflectionClass($this);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        $relations = [];
        foreach ($methods as $method) {
            // Skip inherited methods (from Model / base classes)
            if ($method->class !== get_class($this)) {
                continue;
            }

            // Skip methods requiring parameters
            if ($method->getNumberOfRequiredParameters() > 0) {
                continue;
            }

            try {
                $return = $method->invoke($this);
                if ($return instanceof Relation) {
                    $relations[] = $method->getName();
                }
            } catch (\Throwable $e) {
                // In case method is not safe to invoke, just skip it
                continue;
            }
        }

        return $relations;
    }
}
