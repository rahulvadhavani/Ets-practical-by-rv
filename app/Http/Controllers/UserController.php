<?php

namespace App\Http\Controllers;

use App\CommonTrail;
use App\Http\Requests\UserCreateUpdateRequest;
use App\Http\Requests\UserDeleteRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Role;
use App\Models\State;
use App\Models\Upload;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use CommonTrail;
    public $module = "User";
    public $permissionModule = "users";
    public function index(Request $request)
    {
        checkPermissions("$this->permissionModule");
        if ($request->ajax()) {
            $baseurl = route('admin.users.index');
            $data = User::query()
                ->with(['profile', 'roles:id,name', 'city:id,name', 'state:id,name'])
                ->userRole()
                ->whereNot('id', auth()->id())
                ->when(empty(request()->get('order')), function ($query) {
                    $query->orderBy('id', 'desc');
                });
            $permissionArr = $this->getCrudPermissionArr($this->permissionModule);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) use ($baseurl, $permissionArr) {
                    return $this->actionButtonHtml($row->id, $baseurl, true, $permissionArr);
                })
                ->addColumn('image', function ($row) {
                    $file = $row?->profile?->file_link ?? asset('dist/img/default-150x150.png');
                    $image = "<img src='$file' class='img-fluid img-radius' width='40px' height='40px'>";
                    return $image;
                })
                ->addColumn('city_name', function ($row) {
                    return $row?->city?->name ?? '-';
                })
                ->addColumn('state_name', function ($row) {
                    return $row?->state?->name ?? '-';
                })
                ->addColumn('created_at_text', function ($row) {
                    return dateTimeFormat($row->created_at);
                })
                ->rawColumns(['actions', 'image', 'roles_text','created_at_text'])
                ->make(true);
        }
        $title =  'Users';
        $module = $this->module;
        return view('admin.users.index', compact('title', 'module'));
    }

    public function create()
    {
        checkPermissions("$this->permissionModule.create");
        $title =  'Create User';
        $states = State::select('id', 'name')->get();
        $roles = Role::select('id', 'name')->notAdmin()->get();
        $module = $this->module;
        return view('admin.users.create', compact('title', 'states', 'roles', 'module'));
    }
    public function edit($id)
    {
        checkPermissions("$this->permissionModule.update");
        $data  = User::with(['uploads', 'roles:id,name'])->userRole()->where('id', $id)->first();
        if ($data == null) {
            abort(404);
        }
        $title =  "Edit $this->module";
        $states = State::getStates();
        $roles = Role::getRoles();
        $module = $this->module;

        $data->profile_img = $data?->uploads?->where('file_usage', 'profile')?->first()?->file_link ?? null;
        $data->document = $data?->uploads?->where('file_usage', 'document') ?? [];
        return view('admin.users.edit', compact('title', 'states', 'roles', 'module', 'data'));
    }

    // add or edit user
    public function store(UserCreateUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            checkPermissions("$this->permissionModule.create");
            $validatedData = $request->only('first_name', 'last_name', 'email', 'contact_number', 'postcode', 'hobbies', 'gender', 'city_id', 'state_id', 'password');
            $validatedData['hobbies'] = explode(',', $validatedData['hobbies']);
            $validatedData['is_super_admin'] = User::TYPE_USER;
            $validatedData['created_by'] = auth()->id();
            $user = User::create($validatedData);

            //save profile
            if ($request->hasFile('image')) {
                Upload::saveUpload($request->file('image'), $user, 'profile');
            }

            //save document
            if ($request->hasFile('files') && $request->file('files')) {
                foreach ($request->file('files') as $file) {
                    // Ensure the file is valid before proceeding
                    if ($file->isValid()) {
                        Upload::saveUpload($file, $user, 'document');
                    }
                }
            }
            $user->roles()->attach($request->roles); // Attaches the selected roles

            if ($user == null) {
                unset($validatedData['id']);
                User::create($validatedData);
            } else {
                if ($request->image != '') {
                    $path = public_path('uploads/user/' . basename($user->image));
                    $this->destroyFileHelper($path);
                }
                $user->update($validatedData);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return error('Something went wrong!!!', $e->getMessage());
        }
        return success("$this->module created successfully.");
    }
    public function update(UserCreateUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            checkPermissions("$this->permissionModule.update");
            $user = User::with(['uploads', 'roles:id,name'])->userRole()->where('id', $id)->first();
            if ($user == null) {
                return error("$this->module not found.");
            }
            $validatedData = $request->only('first_name', 'last_name', 'email', 'contact_number', 'postcode', 'hobbies', 'gender', 'city_id', 'state_id');
            $validatedData['hobbies'] = explode(',', $validatedData['hobbies']);
            if ($request->password != null) {
                $validatedData['password'] = $request->password;
            }
            $newRoles = $request->input('roles'); // Assuming the 'roles' field contains the role ids.
            $currentRoles = $user->roles->pluck('id')->toArray(); // Get the current roles of the user

            $rolesToAttach = array_diff($newRoles, $currentRoles);  // Determine roles to attach (new roles)
            $rolesToDetach = array_diff($currentRoles, $newRoles); // Determine roles to detach (removed roles)

            // Attach new roles
            if (!empty($rolesToAttach)) {
                $user->roles()->attach($rolesToAttach);
            }

            // Detach removed roles
            if (!empty($rolesToDetach)) {
                $user->roles()->detach($rolesToDetach);
            }


            //save profile
            if ($request->hasFile('image')) {
                Upload::deleteUpload($user->profile);
                Upload::saveUpload($request->file('image'), $user, 'profile');
            }

            //save document
            if ($request->hasFile('files') && $request->file('files')) {
                Upload::deleteUpload($user->documents);
                foreach ($request->file('files') as $file) {
                    // Ensure the file is valid before proceeding
                    if ($file->isValid()) {
                        Upload::saveUpload($file, $user, 'document');
                    }
                }
            }

            $user->update($validatedData);
            DB::commit();
            return success('User ' . $request->id == 0 ? 'added' : 'updated' . ' successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error('Something went wrong!!!', $e->getMessage());
        }
    }

    // get edit user details
    public function show($id)
    {
        checkPermissions("$this->permissionModule.show");
        $data  = User::with(['uploads', 'roles:id,name'])->userRole()->where('id', $id)->first();
        if ($data == null) {
            abort(404);
        }
        $title =  "$this->module Detail";
        $states = State::getStates();
        $roles = Role::getRoles();
        $module = $this->module;
        $data->profile_img = $data?->uploads?->where('file_usage', 'profile')?->first()?->file_link ?? null;
        $data->document = $data?->uploads?->where('file_usage', 'document') ?? [];
        return view('admin.users.show', compact('title', 'states', 'roles', 'module', 'data'));
    }
    // delete user
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            checkPermissions("$this->permissionModule.delete");
            $user = User::userRole()->where('id', $id)->first();
            if (empty($user)) {
                return error('Invalid request');
            }
            $user->roles()->detach();
            if ($user->profile) {
                Upload::deleteUpload($user->profile);
            }
            if ($user->documents) {
                Upload::deleteUpload($user->documents);
            }
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return error('Something went wrong!', $e->getMessage());
        }
        return success("User deleted successfully.");
    }

    public function deleteSelected(UserDeleteRequest $request)
    {
        try {
            checkPermissions("$this->permissionModule.delete");
            $users = User::userRole()->whereIn('id', $request->id);
            foreach ($users->get() as $user) {
                $user->roles()->detach();
                if ($user->profile) {
                    Upload::deleteUpload($user->profile);
                }
                if ($user->documents) {
                    Upload::deleteUpload($user->documents);
                }
                $user->delete();
            }
            DB::commit();
            return success("Users deleted successfully.");
        } catch (\Exception $e) {
            return error('Something went wrong!', $e->getMessage());
        }
    }

    public function getCitiesByState($stateId)
    {
        $states = City::where('state_id', $stateId)->get()->pluck('name', 'id');
        return success('success', $states);
    }
}
