@extends('layouts.app')

@section('title','Product Reviews')

@section('style')
<style>
    .star {
        font-size: 18px;
        margin-right: 2px;
    }
</style>
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Product Reviews</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product Reviews</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                @can('Review Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('reviews.create') }}"><i class="fa fa-plus"></i>Add New Review</a></li>
                @endcan
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active" id="Reviews-all">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl.no</th>
                                        <th>User</th>
                                        <th>Product</th>
                                        <th>Rating</th>
                                        <th>Review Text</th>
                                        <th>Media</th>
                                        <th>Created At</th>
                                        @canany(['Review Edit','Review Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $review->user->name }}</td>
                                        <td>{{ $review->product->name }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star" style="color: {{ $i <= $review->rating ? '#FFD700' : '#ccc' }};">&#9733;</span>
                                            @endfor
                                            ({{ $review->rating }})
                                        </td>
                                        <td class="text-wrap">{!! $review->review_text !!}</td>
                                        <td>
                                            @if($review->getMedia('review-media')->count())
                                                @foreach($review->getMedia('review-media') as $file)
                                                    @php
                                                        $mime = $file->mime_type;
                                                    @endphp

                                                    {{-- Show Images --}}
                                                    @if(Str::startsWith($mime, 'image/'))
                                                        <img src="{{ $file->getUrl() }}" 
                                                            class="img-thumbnail rounded me-2 mb-2" 
                                                            width="100">
                                                    
                                                    {{-- Show Videos --}}
                                                    @elseif(Str::startsWith($mime, 'video/'))
                                                        <video src="{{ $file->getUrl() }}" 
                                                            width="150" 
                                                            controls 
                                                            class="me-2 mb-2"></video>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if($review->video_link)
                                                @php
                                                    $videoId = '';

                                                    // Match normal YouTube links (watch?v=)
                                                    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $review->video_link, $matches)) {
                                                        $videoId = $matches[1];
                                                    }
                                                    // Match youtu.be links
                                                    elseif (preg_match('/youtu\.be\/([^?]+)/', $review->video_link, $matches)) {
                                                        $videoId = $matches[1];
                                                    }
                                                    // Match YouTube Shorts links
                                                    elseif (preg_match('/youtube\.com\/shorts\/([^?]+)/', $review->video_link, $matches)) {
                                                        $videoId = $matches[1];
                                                    }
                                                @endphp

                                                @if($videoId)
                                                    <iframe height="100"
                                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen>
                                                    </iframe>
                                                @endif
                                            @endif

                                        </td>
                                        <td>{{ format_datetime($review->created_at) }}</td>
                                        @canany(['Review Edit','Review Delete'])
                                        <td>
                                            @can('Review Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('reviews.edit',$review->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Review Delete')
                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
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
                        <div class="mt-3">
                            {{ $reviews->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
