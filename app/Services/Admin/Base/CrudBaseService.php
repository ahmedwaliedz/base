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
        return array_merge($this->showVars(), [
            $this->lowerClassName => $this->model::with($this->model::RELATIONS)->findOrFail($id),
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

        // Default to CSV (Excel-compatible). Add UTF-8 BOM for Arabic/English
        $filename = strtolower(class_basename($this->model)) . '-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $output = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Determine columns from first row
            $first = $rows->first();
            if ($first) {
                $array = $first->toArray();
                fputcsv($output, array_keys($array));
                foreach ($rows as $row) {
                    $data = $row->toArray();
                    // Flatten nested arrays/objects to JSON strings
                    foreach ($data as $key => $value) {
                        if (is_array($value) || is_object($value)) {
                            $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
                        }
                    }
                    fputcsv($output, $data);
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
}
