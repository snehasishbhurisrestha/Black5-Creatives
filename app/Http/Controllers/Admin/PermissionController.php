<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    public function __construct(){
        $this->view_path = 'admin.roles_permission.';
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Permission Show', only: ['permission']),
            new Middleware('permission:Permission Create', only: ['create_permission']),
            new Middleware('permission:Permission Edit', only: ['update_permission']),
            new Middleware('permission:Permission Delete', only: ['destroy_permission']),
        ];
    }

    public function permission(){
        $data['title'] = 'Permission';
        $data['permissions'] = Permission::all();
        return view($this->view_path.'permission')->with($data);
    }

    public function create_permission(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|unique:permissions,name'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $permission = Permission::create(['name' => $r->name]);
            if($permission){
                return back()->with(['success'=>'Role Created Successfully']);
            }else{
                return back()->with(['error'=>'Role Not Created']);
            }
        }
    }

    public function update_permission(Request $r, $permissionId){
        $validator = Validator::make($r->all(), [
            'name' => ['required','string',Rule::unique('permissions')->ignore($permissionId),]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $permission = Permission::findOrFail($permissionId);
            $permission->name = $r->name;
            $res = $permission->update(); 
            if($res){
                return back()->with(['success'=>'Permission Updated Successfully']);
            }else{
                return back()->with(['error'=>'Permission Not Updated']);
            }
        }
    }

    public function destroy_permission(Request $r){
        $permission = Permission::find($r->permissionId);
        $res = $permission->delete();
        if($res){
            return back()->with(['success'=>'Permission Deleted Successfully']);
        }else{
            return back()->with(['error'=>'Permission Not Deleted']);
        }
    }
}