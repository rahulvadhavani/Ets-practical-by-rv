<?php

namespace App;

trait CommonTrail
{

    public function actionButtonHtml($id, $baseurl, $isLink = false, $permssion = [])
    {
        $htmlData = "<div style=' class='actions-a'>";
        $deleteClass =  (in_array('delete', $permssion)) ? 'module_delete_record' : 'disabled';
        if ($isLink) {
            $showLink =  (in_array('show', $permssion)) ? "$baseurl/$id" : '#';
            $editLink =  (in_array('update', $permssion)) ? "$baseurl/$id/edit" : '#';
            $showClass =  (in_array('show', $permssion)) ? '' : 'disabled';
            $editClass =  (in_array('update', $permssion)) ? '' : 'disabled';
            $htmlData .= "<a href='$showLink' class='btn-circle ml-1 btn btn-dark btn-sm $showClass' data-id='$id' data-url='$baseurl' title='View'><i class='text-info fas fa-eye'></i></a>";
            $htmlData .= "<a href='$editLink' class='btn-circle ml-1 btn btn-dark btn-sm $editClass' data-id='$id' data-url='$baseurl' title='Edit'><i class='text-warning far fa-edit'></i></a>";
        } else {
            $showClass =  (in_array('show', $permssion)) ? 'module_view_record' : 'disabled';
            $editClass =  (in_array('update', $permssion)) ? 'module_edit_record' : 'disabled';
            $htmlData .= "<a class='btn-circle ml-1 btn btn-dark btn-sm $showClass' data-id='$id' data-url='$baseurl' title='View'><i class='text-info fas fa-eye'></i></a>";
            $htmlData .= "<a class='btn-circle ml-1 btn btn-dark btn-sm $editClass' data-id='$id' data-url='$baseurl' title='Edit'><i class='text-warning far fa-edit'></i></a>";
        }

        $htmlData .= "<a class='btn-circle ml-1 btn btn-dark btn-sm $deleteClass' data-id='$id' data-url='$baseurl' title='Delete'><i class='text-danger far fa-trash-alt'></i></a>";
        $htmlData .= "</div>";
        return $htmlData;
    }

    public function getCrudPermissionArr($module)
    {
        $permissionArr = [];
        if (request()->user()->can("$module.update")) {
            $permissionArr[] = 'update';
        }
        if (request()->user()->can("$module.show")) {
            $permissionArr[] = 'show';
        }
        if (request()->user()->can("$module.delete")) {
            $permissionArr[] = 'delete';
        }
        return $permissionArr;
    }
}
