@extends('layouts.app')

    @section('title') Contact Us @endsection
    
    @section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Contact Us</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                    </ol>
                </div>
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
                                <table class="table table-hover js-exportable-desc dataTable table-striped table_custom border-style spacing5">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-wrap">Date</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            @canany(['ContactUs Delete'])
                                            <th>Action</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contacts as $enquiry)
                                        <tr>
                                            <td class="text-wrap">{!! format_datetime($enquiry->created_at) !!}</td>
                                            <td class="text-wrap">{{ $enquiry->name }}</td>
                                            <td>{{ $enquiry->phone }}</td>
                                            <td>{{ $enquiry->email }}</td>
                                            <td>{{ $enquiry->subject }}</td>
                                            <td>{{ $enquiry->message }}</td>
                                            @canany(['ContactUs Delete'])
                                            <td>
                                                @can('ContactUs Delete')
                                                <form action="{{ route('contact-us.destroy', $enquiry->id) }}" onsubmit="return confirm('Are you sure?')" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
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
            </div>
        </div>
    </div>

    @endsection