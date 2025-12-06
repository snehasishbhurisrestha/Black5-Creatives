@extends('layouts.app')

    @section('title') Assign Permission @endsection
    
    @section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Role Permission</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('roles') }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('roles') }}"><i class="fadeIn animated bx bx-arrow-back"></i>Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="section-body mt-4">
        <div class="container-fluid">

            <div class="card mt-4">
                <div class="card-body">
                    <form class="custom-validation" action="{{ route('role.give-permissions', ['roleId' => $role->id]) }}" method="post">
                        @csrf
                        <div class="">
                            <h4 class="mb-3">Permissions Name</h4>
                            @foreach ($permissions as $item)
                            <div class="form-check form-check-inline">
                                <input 
                                    class="form-check-input" 
                                    name="permission[]" 
                                    id="{{ $item->name}}" 
                                    type="checkbox" 
                                    value="{{ $item->name}}" 
                                    {{ in_array($item->id, $rolePermissions) ? 'checked': '' }}
                                />
                                <label class="form-check-label" for="{{ $item->name}}">{{ $item->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-5">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @endsection