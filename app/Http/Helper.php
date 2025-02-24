<?php

use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

if (!function_exists('success')) {
    function success($message = "Success", $data = [])
    {
        $res = ['status' => true,  'message' => $message, 'code' => 200];
        if (!empty($data)) {
            $res = ['status' => true,  'message' => $message, 'data' => $data, 'code' => 200];
        }
        return $res;
    }
}
if (!function_exists('error')) {
    function error($message = "Something went wrong!!", $data = [])
    {
        $res = ['status' => false,  'message' => $message, "data" => $data, 'code' => 400];
        return $res;
    }
}

if (!function_exists('authError')) {
    function authError($message = "Unauthenticated")
    {
        $res = [
            'status' => false,
            'code' => 401,
            'message' => $message,
        ];
        return $res;
    }
}

if (!function_exists('validatorError')) {
    function validatorError($validate)
    {
        return response()->json(
            [
                'status' => false,
                'code' => 422,
                'message' => $validate->messages()->first(),
                'errors' => $validate->errors(),
            ],
            200
        );
    }
}

if (!function_exists('loginAndSignupSuccess')) {
    function loginAndSignupSuccess($message, $tokenBody, $data = null)
    {
        $response = array_merge([
            'status' => true,
            'code' => 200,
            'message' => $message,
        ], $tokenBody);

        if (isset($data)) {
            $response = array_merge($response, [
                'data' => $data,
            ]);
        }
        return response()->json($response);
    }
}


if (!function_exists('imageUploader')) {
    function imageUploader($image, $filePath, $isUrl = false, $storeAs = '')
    {
        $path = public_path($filePath);
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        if ($storeAs == '') {
            $imageName = basename($path) . '_' . time() . '.' . $image->extension();
        } else {
            $imageName = $storeAs;
        }
        $image->move($path, $imageName);
        return $isUrl ? url($filePath, $imageName) : $filePath . $imageName;
    }
}


if (!function_exists('unlinkFile')) {
    function unlinkFile($path)
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

if (!function_exists('checkPermissions')) {
    function checkPermissions($permissionsOrModules)
    {
        $permissionsOrModules = is_array($permissionsOrModules) ? $permissionsOrModules : [$permissionsOrModules];
        // Loop through each permission or module and check if the user has it
        foreach ($permissionsOrModules as $permissionOrModule) {
            // Check if the user has permission or module
            if (Gate::allows($permissionOrModule)) {
                return true;
            }
        }

        // If the user does not have any of the permissions, return an error
        $errorMessage = "Unauthorised";
        abort(403, $errorMessage);
    }
}
if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($dateTime = null, $format = 'Y-m-d H:i:s')
    {
        return $dateTime == null ? '-' : date('Y-m-d H:i:s', strtotime($dateTime));
    }
}
