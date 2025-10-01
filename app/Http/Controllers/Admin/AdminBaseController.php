<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\Response\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class AdminBaseController extends Controller {
    use ResponseTrait;

    protected $model;
    protected $service;
    protected $smallPluralName;
    protected $smallSingularName;
    protected $createRequest;
    protected $updateRequest;
    protected $modelBaseName;
    public $inputs = [];

    public function __construct($service) {
        $this->service           = $service;
        $this->model             = $service->getModel();
        $this->smallPluralName   = $this->model::smallPluralName();
        $this->smallSingularName = $this->model::smallSingularName();
        $this->modelBaseName     = class_basename($this->model);

        $this->createRequest = "App\\Http\\Requests\\Admin\\" . $this->modelBaseName . "\\StoreRequest";
        $this->updateRequest = "App\\Http\\Requests\\Admin\\" . $this->modelBaseName . "\\UpdateRequest";
    }

    public function index(Request $request) {
        // Export full filtered dataset (no pagination)
        if ($request->has('export')) {
            return $this->service->export($request);
        }

        if (request()->ajax()) {
            ${$this->smallPluralName} = $this->service->index($request)->paginate($request->filters['per_page'] ?? 30);
            return view('admin.' . $this->smallPluralName . '.table', [$this->smallPluralName => ${$this->smallPluralName}])->render();
        }
        return view('admin.' . $this->smallPluralName . '.index', $this->service->indexVars() + [ ...get_defined_vars()]);
    }

    public function create() {
        $vars           = $this->service->create();
        $vars['inputs'] = $this->inputs;
        return view('admin.' . $this->smallPluralName . '.create', $vars);
    }

    public function store() {
        $request = app($this->createRequest);
        try {
            $this->service->store($request);
            return $this->respondWithSuccess(__('admin/main.created_successfully'), [
                'route' => route('admin.' . $this->smallPluralName . '.index'),
            ]);
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }

    public function edit($id) {
        $vars = $this->service->edit($id);
        return view('admin.' . $this->smallPluralName . '.edit', $vars);
    }

    public function update($id) {
        $request = app($this->updateRequest);
        try {
            $this->service->update($request, $id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'route' => route('admin.' . $this->smallPluralName . '.index'),
            ]);
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }

    public function show($id) {
        $vars = $this->service->show($id);
        return view('admin.' . $this->smallPluralName . '.show', $vars);
    }

    public function destroy($id) {
        try { 
            $this->service->destroy($id);
            return $this->respondWithSuccess(__('admin/main.deleted_successfully'));
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }
    
    public function destroyAll(Request $request) {
        try { 
            $this->service->destroyAll($request->ids);
            return $this->respondWithSuccess(__('admin/main.delete_selected_successfully'));
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }

}
