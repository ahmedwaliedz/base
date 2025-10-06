<?php
namespace App\Services\Admin\Base;

use App\Services\BaseModelService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            return $query->search($request->filters);
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
            // ReportTrait::addToLog(__('log.added', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
        });
        return $object;
    }

    public function edit($id) {
        return array_merge($this->editVars(), [
            $this->lowerClassName => $this->model::findOrFail($id),
            'id'                  => $id,
        ]);
    }

    public function show($id) {
        $query = $this->model::with($this->model::RELATIONS);
        // If model supports retrieval (SoftDeletes + CanRetrieve), allow viewing trashed records
        if ($this->getIsRetreivable()) {
            $query = $query->withTrashed();
        }
        return array_merge($this->showVars(), [
            $this->lowerClassName => $query->findOrFail($id),
            'id'                  => $id,
            'lowerClassName'      => $this->lowerClassName,
        ]);
    }

    public function update(Request $request, $id) {
        $object = $this->model::findOrFail($id);
        DB::transaction(function () use ($request, &$object) {
            $object->update($request->validated());
            $this->modelService->updateRelations($object, $request->validated());
            // ReportTrait::addToLog(__('log.updated', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
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
            // ReportTrait::addToLog(__('log.deleted', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
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

    public function switchActive($id) {
        $object = $this->model::findOrFail($id);
        $object->update(['is_active' => ! $object->is_active]);
        // ReportTrait::addToLog(__('log.switched_active', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
        return response()->json(['msg' => 'success', 'is_active' => $object->is_active]);
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

    // public function indexWithRelations(array $where = []) {
    //     $this->data = $this->index($where)->with($this->model::RELATIONS);
    //     return $this;
    // }

    public function with(array $relations) {
        $this->data = $this->data->with($relations);
        return $this;
    }

    public function paginate($paginationNumber = 0) {
        return $this->data->paginate($paginationNumber);
    }

    public function get() {
        return $this->data->get();
    }

    public function find($id) {
        return $this->model::findOrFail($id);
    }

    public function findWithRelations($id, array $relations = []) {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function getModel() {
        return $this->model;
    }

    public function getIsRetreivable() {
        $is_retreivable = false;

        try {
            $is_retreivable = $this->model::is_retreivable();
        } catch (Exception $e) {
            $is_retreivable = false;
        }

        return $is_retreivable;
    }

    public function export(Request $request) {
        $format = strtolower($request->get('export', 'csv'));
        $query = $this->index($request);
        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : [$request->ids];
            $query->whereIn('id', $ids);
        }
        // Get all filtered data (no pagination)
        $rows = $query->get();

        if ($format === 'json') {
            return response()->json($rows);
        }

        // If HTML requested (for print/pdf), render a unified export table view
        if (in_array($format, ['html', 'print', 'pdf'])) {
            $columns = method_exists($this, 'getExportColumns') ? $this->getExportColumns() : null;
            // Fallback: infer from first row if no schema
            if (! $columns) {
                $first   = $rows->first();
                $headers = [];
                if ($first) {
                    foreach (array_keys($first->toArray()) as $key) {
                        $headers[] = ['key' => $key, 'label' => ucfirst(str_replace('_', ' ', $key)), 'value' => function ($row) use ($key) { return data_get($row, $key); }];
                    }
                }
                $columns = $headers;
            }

            return response()->view('admin.layouts.export.table', [
                'title'   => __("admin/main.export") . ' - ' . class_basename($this->model),
                'columns' => $columns,
                'rows'    => $rows,
            ]);
        }

        // For Excel export from UI (format=csv), return an HTML table with Excel MIME type
        if ($format === 'csv') {
            $columns = method_exists($this, 'getExportColumns') ? $this->getExportColumns() : null;
            if (! $columns) {
                $first   = $rows->first();
                $headers = [];
                if ($first) {
                    foreach (array_keys($first->toArray()) as $key) {
                        $headers[] = ['key' => $key, 'label' => ucfirst(str_replace('_', ' ', $key)), 'value' => function ($row) use ($key) { return data_get($row, $key); }];
                    }
                }
                $columns = $headers;
            }

            $filename = strtolower(class_basename($this->model)) . '-' . now()->format('Ymd-His') . '.xls';
            return response()->view('admin.layouts.export.table', [
                'title'   => __("admin/main.export") . ' - ' . class_basename($this->model),
                'columns' => $columns,
                'rows'    => $rows,
            ])->withHeaders([
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        // Fallback: stream UTF-8 CSV with BOM (used only if other formats requested explicitly)
        $filename = strtolower(class_basename($this->model)) . '-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = method_exists($this, 'getExportColumns') ? $this->getExportColumns() : null;

        $callback = function () use ($rows, $columns) {
            $output = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($columns && count($columns)) {
                // Translate labels if they look like translation keys
                $labels = array_map(function ($c) {
                    $label = $c['label'] ?? $c['key'] ?? '';
                    return str_contains((string)$label, '/') ? __($label) : $label;
                }, $columns);
                fputcsv($output, $labels);
                foreach ($rows as $row) {
                    $line = [];
                    foreach ($columns as $col) {
                        $val = isset($col['value']) && is_callable($col['value']) ? call_user_func($col['value'], $row) : data_get($row, $col['key'] ?? '');
                        if (is_array($val) || is_object($val)) { $val = json_encode($val, JSON_UNESCAPED_UNICODE); }
                        $line[] = $val;
                    }
                    fputcsv($output, $line);
                }
            } else {
                $first = $rows->first();
                if ($first) {
                    $array = $first->toArray();
                    fputcsv($output, array_keys($array));
                    foreach ($rows as $row) {
                        $data = $row->toArray();
                        foreach ($data as $key => $value) {
                            if (is_array($value) || is_object($value)) { $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE); }
                        }
                        fputcsv($output, $data);
                    }
                }
            }

            fclose($output);
        };

        return new StreamedResponse($callback, 200, $headers);
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
                        $normalized[] = [ 'key' => $col, 'label' => ucfirst(str_replace('_', ' ', $col)) ];
                    } elseif (is_array($col)) {
                        $key = $col['key'] ?? null;
                        if ($key) {
                            $label = $col['label'] ?? ucfirst(str_replace('_', ' ', $key));
                            // Translate if provided as a translation key
                            $label = is_string($label) && str_contains($label, '/') ? __($label) : $label;
                            $normalized[] = [ 'key' => $key, 'label' => $label ];
                        }
                    }
                }
                if (count($normalized)) return $normalized;
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
