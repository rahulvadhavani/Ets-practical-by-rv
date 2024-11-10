<?php

namespace App\Http\Controllers;

use App\CommonTrail;
use App\Http\Requests\CustomerCreateUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use CommonTrail;
    public $module = "Customer";
    public $permissionModule = "customers";
    public function index(Request $request)
    {

        checkPermissions("$this->permissionModule");
        if ($request->ajax()) {
            $baseurl = route('admin.customers.index');
            $data = Customer::query()
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
        $title =  'Customers';
        $module = $this->module;
        return view('admin.customer.index', compact('title', 'module'));
    }

    // Add or edit data
    public function store(CustomerCreateUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->only('first_name', 'last_name', 'contact_number', 'email', 'address');
            $data = Customer::where('id', $request->id)->first();
            $message =  "$this->module updated successfully.";

            // handle create update
            if ($data == null) {
                checkPermissions("$this->permissionModule.create");
                $message =  "$this->module created successfully.";
                unset($validatedData['id']);
                $validatedData['created_by'] = auth()->id();
                Customer::create($validatedData);
            } else {
                checkPermissions("$this->permissionModule.update");
                $data->update($validatedData);
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
            $data = Customer::where('id', $id)
                ->select('id', 'first_name', 'last_name', 'email', 'created_at', 'address', 'contact_number')
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
            $data = Customer::where('id', $id)->first();
            if (empty($data)) {
                return error('Invalid data details');
            }
            $data->delete();
            DB::commit();
            return success("$this->module deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return error('Something went wrong!', $e->getMessage());
        }
    }

    public function deleteSelected(Request $request)
    {
        DB::beginTransaction();
        try {
            checkPermissions("$this->permissionModule.delete");
            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    'min:1'
                ],
                'id.*' => [
                    'required',
                    'integer'
                ]
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 200);
            }

            $datas = Customer::whereIn('id', $request->id)
                ->delete();
            DB::commit();
            return success("$this->module deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return error('Something went wrong!', $e->getMessage());
        }
    }
}
