@extends('layouts.app')

    @section('title') Permission @endsection
    
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
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all">List</a></li>
                    @can('Permission Create')
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
                                    <thead>
                                        <tr>
                                            <th>Permission Name</th>
                                            @canany(['Permission Edit','Permission Delete'])
                                            <th>Action</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->name }}</td>
                                            @canany(['Permission Edit','Permission Delete'])
                                            <td class="d-flex">
                                                @can('Permission Edit')
                                                <button type="button" class="btn btn-icon btn-sm edit-permission-btn" data-permission-id="{{ $permission->id }}" data-permission-name="{{ $permission->name }}"> 
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endcan
                                                @can('Permission Delete')
                                                <form action="{{ route('permission.destroy', ['permissionId' => $permission->id]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
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
                                    <h3 class="card-title">Permission Details</h3>
                                    {{-- <div class="card-options ">
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="row clearfix">
                                        <form action="{{ route('permission.create') }}" id="permission-form" method="post">
                                        @csrf
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="permission-name" class="form-label">Permission Name</label>
                                                <input type="text" name="name" class="form-control" id="permission-name" placeholder="Write permission name here..." required="">
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
            $('.edit-permission-btn').on('click', function () {
                const permissionId = $(this).data('permission-id');
                const permissionName = $(this).data('permission-name');
    
                // Populate form fields
                $('#permission-id').val(permissionId);
                $('#permission-name').val(permissionName);
    
                // Update form action for editing
                let form = $('#permission-form');
                let baseAction = "{{ route('permission.update', ':permissionId') }}";
                form.attr('action', baseAction.replace(':permissionId', permissionId));

                // Switch to the Add tab
                $('#add-tab').trigger('click');
            });
    
            // Handle Add button click
            $('#add-permission-btn').on('click', function () {
                resetForm(); // Reset form to default create state
            });
    
            // Reset form to default state for creating a new permission
            window.resetForm = function () {
                $('#permission-form')[0].reset();
                $('#permission-id').val('');
                $('#permission-form').attr('action', "{{ route('permission.create') }}");
            };
        });
    </script>
    @endsection