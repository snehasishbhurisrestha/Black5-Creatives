@extends('layouts.app')

@section('title','Create Platinum Card Offer')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Platinum Card Offer</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category_free_offer.index') }}">Platinum Card Offer</a></li>
                    <li class="breadcrumb-item active">Create Platinum Card Offer</li>
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

<form action="{{ route('category_free_offer.store') }}" method="POST">
    @csrf

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
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- REQUIRED PURCHASE --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Minimum Purchase Count <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control" name="required_qty" required placeholder="Example: 10">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Free Product Count <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control" name="free_product_qty" required placeholder="Example: 2">
                            </div>

                            {{-- FREE ITEMS BLOCK --}}
                            {{-- <div class="mb-3">
                                <label class="form-label fw-bold">Free Product Variations <span class="text-danger">*</span></label>

                                <div id="free-items-container">

                                    <!-- First Row -->
                                    <div class="free-item-row row mb-2">

                                        <div class="col-md-7">
                                            <select name="free_variations[0][variation_option_id]" class="form-control select2" required>
                                                <option value="">Select Variation</option>
                                                @foreach ($variationOptions as $opt)
                                                    <option value="{{ $opt->id }}">
                                                        {{ $opt->variation->product->name ?? 'Unknown Product' }}
                                                        — {{ $opt->variation_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="number" name="free_variations[0][free_qty]" min="1" value="1" class="form-control" required placeholder="Qty">
                                        </div>

                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-row w-100">X</button>
                                        </div>

                                    </div>

                                </div>

                                <button type="button" class="btn btn-info mt-2" id="add-free-item">
                                    + Add More Free Item
                                </button>
                            </div> --}}

                            {{-- DESCRIPTION --}}
                            {{-- <div class="mb-3">
                                <label class="form-label fw-bold">Offer Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div> --}}

                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="col-lg-4">

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
                                    <input type="radio" id="status1" name="is_active" value="1" class="form-check-input" checked>
                                    <label class="form-check-label" for="status1">Active</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status2" name="is_active" value="0" class="form-check-input">
                                    <label class="form-check-label" for="status2">Inactive</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 mb-2">Save Offer</button>
                                <button type="reset" class="btn btn-secondary w-100">Reset</button>
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
    let rowIndex = 1;

    $("#add-free-item").click(function () {
        let row = `
            <div class="free-item-row row mb-2">

                <div class="col-md-7">
                    <select name="free_variations[${rowIndex}][variation_option_id]" class="form-control select2" required>
                        <option value="">Select Variation</option>
                        @foreach ($variationOptions as $opt)
                            <option value="{{ $opt->id }}">
                                {{ $opt->variation->product->name ?? 'Unknown Product' }} — {{ $opt->variation_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="number" name="free_variations[${rowIndex}][free_qty]" min="1" value="1" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row w-100">X</button>
                </div>

            </div>
        `;

        $("#free-items-container").append(row);
        $(".select2").select2();

        rowIndex++;
    });

    // Remove Row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('.free-item-row').remove();
    });

    $(".select2").select2();
</script>
@endsection
