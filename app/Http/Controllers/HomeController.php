<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\{AdminProfileRequest, ChangePasswordRequest, StaticPageRequest};
use App\Models\Customer;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Upload;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Dashboard';
        $data = [];
        $statistics = Cache::remember('statistics', 0, function () {
            return [
                'UsersCount' => User::userRole()->count(),
                'RoleCount' => Role::notAdmin()->count(),
                'SuppliersCount' => Supplier::count(),
                'CustomerCount' => Customer::count(),
            ];
        });
        if (request()->user()->can('users')) {
            $data['Users'] = ['count' => $statistics['UsersCount'], 'route' => route('admin.users.index'), 'class' => 'bg-primary', 'icon' => 'fas fa-solid fa-users'];
        }
        if (request()->user()->can('roles')) {
            $data['Roles'] = ['count' => $statistics['RoleCount'], 'route' => route('admin.roles.index'), 'class' => 'bg-primary', 'icon' => 'fa-solid fa-shield-halved'];
        }
        if (request()->user()->can('suppliers')) {
            $data['Suppliers'] = ['count' => $statistics['SuppliersCount'], 'route' => route('admin.suppliers.index'), 'class' => 'bg-primary', 'icon' => 'fa-solid fa-truck-fast'];
        }
        if (request()->user()->can('customers')) {
            $data['Customers'] = ['count' => $statistics['CustomerCount'], 'route' => route('admin.customers.index'), 'class' => 'bg-primary', 'icon' => 'fas fa-users'];
        }
        $cards = $data;
        return view('admin.dashboard', compact('title', 'cards'));
    }

    public function profile()
    {
        $title = 'Profile';
        $user = Auth::user();
        $user->profile_img = $user?->uploads?->where('file_usage', 'profile')?->first()?->file_link ?? null;
        return view('admin.profile', compact('title', 'user'));
    }

    public function updateAdminProfile(AdminProfileRequest $request)
    {
        try {
            $userId = Auth::user()->id;
            $post_data = $request->only('first_name', 'last_name', 'gender', 'contact_number', 'image');
            $post_data['user_id'] = $userId;
            $user = User::find($userId);
            if ($user == null) {
                return error('Invalid user detail');
            }
            //save profile

            if ($request->hasFile('image')) {
                Upload::deleteUpload($user->profile);
                Upload::saveUpload($request->file('image'), $user, 'profile');
            }
            $user->update($post_data);
            return success('Profile updated successfully');
        } catch (Exception $e) {
            return error('Something went wrong!', $e->getMessage());
        }
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        try {
            $userId = Auth::user()->id;
            $user = User::where('id', $userId)->first();
            $validatedData['password'] = $request->password;
            $user->update($validatedData);
            return success('Password updated successfully');
        } catch (Exception $e) {
            return error('Something went wrong!', $e->getMessage());
        }
    }

    public function Logout()
    {
        Auth::logout();
        return \Redirect::to("admin/login")
            ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
    }
}
