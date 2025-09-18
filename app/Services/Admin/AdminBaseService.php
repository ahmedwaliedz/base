<?php
namespace App\Services\Admin;

use App\Services\BaseModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBaseService {

    protected $data;
    protected $model;
    protected $lowerClassName;
    protected BaseModelService $modelService;

    public function __construct($model) {
        $this->model          = $model;
        $this->modelService   = new BaseModelService();
        $this->lowerClassName = strtolower(class_basename($model));
    }

    public function index($request, array $where = []) {
        $query = $this->model::query()->where($where);
        // ->with($this->model::RELATIONS);

        if (method_exists($query, 'search') && $request->filters) {
            $query = $query->search($request->filters);
        }

        return $query;
    }

    public function create() {
        return $this->createVars();
    }

    public function store(Request $request) {
        $object = null;
        DB::transaction(function () use ($request, &$object) {
            // $object = parent::store($request);
            // ReportTrait::addToLog(__('log.added', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
        });
        return $object;
    }

    public function update(Request $request, $id) {
        // $object = parent::update($request, $id);
        // ReportTrait::addToLog(__('log.updated', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
        return $object;
    }

    public function destroy($id, $function = null) {
        // $object = parent::destroy($id, $function);
        // ReportTrait::addToLog(__('log.deleted', ['id' => $object->id, 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
        return $object;
    }

    public function destroyAll($ids, $function = null) {
        DB::beginTransaction();
        try {
            // $result = parent::destroyAll($ids, $function);
            if ($result) {
                // ReportTrait::addToLog(__('log.bulk_deleted', ['ids' => implode(',', $ids), 'model' => $this->lowerClassName, 'by' => auth('admin')->user()->name]));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info('error at the admin base service destroy all function : ' . $e->getMessage());
            throw $e;
        }
        return $result;
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

    public function showVars($id): array {
        return [];
    }
}
