@extends('layouts.app')

    @section('title') Role @endsection
    
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
                <ul class="nav nav-tabs page-header-tab">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li>
                    @can('Role Create')
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Student-add" id="add-tab">Add</a></li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>

    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane active" id="Student-all">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-striped table_custom border-style spacing5">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            @can('Asign Permission')
                                            <th>Asign Permission to Role</th>
                                            @endcan
                                            @canany(['Role Edit','Role Delete'])
                                            <th>Action</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            @can('Asign Permission')
                                            <td><a href="{{ route('role.addPermissionToRole', ['roleId' => $role->id]) }}" class="btn btn-outline-success">Asign Permission</a></td>
                                            @endcan
                                            @canany(['Role Edit','Role Delete'])
                                            <td class="d-flex">
                                                @can('Role Edit')
                                                <button type="button" class="btn btn-icon btn-sm edit-role-btn" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}"> 
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endcan
                                                @can('Role Delete')
                                                <form action="{{ route('role.destroy', ['roleId' => $role->id]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
                                                    {{-- <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o text-danger"></i></button> --}}
                                                </form>
                                                @endcan
                                            </td>
                                            @endcanany
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="Student-add">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Role Details</h3>
                                    {{-- <div class="card-options ">
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="row clearfix">
                                        <form action="{{ route('role.create') }}" id="role-form" method="post">
                                        @csrf
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="role-name">Role Name</label>
                                                <input type="text" class="form-control" name="name" id="role-name" placeholder="Write Role name here...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="submit" class="btn btn-outline-secondary">Cancel</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('script')
    <script>
        $(document).ready(function () {
            // Handle Edit button click
            $('.edit-role-btn').on('click', function () {
                const roleId = $(this).data('role-id');
                const roleName = $(this).data('role-name');
    
                // Populate form fields
                $('#role-id').val(roleId);
                $('#role-name').val(roleName);
    
                // Update form action for editing
                let form = $('#role-form');
                let baseAction = "{{ route('role.update', ':roleId') }}";
                form.attr('action', baseAction.replace(':roleId', roleId));
    
                // Switch to the Add tab
                $('#add-tab').trigger('click');
            });
    
            // Handle Add button click
            $('#add-role-btn').on('click', function () {
                resetForm();
            });
            $('#list-tab').on('click', function () {
                resetForm();
            });
    
            // Reset form to default state for creating a new role
            window.resetForm = function () {
                $('#role-form')[0].reset();
                $('#role-id').val('');
                $('#role-form').attr('action', "{{ route('role.create') }}");
            };
        });
    </script>
    
    
    
    @endsection