@extends('layouts.app')

@section('title','Add Product Review')

@section('style')
<style>
    .star-rating {
        direction: rtl;
        display: inline-flex;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label {
        font-size: 30px;
        color: #ccc;
        cursor: pointer;
        padding: 0 3px;
    }
    .star-rating input[type="radio"]:checked ~ label {
        color: gold;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: gold;
    }
</style>
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Add Product Review</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Product Reviews</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Review</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>User <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control select2" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-control select2" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Rating <span class="text-danger">*</span></label>
                        <div class="star-rating">
                            @for($i=5; $i>=1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                                <label for="star{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                        @error('rating')<br><span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Review Text</label>
                        <textarea name="review_text" class="form-control" rows="4">{{ old('review_text') }}</textarea>
                        @error('review_text')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Review Video Link</label>
                        <input type="text" name="video_link" class="form-control" value="{{ old('video_link') }}" id="">
                        @error('video_link')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>Upload Media (Images/Videos)</label>
                        <input type="file" name="media[]" class="form-control" multiple>
                        <small class="text-muted">You can upload multiple files</small>
                        @error('media')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
