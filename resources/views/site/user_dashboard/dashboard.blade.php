@extends('layouts.web-app')

@section('title') @yield('tab-title') @endsection

@section('style')
<style>
    .list-group-item {
        cursor: pointer;
    }
    .list-group-item.active {
        background-color: #fe0031;
        color: #fff;
        border-color: #fe0031;
    }
    .list-group-item.active a{
        color: #fff;
        font-weight: 700;
    }
</style>
@endsection

@section('content')

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            @include('site.user_dashboard.tabs')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="tab-content">

                @yield('tab-pane-content')

            </div>
        </div>
    </div>
</div>

@endsection