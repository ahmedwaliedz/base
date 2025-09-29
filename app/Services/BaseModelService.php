<?php
namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModelService {

    public function storeRelations(Model $model, array $relations, array $validatedData): void {
        dd($relations);
        DB::beginTransaction();
        try {
            foreach ($relations as $relation) {
                if (! isset($validatedData[$relation])) {
                    continue;
                }

                $relationType = $this->getRelationType($model, $relation);

                match ($relationType) {
                    'HasOne', 'MorphOne'           => $model->{$relation}()->create($validatedData[$relation]),
                    'HasMany', 'MorphMany'         => $model->{$relation}()->createMany($validatedData[$relation]),
                    'BelongsToMany', 'MorphToMany' => $model->{$relation}()->sync($validatedData[$relation]),
                    default => null,
                };
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    public function updateRelations(Model $model, array $relations, array $validatedData): void {
        DB::transaction(function () use ($model, $relations, $validatedData) {
            foreach ($relations as $relation) {
                if (! isset($validatedData[$relation])) {
                    continue;
                }

                $relationType = $this->getRelationType($model, $relation);

                match ($relationType) {
                    'HasOne', 'MorphOne'           => $model->{$relation}()->updateOrCreate(
                        ['id' => $model->{$relation}?->id],
                        $validatedData[$relation]
                    ),

                    'HasMany', 'MorphMany'         => $this->updateHasMany($model, $relation, $validatedData[$relation]),

                    'BelongsToMany', 'MorphToMany' => $model->{$relation}()->sync($validatedData[$relation]),

                    default => null,
                };
            }
        });
    }

    /**
     * Handle updating hasMany or morphMany relations.
     */
    protected function updateHasMany(Model $model, string $relation, array $data): void {
        $relationModel = $model->{$relation}();

        foreach ($data as $item) {
            if (isset($item['id'])) {
                $relationModel->where('id', $item['id'])->update($item);
            } else {
                $relationModel->create($item);
            }
        }
    }

    /**
     * Get relation type dynamically.
     */
    protected function getRelationType(Model $model, string $relation): ?string {
        if (! method_exists($model, $relation)) {
            return null;
        }

        $relationObject = $model->{$relation}();
        return class_basename(get_class($relationObject));
    }
}
