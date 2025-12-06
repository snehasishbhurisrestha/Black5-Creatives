@extends('layouts.app')

@section('title','Platinum Card Offer')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Platinum Card Offer</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Platinum Card Offer</li>
                </ol>
            </div>

            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('category_free_offer.create') }}">
                        <i class="fa fa-plus"></i> Add New Offer
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active">

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-hover js-basic-example dataTable table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl.no</th>
                                        <th>Category</th>
                                        <th>Required Quantity</th>
                                        <th>Free Product QTY</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($offers as $offer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $offer->category->name }}</strong>
                                        </td>

                                        <td>
                                            Buy <strong>{{ $offer->required_qty }}</strong> items
                                        </td>

                                        <td>
                                            {{-- @foreach($offer->items as $item)
                                                <div class="border rounded p-1 mb-1">
                                                    <strong>{{ $item->variationOption->variation_name }}</strong>
                                                    <span class="badge bg-primary">
                                                        x {{ $item->free_qty }}
                                                    </span>

                                                    <div>
                                                        <small class="text-muted">
                                                            {{ $item->variationOption->variation->product->name }}
                                                            ({{ $item->variationOption->variation->name }})
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach --}}
                                            {{ $offer->free_product_qty }}
                                        </td>

                                        <td>
                                            @if($offer->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{ format_datetime($offer->created_at) }}</td>

                                        <td>
                                            <a class="btn btn-icon btn-sm" 
                                               href="{{ route('category_free_offer.edit', $offer->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('category_free_offer.destroy', $offer->id) }}"
                                                  method="POST"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-icon btn-sm"
                                                        onclick="return confirm('Are you sure?')" 
                                                        type="submit">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No offers created yet.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div> <!-- /tab-pane -->
        </div>
    </div>
</div>

@endsection
