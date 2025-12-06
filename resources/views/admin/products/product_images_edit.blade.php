@extends('layouts.app')

@section('title','Products')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/file-uploader/css/jquery.dm-uploader.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/file-uploader/css/styles.css') }}"/>
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Products</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Product Images</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('product.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('products.product-images-process') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            @include('admin.products.nav-tabs-edit')
                            <input type="hidden" name="id" value="{{ request()->segment(4) }}">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="pricedetails" role="tabpanel">
                                    @if($product->product_type != 'simple')
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Select Variation Option</label>
                                            <select class="form-control" id="option-selector">
                                                <option value="0">Default Images (no specific option)</option>
                                                @foreach($variations as $variation)
                                                    <optgroup label="{{ $variation->name }}">
                                                        @foreach($variation->options as $option)
                                                            <option value="{{ $option->id }}">{{ $option->variation_name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="dm-uploader-container">
                                        <div id="drag-and-drop-zone" class="dm-uploader text-center">
                                            <p class="dm-upload-icon">
                                                <i class="icon-upload"></i>
                                            </p>
                                            <p class="dm-upload-text">Drop files here or click to upload.&nbsp;<span style="text-decoration: underline">Browse</span></p>
                                    
                                            <a class='btn btn-md dm-btn-select-files'>
                                                <input type="file" name="file" size="40" multiple="multiple">
                                            </a>
                                    
                                            <ul class="dm-uploaded-files" id="files-image">
                                                <?php if (!empty($product_images)):
                                                    foreach ($product_images as $image):?>
                                                        <li class="media" id="uploaderFile<?php echo $image->getCustomProperty('file_id'); ?>" data-option-id="{{ $image->getCustomProperty('option_id', 0) }}">
                                                            <img src="{{ $image->getUrl() }}" alt="">
                                                            <a href="javascript:void(0)" class="btn-img-delete btn-delete-product-img text-center" data-file-id="{{ $image->getCustomProperty('file_id') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove this Item">
                                                                <i class="fa fa-trash-o text-light"></i>
                                                            </a>
                                                            @if ($image->getCustomProperty('option_id'))
                                                                <span class="badge bg-info option-badge" style="z-index:99999999;">
                                                                    {{ $image->getCustomProperty('option_name') }}
                                                                </span>
                                                            @endif
                                                            @if ($image->getCustomProperty('is_main'))
                                                                <a href="javascript:void(0)" class="float-start btn btn-success mt-1 btn-sm waves-effect btn-set-image-main" style="padding-bottom: 0px;padding-top: 0px;padding-right: 4px;padding-left: 4px;">Main</a>
                                                            @else
                                                                <a href="javascript:void(0)" class="float-start btn btn-secondary btn-sm mt-1 waves-effect btn-set-image-main" style="padding-bottom: 0px;padding-top: 0px;padding-right: 4px;padding-left: 4px;" data-file-id="{{ $image->getCustomProperty('file_id') }}">Main</a>
                                                            @endif
                                                            
                                                        </li>
                                                    <?php endforeach;
                                                endif; ?>
                                            </ul>
                                    
                                            <div class="error-message-img-upload"></div>
                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Save & Publish</h3>
                            {{-- <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div> --}}
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                        Save & Next
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect">
                                        Cancel
                                    </button>
                                </div>
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
    <script src="{{ asset('assets/admin-assets/plugins/file-uploader/js/jquery.dm-uploader.min.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/plugins/file-uploader/js/demo-ui.js') }}"></script>

    <script type="text/html" id="files-template-image">
        <li class="media">
            <img class="preview-img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="bg">
            <div class="media-body">
                <div class="progress">
                    <div class="dm-progress-waiting">Waiting...</div>
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </li>
    </script>
    <script>
        $(document).ready(function() {

            // Track current variation selection
            let currentOptionId = $('#option-selector').val();
    
            // Filter images when option changes
            $('#option-selector').change(function() {
                currentOptionId = $(this).val();
                filterImagesByOption(currentOptionId);
            });
            
            // Function to filter images by option
            function filterImagesByOption(optionId) {
                if (optionId === '0') {
                    // Show all images
                    $('#files-image li').show();
                } else {
                    // Show only images for selected option
                    $('#files-image li').each(function() {
                        const imgOptionId = $(this).data('option-id');
                        $(this).toggle(imgOptionId == optionId);
                    });
                }
            }
            /*
            * Image Uploader
            */
            $('#drag-and-drop-zone').dmUploader({
                url: "{{ route('products.product-gallery-save') }}",
                maxFileSize: 5242880, // 5MB
                maxFiles: 4, // Allow up to 4 files
                queue: true,
                allowedTypes: 'image/*',
                extFilter: ["jpg", "jpeg", "png", "gif", "webp"],
                extraData: function(id) {
                    const optionId = $('#option-selector').val();
                    const optionName = $('#option-selector option:selected').text();
                    
                    return {
                        "file_id": id,
                        "product_id": "{{ request()->segment(4) }}",
                        "option_id": optionId,
                        "_token": "{{ csrf_token() }}"
                    };
                },
                onDragEnter: function() {
                    this.addClass('active');
                },
                onDragLeave: function() {
                    this.removeClass('active');
                },
                onNewFile: function(id, file) {
                    ui_multi_add_file(id, file, "image");
                    if (typeof FileReader !== "undefined") {
                        var reader = new FileReader();
                        var img = $('#uploaderFile' + id).find('img');

                        reader.onload = function(e) {
                            img.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                },
                onBeforeUpload: function(id) {
                    $('#uploaderFile' + id + ' .dm-progress-waiting').hide();
                    ui_multi_update_file_progress(id, 0, '', true);
                    ui_multi_update_file_status(id, 'uploading', 'Uploading...');
                },
                onUploadProgress: function(id, percent) {
                    ui_multi_update_file_progress(id, percent);
                },
                onUploadSuccess: function(id, response) {
                    if (response.success) {
                        ui_multi_update_file_status(id, 'success', 'Upload Complete');
                        ui_multi_update_file_progress(id, 100, 'success', false);
                        // Fetch and update the product images
                        $.ajax({
                            type: "POST",
                            url: "{{ route('products.get-product-temp-images') }}",
                            data: {
                                "file_id": id,
                                "product_id": "{{ request()->segment(4) }}",
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                document.getElementById("uploaderFile" + id).innerHTML = response.html;
                            }
                        });
                    } else if (response.errors) {
                        // Show validation errors using Toastr
                        $.each(response.errors, function(key, value) {
                            toastr.error(value[0]); // Display the first error message
                        });
                        ui_multi_update_file_status(id, 'danger', 'Upload Failed');
                    }
                },
                onUploadError: function(id, xhr, status, message) {
                    console.log(message);
                    $("#uploaderFile" + id).remove();
                    toastr.error("An error occurred during the upload.");
                },
                onFileSizeError: function(file) {
                    toastr.error("File Size too Big");
                },
                onFileTypeError: function(file) {
                    toastr.error("Invalid File Type");
                },
                onFileExtError: function(file) {
                    toastr.error("Invalid File Extension");
                },
            });
        });


        $(document).on("click", ".btn-delete-product-img", function() {
            var b = $(this).attr("data-file-id");

            if (confirm('Are you sure you want to delete this image?')) {
                var a = {
                    "file_id": b,
                    "product_id": "{{ request()->segment(4) }}",
                    "_token": "{{ csrf_token() }}"
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('products.delete-product-images') }}",
                    data: a,
                    success: function() {
                        $("#uploaderFile" + b).remove();
                    },
                });
            } else {
                console.log('Deletion cancelled');
            }
        });

        $(document).on("click", ".btn-set-image-main", function() {
            var b = $(this).attr("data-file-id");
            var a = {
                "file_id": b,
                "product_id": "{{ request()->segment(4) }}",
                "_token": "{{ csrf_token() }}"
            };
            $(".badge-is-image-main").removeClass("btn-primary");
            $(".badge-is-image-main").addClass("btn-secondary");
            $(this).removeClass("btn-secondary");
            $(this).addClass("btn-primary");
            $.ajax({
                type: "POST",
                url: "{{ route('products.set-main-product-image') }}",
                data: a,
                success: function(c) {
                    document.getElementById("files-image").innerHTML = c.html;
                }
            });
        });
    </script>
@endsection