<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Role;
use App\Traits\Response\ResponseTrait;
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

        $this->createRequest = "App\\Http\\Requests\\Admin\\" . $this->modelBaseName . "\\Store";
        $this->updateRequest = "App\\Http\\Requests\\Admin\\" . $this->modelBaseName . "\\Update";
    }

    public function index(Request $request) {

        ${$this->smallPluralName} = $this->service->index($request)->paginate($request->filters['per_page'] ?? 30);

        if (request()->ajax()) {

            return view('admin.' . $this->smallPluralName . '.table', [$this->smallPluralName => ${$this->smallPluralName}])->render();

        }

        return view('admin.' . $this->smallPluralName . '.index', $this->service->indexVars() + [ ...get_defined_vars()]);
    }

    public function create() {
        $vars = $this->service->create();
        $vars['inputs'] = $this->inputs;
        return view('admin.' . $this->smallPluralName . '.create', $vars);
    }

    public function create() {
        $roles = Role::get()->map(function ($role) {
            return [
                'id'   => $role->id,
                'name' => $role->name,
            ];
        })->toArray();
        $countries = Country::where('is_active', true)->get()->map(function ($country) {
            return [
                'id'   => $country->code,
                'name' => $country->code,
            ];
        })->toArray();
        return view('admin.admins.create', get_defined_vars());
    }

    public function store(StoreRequest $request) {
        Admin::create($request->validated());
        return $this->respondWithSuccess(__('admin/main.admin_created'), [
            'route' => route('admin.admins.index'),
        ]);
    }

    public function show($id) {
        return view('admin.admins.show', compact('id'));
    }

    public function edit($id) {
        return view('admin.admins.edit', compact('id'));
    }

    public function update(Request $request, $id) {
        return $this->respondWithSuccess(__('admin/main.admin_updated'), [
            'route' => route('admin.admins.index'),
        ]);
    }

    public function destroyAll(Request $request) {
        if (is_array($request->ids)) {
            $admins = Admin::has('role')->whereIn('id', $request->ids)->get();
            $admins->each->delete();
            return $this->respondWithSuccess(__('admin/main.admins_deleted'));
        }
        return $this->respondWithFail(__('admin/main.admin_not_found'), [
            'route' => route('admin.admins.index'),
        ]);
    }

}
