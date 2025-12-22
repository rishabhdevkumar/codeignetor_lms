<?php
use App\Models\RolePermissionModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

function rolePermission()
{
        $session = \Config\Services::session();
        $role = $session->get('role');

        //$role = 1;
        $RolePermissionModel = new RolePermissionModel();

        //$per = $RolePermissionModel->select('permission')->where('role_id', $role)->first();
        $per = $RolePermissionModel->select('permission')->whereIn('role_id', $role)->get()->getResultArray();

        // $valueData = json_decode($per['permission']);

        $accessValue = array();

        foreach ($per as $permissionData) {
            $permissions = json_decode($permissionData['permission'], true);
            foreach ($permissions as $key => $value) {
                if (isset($accessValue[$key])) {
                    $accessValue[$key] = array_merge($accessValue[$key], $value);
                } else {
                    $accessValue[$key] = $value;
                }
            }
        }

        $valueDataJson = json_encode($accessValue);
        $valueData = json_decode($valueDataJson);

        return $valueData;
}

function userProfile()
{
        $session = \Config\Services::session();
        $email = $session->get('email');
        $userModel = new UserModel();
        $userData = $userModel->where('email', $email)->first();
        return $userData;
}

function notificationHelper()
{
        $session = \Config\Services::session();
        $emp_code = $session->get('emp_code');
        $NotificationModel = new NotificationModel();
        $notiData = $NotificationModel->where('emp_codes', $emp_code)->where('status', null)->findAll();
        return $notiData;
}

