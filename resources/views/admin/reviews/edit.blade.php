@extends('layouts.app')

@section('title','Edit Product Review')

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
                <h1 class="page-title">Edit Product Review</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Product Reviews</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Review</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>User <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control select2" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $review->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-control select2" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $review->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Rating <span class="text-danger">*</span></label>
                        <div class="star-rating">
                            @for($i=5; $i>=1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }}>
                                <label for="star{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Review Text</label>
                        <textarea name="review_text" class="form-control" rows="4">{{ $review->review_text }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Review Video Link</label>
                        <input type="text" name="video_link" class="form-control" value="{{ old('video_link', $review->video_link) }}" id="">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Existing Media</label><br>

                        @if($review->getMedia('review-media')->count())
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($review->getMedia('review-media') as $file)
                                    @php
                                        $mime = $file->mime_type;
                                    @endphp

                                    <div class="position-relative border rounded shadow-sm p-2 bg-light" style="width: 120px;">
                                        
                                        {{-- Show Images --}}
                                        @if(Str::startsWith($mime, 'image/'))
                                            <img src="{{ $file->getUrl() }}" 
                                                class="img-fluid rounded" 
                                                style="max-height: 100px; object-fit: cover; width: 100%;">
                                        
                                        {{-- Show Videos --}}
                                        @elseif(Str::startsWith($mime, 'video/'))
                                            <video class="rounded w-100" style="max-height: 100px; object-fit: cover;" controls>
                                                <source src="{{ $file->getUrl() }}">
                                            </video>
                                        @endif

                                        {{-- Delete Button --}}
                                        <form action="{{ route('reviews.media.destroy', [$review->id, $file->id]) }}" 
                                            method="POST" 
                                            class="position-absolute top-0 end-0 m-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger rounded-circle"
                                                    style="width: 24px; height: 24px; padding:0;"
                                                    onclick="return confirm('Are you sure you want to delete this media?')">
                                                &times;
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted fst-italic">No media uploaded.</p>
                        @endif
                    </div>



                    <div class="mb-3">
                        <label>Add More Media</label>
                        <input type="file" name="media[]" class="form-control" multiple>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
