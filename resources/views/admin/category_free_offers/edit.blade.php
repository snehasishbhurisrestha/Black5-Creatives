@extends('layouts.app')

@section('title','Edit Platinum Card Offer')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Edit Platinum Card Offer</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category_free_offer.index') }}">Platinum Card Offer</a></li>
                    <li class="breadcrumb-item active">Edit Platinum Card Offer</li>
                </ol>
            </div>

            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('category_free_offer.index') }}">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('category_free_offer.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">

                <!-- LEFT MAIN CONTENT -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mb-4">Offer Setup</h5>

                            {{-- CATEGORY --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control select2" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $offer->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- REQUIRED PURCHASE --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Minimum Purchase Count</label>
                                <input type="number" min="1" class="form-control" id="required_qty"
                                    name="required_qty" value="{{ $offer->required_qty }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Free Product Count <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control" name="free_product_qty" 
                                    value="{{ $offer->free_product_qty }}" required placeholder="Example: 2">
                            </div>

                            {{-- STEP IMAGES SECTION --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Step Images</label>
                                <div id="step-images-container">
                                    @php
                                        $stepImages = $offer->getMedia('step_images');
                                        $stepImagesCount = $stepImages->count();
                                    @endphp
                                    
                                    @if($stepImagesCount > 0)
                                        @foreach($stepImages as $index => $stepImage)
                                            @php
                                                $stepNumber = $stepImage->getCustomProperty('step', $index + 1);
                                            @endphp
                                            <div class="mb-2">
                                                <label class="form-label">Step Image {{ $stepNumber }}</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3 mb-2">
                                                        <img src="{{ $stepImage->getUrl() }}" 
                                                             alt="Step {{ $stepNumber }}" 
                                                             class="img-thumbnail" 
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                        <div class="form-check mt-1">
                                                            <input type="checkbox" 
                                                                   name="remove_step_images[{{ $stepImage->id }}]" 
                                                                   value="{{ $stepImage->id }}" 
                                                                   class="form-check-input remove-step-checkbox">
                                                            <label class="form-check-label text-danger">
                                                                Remove Image
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="step_images[{{ $stepNumber }}]"
                                                               class="form-control step-file-input" 
                                                               accept="image/*">
                                                        <small class="text-muted">
                                                            Leave empty to keep current image
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    
                                    {{-- New step images input (for when required_qty increases) --}}
                                    @if($stepImagesCount < $offer->required_qty)
                                        @for($i = $stepImagesCount + 1; $i <= $offer->required_qty; $i++)
                                            <div class="mb-2">
                                                <label class="form-label">Step Image {{ $i }} (New)</label>
                                                <input type="file" name="step_images[{{ $i }}]"
                                                       class="form-control" accept="image/*" required>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="col-lg-4">

                    {{-- IMAGES CARD --}}
                    <div class="card">
                        <div class="card-header">
                            <h3>Main & Success Image</h3>
                        </div>
                        <div class="card-body">
                            {{-- OFFER IMAGE --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Offer Image</label>
                                @if($offer->hasMedia('offer_image'))
                                    <div class="mb-2">
                                        @php
                                            $offerImage = $offer->getFirstMedia('offer_image');
                                        @endphp
                                        <img src="{{ $offerImage->getUrl() }}" 
                                             alt="Current Offer Image" 
                                             class="img-thumbnail mb-2"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                        <div class="form-check">
                                            <input type="checkbox" id="remove_offer_image" 
                                                   name="remove_offer_image" value="{{ $offerImage->id }}" 
                                                   class="form-check-input remove-image-checkbox">
                                            <label class="form-check-label text-danger" for="remove_offer_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="offer_image" class="form-control" accept="image/*">
                                <small class="text-muted">
                                    @if($offer->hasMedia('offer_image'))
                                        Leave empty to keep current image
                                    @else
                                        Please upload an offer image
                                    @endif
                                </small>
                            </div>

                            {{-- SUCCESS IMAGE --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Success Image</label>
                                @if($offer->hasMedia('success_image'))
                                    <div class="mb-2">
                                        @php
                                            $successImage = $offer->getFirstMedia('success_image');
                                        @endphp
                                        <img src="{{ $successImage->getUrl() }}" 
                                             alt="Current Success Image" 
                                             class="img-thumbnail mb-2"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                        <div class="form-check">
                                            <input type="checkbox" id="remove_success_image" 
                                                   name="remove_success_image" value="{{ $successImage->id }}"
                                                   class="form-check-input remove-image-checkbox">
                                            <label class="form-check-label text-danger" for="remove_success_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="success_image" class="form-control" accept="image/*">
                                <small class="text-muted">
                                    @if($offer->hasMedia('success_image'))
                                        Leave empty to keep current image
                                    @else
                                        Please upload a success image
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS CARD --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Offer Status</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse">
                                    <i class="fe fe-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label d-flex fw-bold">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status1" name="is_active" value="1" 
                                           class="form-check-input" {{ $offer->is_active == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status2" name="is_active" value="0" 
                                           class="form-check-input" {{ $offer->is_active == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status2">Inactive</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 mb-2">Update Offer</button>
                                <a href="{{ route('category_free_offer.index') }}" class="btn btn-secondary w-100">Cancel</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</form>

@endsection

@section('script')
<script>
    // Dynamic step images generation when required_qty changes
    $('#required_qty').on('input', function () {
        let currentQty = parseInt($(this).val());
        let container = $('#step-images-container');
        let existingImages = $('.step-file-input').length; // Count existing file inputs
        
        // Clear only if we're decreasing and removing all images
        if (currentQty < existingImages) {
            // Ask for confirmation
            if (confirm('Reducing the purchase count will remove step images beyond the new count. Continue?')) {
                container.html('');
                
                // Rebuild the form for the new count
                for (let i = 1; i <= currentQty; i++) {
                    container.append(`
                        <div class="mb-2">
                            <label class="form-label">Step Image ${i} (New)</label>
                            <input type="file" name="step_images[${i}]"
                                   class="form-control" accept="image/*" required>
                        </div>
                    `);
                }
            } else {
                // Reset to original value
                $(this).val({{ $offer->required_qty }});
            }
        } else if (currentQty > existingImages) {
            // Add new step image inputs for additional steps
            for (let i = existingImages + 1; i <= currentQty; i++) {
                container.append(`
                    <div class="mb-2">
                        <label class="form-label">Step Image ${i} (New)</label>
                        <input type="file" name="step_images[${i}]"
                               class="form-control" accept="image/*" required>
                    </div>
                `);
            }
        }
    });

    // Handle image removal checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        // For main images
        document.querySelectorAll('.remove-image-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fileInput = this.closest('.mb-4, .mb-3').querySelector('input[type="file"]');
                if (this.checked) {
                    fileInput.required = true;
                } else {
                    fileInput.required = false;
                }
            });
        });
        
        // For step images
        document.querySelectorAll('.remove-step-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fileInput = this.closest('.mb-2').querySelector('.step-file-input');
                if (this.checked) {
                    fileInput.required = true;
                } else {
                    fileInput.required = false;
                }
            });
        });
    });

    // Initialize select2
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
@endsection