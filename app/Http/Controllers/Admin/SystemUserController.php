<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SystemUserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:System User Show', only: ['index','show']),
            new Middleware('permission:System User Create', only: ['create','store']),
            new Middleware('permission:System User Edit', only: ['edit','update']),
            new Middleware('permission:System User Delete', only: ['destroy']),
        ];
    }


    public function index()
    {
        $users = User::whereDoesntHave('roles', function($query) {
            $query->whereIn('name', ['User','Super Admin']);
        })->get();
        return view('admin.system_user.index',compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name',['User','Super Admin'])->get();
        return view('admin.system_user.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number',
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number',
            'password' => 'required|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0'
        ], [
            'profile_image.max' => 'The Profile Image must not be larger than 2 MB.',
            'aadhar_image.max' => 'The Aadhar Image must not be larger than 2 MB.',
            'pan_image.max' => 'The Pan Image must not be larger than 2 MB.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $system_user = new User();
            $system_user->status = $request->status;
            $system_user->user_id = generateUniqueId('system_user');
            $system_user->first_name = $request->first_name;
            $system_user->last_name = $request->last_name;
            $system_user->name = $request->first_name.' '.$request->last_name;
            $system_user->date_of_birth = format_date_for_db($request->date_of_birth);
            $system_user->gender = $request->gender;
            $system_user->address = $request->address;
            $system_user->email = $request->email;
            $system_user->phone = $request->phone;
            $system_user->opt_mobile_no = $request->opt_mobile_no;
            $system_user->password = bcrypt($request->password);
            $system_user->aadhar_card_number = $request->aadhaar_number;
            $system_user->pan_card_number = $request->pan_card_number;

            if ($request->hasFile('profile_image')) {
                $system_user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            }

            if ($request->hasFile('aadhar_image')) {
                $system_user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            }

            if ($request->hasFile('pan_image')) {
                $system_user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            }

            $system_user->syncRoles($request->roles);
            $res = $system_user->save();

            if($res){
                return back()->with('success','User Added Successfully');
            }else{
                return back()->with('success','User Not Added');
            }
        }
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.system_user.show',compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::whereNotIn('name',['User','Auditor','Super Admin'])->get();
        return view('admin.system_user.edit',compact('user','roles'));
    }

    public function update(Request $request, string $id)
    {
        $system_user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $system_user->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $system_user->id,
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number,'. $system_user->id,
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number,'. $system_user->id,
            'password' => 'nullable|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0'
        ], [
            'profile_image.max' => 'The Profile Image must not be larger than 2 MB.',
            'aadhar_image.max' => 'The Aadhar Image must not be larger than 2 MB.',
            'pan_image.max' => 'The Pan Image must not be larger than 2 MB.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $system_user->first_name = $request->first_name;
            $system_user->last_name = $request->last_name;
            $system_user->name = $request->first_name.' '.$request->last_name;
            $system_user->status = $request->status;
            $system_user->date_of_birth = format_date_for_db($request->date_of_birth);
            $system_user->gender = $request->gender;
            $system_user->address = $request->address;
            $system_user->email = $request->email;
            $system_user->phone = $request->phone;
            $system_user->opt_mobile_no = $request->opt_mobile_no;
            if(isset($request->password)){
                $system_user->password = bcrypt($request->password);
            }
            $system_user->aadhar_card_number = $request->aadhaar_number;
            $system_user->pan_card_number = $request->pan_card_number;

            if ($request->hasFile('profile_image')) {
                $system_user->clearMediaCollection('system-user-image');
                $system_user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            }
        
            if ($request->hasFile('aadhar_image')) {
                $system_user->clearMediaCollection('system-user-aadhar');
                $system_user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            }
        
            if ($request->hasFile('pan_image')) {
                $system_user->clearMediaCollection('system-user-pan');
                $system_user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            }

            $res = $system_user->update();

            if($res){
                return back()->with('success','Auditor Updated Successfully');
            }else{
                return back()->with('success','Auditor Not Updated');
            }
        }
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
