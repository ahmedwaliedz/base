<?php
namespace App\Services\Admin\Base;

use App\Services\Admin\Export\ExportService;
use App\Services\BaseModelService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrudBaseService {

    protected $data;
    protected $model;
    protected $lowerClassName;
    protected BaseModelService $modelService;

    public function __construct($model) {
        $this->model          = $model;
        $this->modelService   = new BaseModelService();
        $this->lowerClassName = strtolower(class_basename($model));
    }

    public function index($request, $where = []) {
        $query = $this->model::query()->when($request->filters, function ($query) use ($request) {
            if (method_exists($this->model, 'scopeSearch')) {
                return $query->search($request->filters);
            }
        })->where($where);

        return $query;
    }

    public function create() {
        return $this->createVars();
    }

    public function store(Request $request) {
        $object = null;
        DB::transaction(function () use ($request, &$object) {
            $object = $this->model::create($request->validated());
            $this->modelService->storeRelations($object, $request->validated());
        });
        return $object;
    }

    public function edit($id) {

        $query = $this->model::with($this->model::RELATIONS);

        if ($this->getIsRetrievable()) {
            $query = $query->withTrashed();
        }

        return array_merge($this->editVars(), [
            $this->lowerClassName => $query->findOrFail($id),
            'id'                  => $id,
        ]);
    }

    public function show($id) {
        $query = $this->model::with($this->model::RELATIONS);

        if ($this->getIsRetrievable()) {
            $query = $query->withTrashed();
        }

        $item = $query->findOrFail($id);

        return array_merge($this->showVars(), [
            $this->lowerClassName => $item,
            'id'                  => $id,
            'lowerClassName'      => $this->lowerClassName,
            'model'               => $item,
        ]);
    }

    public function update(Request $request, $id) {
        $query = $this->model::with($this->model::RELATIONS);

        if ($this->getIsRetrievable()) {
            $query = $query->withTrashed();
        }

        $object = $query->findOrFail($id);

        DB::transaction(function () use ($request, &$object) {
            $object->update($request->validated());
            $this->modelService->updateRelations($object, $request->validated());
        });
        return $object;
    }

    public function destroy($id, $function = null) {
        $object     = $this->model::findOrFail($id);
        $objectCopy = clone $object;
        DB::transaction(function () use (&$object, &$function) {
            if ($function) {
                call_user_func($function, $object);
            }
            $object->delete();
        });
        return $objectCopy;
    }

    public function destroyAll($ids, $function = null) {
        $objects     = $this->model::whereIn('id', $ids)->get();
        $objectsCopy = clone $objects;
        DB::transaction(function () use (&$objects, &$function) {
            if ($function) {
                call_user_func($function, $objects);
            }
            $objects->each->delete();
        });
        return $objectsCopy;
    }

    public function restore($id) {
        $object = $this->model::withTrashed()->findOrFail($id);
        if (! method_exists($object, 'restore')) {
            throw new Exception('This model does not support restore.');
        }
        DB::transaction(function () use (&$object) {
            // If model uses CanRetrieve trait, prefer retrieve to restore relations as well
            if (method_exists($object, 'retrieve')) {
                $object->retrieve();
            } else {
                $object->restore();
            }
        });
        return $object;
    }

    public function switchActive(int|string $id): bool {
        $object = $this->model::findOrFail($id);
        $object->update(['is_active' => ! $object->is_active]);
        return (bool) $object->fresh()->is_active;
    }

    public function storeRelations(Request $request) {
        $this->data = $this->model::RELATIONS;
        return $this;
    }

    public function handleDeleteFiles($object, $validated) {
        foreach ($validated as $key => $value) {
            if (str_contains($key, 'deleted_')) {
                $key = str_replace('deleted_', '', $key);
                $object->{$key}->whereIn('id', $value)->each->delete();
            }
        }
    }

    public function paginate($paginationNumber = 0) {
        return $this->data->paginate($paginationNumber);
    }

    public function findWithRelations($id, array $relations = []) {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function getModel() {
        return $this->model;
    }

    public function getIsRetrievable() {
        $is_retrievable = false;
        try {
            $is_retrievable = $this->model::is_retrievable();
        } catch (Exception $e) {
            $is_retrievable = false;
        }

        return $is_retrievable;
    }

    public function export(Request $request) {
        $query = $this->index($request);
        // Prefer normalized/translated columns via hook
        $columns       = $this->getExportColumns() ?? (defined($this->model . '::EXPORT_COLUMNS') ? $this->model::EXPORT_COLUMNS : []);
        $exportService = new ExportService();
        return $exportService->handle($request, $query, [
            'columns' => $columns,
            'model'   => $this->model,
        ]);
    }

    // * common variables for views

    public function indexVars(): array {
        return [];
    }

    public function createVars(): array {
        return [];
    }

    public function editVars(): array {
        return [];
    }

    public function showVars(): array {
        return [];
    }

    /**
     * Default export columns schema hook. Prefer model-provided schema.
     * Expected shape: [ ['key'=>string, 'label'=>string, 'value'?:callable($row):mixed], ... ]
     */
    protected function getExportColumns(): ?array {
        $modelClass = $this->model;
        // Prefer constant EXPORT_COLUMNS on model
        if (is_string($modelClass) && defined($modelClass . '::EXPORT_COLUMNS')) {
            $raw = constant($modelClass . '::EXPORT_COLUMNS');
            if (is_array($raw) && count($raw)) {
                $normalized = [];
                foreach ($raw as $col) {
                    if (is_string($col)) {
                        $normalized[] = ['key' => $col, 'label' => ucfirst(str_replace('_', ' ', $col))];
                    } elseif (is_array($col)) {
                        $key = $col['key'] ?? null;
                        if ($key) {
                            $label = $col['label'] ?? ucfirst(str_replace('_', ' ', $key));
                            // Translate if provided as a translation key
                            $label        = is_string($label) && str_contains($label, '/') ? __($label) : $label;
                            $normalized[] = ['key' => $key, 'label' => $label];
                        }
                    }
                }
                if (count($normalized)) {
                    return $normalized;
                }

            }
        }
        // Fallback: static method on model exportColumns()
        if (is_string($modelClass) && is_callable([$modelClass, 'exportColumns'])) {
            $cols = call_user_func([$modelClass, 'exportColumns']);
            if (is_array($cols) && count($cols)) {
                return $cols;
            }
        }
        return null;
    }
}
