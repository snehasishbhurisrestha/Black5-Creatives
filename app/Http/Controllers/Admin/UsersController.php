<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UsersController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Web User Show', only: ['index','show']),
            // new Middleware('permission:Web User Create', only: ['create','store']),
            // new Middleware('permission:Web User Edit', only: ['edit','update']),
            new Middleware('permission:Web User Delete', only: ['destroy']),
        ];
    }

    public function index(){
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();
        return view('admin.web_user.index',compact('users'));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $res = $user->delete();
        if($res){
            return back()->with(['success'=>'User Deleted Successfully']);
        }else{
            return back()->with(['error'=>'User Not Deleted']);
        }
    }
}