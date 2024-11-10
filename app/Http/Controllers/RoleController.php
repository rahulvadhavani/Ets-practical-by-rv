<?php

namespace App\Http\Controllers;

use App\CommonTrail;
use App\Http\Requests\CustomerCreateUpdateRequest;
use App\Http\Requests\RoleCreateUpdateRequest;
use App\Http\Requests\UserCreateUpdateRequest;
use App\Http\Requests\UserDeleteRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\City;
use App\Models\Permission;
use App\Models\Role;
use App\Models\State;
use App\Models\Upload;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    use CommonTrail;
    public $module = "Role";
    public $permissionModule = "roles";
    public function index(Request $request)
    {
        checkPermissions("$this->permissionModule");
        if ($request->ajax()) {
            $baseurl = route('admin.roles.index');
            $data = Role::query()->notAdmin()

                ->when(empty(request()->get('order')), function ($query) {
                    $query->orderBy('id', 'desc');
                });
            $permissionArr = $this->getCrudPermissionArr($this->permissionModule);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) use ($baseurl, $permissionArr) {
                    return $this->actionButtonHtml($row->id, $baseurl, false, $permissionArr);
                })
                ->addColumn('created_at_text', function ($row) {
                    return dateTimeFormat($row->created_at);
                })
                ->rawColumns(['actions', 'created_at_text'])
                ->make(true);
        }
        $title =  'Roles';
        $module = $this->module;
        $permissions = Permission::get()->groupBy('module');
        return view('admin.role.index', compact('title', 'module', 'permissions'));
    }

    // Add or edit data
    public function store(RoleCreateUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->only('name', 'description');
            $permissions = $request->input('permissions', []); // Get permissions from the request (default to an empty array)

            $data = Role::where('id', $request->id)->notAdmin()->first();
            $message =  "$this->module updated successfully.";

            // Handle create/update
            if ($data == null) {
                checkPermissions("$this->permissionModule.create");
                $message =  "$this->module created successfully.";
                unset($validatedData['id']);
                $validatedData['created_by'] = auth()->id();
                $role = Role::create($validatedData);
            } else {
                checkPermissions("$this->permissionModule.update");
                $data->update($validatedData);
                $role = $data;
            }

            // Attach/detach permissions
            if ($role) {
                $role->permissions()->sync($permissions); // Sync the permissions (attach new, detach missing)
            }

            DB::commit();
            return success($message);
        } catch (Exception $e) {
            DB::rollBack();
            return error('Something went wrong!!!', $e->getMessage());
        }
    }


    // get edit data details
    public function show($id)
    {
        if (!request()->ajax()) {
            abort(404);
        }
        try {
            checkPermissions(["$this->permissionModule.update", "$this->permissionModule.show"]);
            $data = Role::notAdmin()->where('id', $id)
                ->with('permissions:id')
                ->select('id', 'name', 'description', 'created_at')
                ->first();
            if (empty($data)) {
                return error('Invalid request');
            }
            return success("Success", $data);
        } catch (\Exception $e) {
            return error('Something went wrong!', $e->getMessage());
        }
    }

    // delete data
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            checkPermissions("$this->permissionModule.delete");
            $role = Role::notAdmin()->find($id);
            if (!$role) {
                return error('Invalid role data details');
            }
            $role->permissions()->detach();
            $role->delete();
            DB::commit();
            return success("$this->module deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return error('Something went wrong!', $e->getMessage());
        }
    }
}
