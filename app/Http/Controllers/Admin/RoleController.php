<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public function __construct(){
        $this->view_path = 'admin.roles_permission.';
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Role Show', only: ['roles']),
            new Middleware('permission:Role Create', only: ['create_role']),
            new Middleware('permission:Role Edit', only: ['update_role']),
            new Middleware('permission:Role Delete', only: ['destroy_role']),
            new Middleware('permission:Asign Permission', only: ['addPermissionToRole','givePermissionToRole']),
        ];
    }

    public function roles(){
        $data['title'] = 'Roles';
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        return view($this->view_path.'roles')->with($data);
    }

    public function create_role(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|unique:roles,name'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $role = Role::create(['name' => $r->name]);
            if($role){
                return back()->with(['success'=>'Role Created Successfully']);
            }else{
                return back()->with(['error'=>'Role Not Created']);
            }
        }
    }

    public function update_role(Request $r, $roleId){
        $validator = Validator::make($r->all(), [
            'name' => ['required','string',Rule::unique('roles')->ignore($roleId),]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $role = Role::findOrFail($roleId);
            $role->name = $r->name;
            $res = $role->update(); 
            if($res){
                return back()->with(['success'=>'Role Updated Successfully']);
            }else{
                return back()->with(['error'=>'Role Not Updated']);
            }
        }
    }

    public function destroy_role(Request $r){
        $role = Role::find($r->roleId);
        $res = $role->delete();
        if($res){
            return back()->with(['success'=>'Role Deleted Successfully']);
        }else{
            return back()->with(['error'=>'Role Not Deleted']);
        }
    }

    public function addPermissionToRole($roleId){
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
                            ->where('role_has_permissions.role_id',$role->id)
                            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                            ->all();

        return view($this->view_path.'asign_permission',[
            'title' => 'Assign Permission',
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $res = $role->syncPermissions($request->permission);
        // return redirect()->back()->with('success','Permissions Added to Role Successfully');
        if($res){
            return back()->with(['success'=>'Permissions Updated to Role Successfully']);
        }else{
            return back()->with(['error'=>'Permissions Not Updated to Role']);
        }
    }
}