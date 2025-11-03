<?php
namespace App\Traits\Models;

use App\Traits\Models\CanRetrieveRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeException;

trait CanRetrieve {
    protected static array $validatedModels  = [];
    protected static array $retrievableState = [];

    public static function bootCanRetrieve(): void {
        // Validate on boot so the retrievable state is set even before any retrieval happens
        if (! CanRetrieveRegistry::isValidated(static::class)) {
            static::validateModelAndRelations();
            CanRetrieveRegistry::markValidated(static::class);
        }

        static::retrieved(function (Model $model) {
            $class = get_class($model);

            if (! CanRetrieveRegistry::isValidated($class)) {
                static::validateModelAndRelations();
                CanRetrieveRegistry::markValidated($class);
            }
        });
    }

    protected static function validateModelAndRelations(): void {
        $isRetrievable = true;

        if (! in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            throw new RuntimeException(sprintf(
                'Model "%s" must use SoftDeletes to use CanRetrieve.',
                static::class
            ));
        }

        $relations = defined(static::class . '::RELATIONS') ? constant(static::class . '::RELATIONS') : [];

        $instance = new static();

        foreach ($relations as $relation) {
            if (! method_exists($instance, $relation)) {
                throw new RuntimeException(sprintf(
                    'Relation "%s" not found in model "%s".',
                    $relation,
                    static::class
                ));
            }

            $relationObj = $instance->{$relation}();

            if (! ($relationObj instanceof Relation)) {
                continue;
            }

            if ($relationObj instanceof HasOne || $relationObj instanceof HasMany) {
                $related = $relationObj->getRelated();

                if (! in_array(SoftDeletes::class, class_uses_recursive($related))) {
                    throw new RuntimeException(sprintf(
                        'Relation "%s" in model "%s" points to "%s" which does NOT use SoftDeletes.',
                        $relation,
                        static::class,
                        get_class($related)
                    ));
                }
            }
        }

        static::$retrievableState[static::class] = $isRetrievable;
    }

    public static function is_retreivable(): bool {
        if (! array_key_exists(static::class, static::$retrievableState)) {
            // Ensure validation runs even if the model hasn't been retrieved/booted in this request
            static::validateModelAndRelations();
        }
        return static::$retrievableState[static::class] ?? false;
    }

    public function __get($key) {
        if ($key === 'is_retreivable') {
            return static::is_retreivable();
        }

        return parent::__get($key);
    }

    public function retrieve(): static
    {
        if (! method_exists($this, 'restore')) {
            throw new RuntimeException(sprintf(
                'Model "%s" cannot retrieve because SoftDeletes is missing.',
                static::class
            ));
        }

        $this->restore();
        $this->restoreRelationsRecursively();

        return $this;
    }

    protected function restoreRelationsRecursively(): void {
        $relations = defined(static::class . '::RELATIONS') ? constant(static::class . '::RELATIONS') : [];

        foreach ($relations as $relation) {
            if (! method_exists($this, $relation)) {
                continue;
            }

            $relationObj = $this->{$relation}();

            if (! ($relationObj instanceof Relation)) {
                continue;
            }

            $related = $relationObj->getRelated();

            if (! in_array(SoftDeletes::class, class_uses_recursive($related))) {
                continue;
            }

            $relatedItems = $relationObj->onlyTrashed()->get();

            foreach ($relatedItems as $item) {
                $item->restore();

                if (in_array(self::class, class_uses_recursive($item))) {
                    $item->restoreRelationsRecursively();
                }
            }
        }
    }
}
