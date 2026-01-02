@extends('layouts.app')

@section('title','Products')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Products</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Product Variation</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('product.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>


<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        @include('admin.products.nav-tabs-edit')
                        <!-- Tab panes -->

                        <div class="tab-content">
                            <div class="tab-pane active p-3" id="pricedetails" role="tabpanel">
                                <div class="tab-pane p-3" id="variations" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="copyVariationSelect">Use Existing Variation:</label>
                                        <select id="copyVariationSelect" class="form-control">
                                            <option value="">-- Select Existing Variation --</option>
                                            @foreach(\App\Models\ProductVariation::with('product')->get() as $existing)
                                                <option value="{{ $existing->id }}">
                                                    {{ $existing->name }} (from {{ $existing->product->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button id="copyVariationBtn" class="btn btn-secondary mt-2">Apply to Current Product</button>
                                    </div>

                                    <button id="createVariationBtn" class="btn btn-primary">Create Variation</button>

                                    <!-- Variation Form (Initially Hidden) -->
                                    <div id="variationForm" style="display: none; margin-top: 20px;">
                                        <form id="addVariationForm">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <label>Variation Name:</label>
                                            <input type="text" name="name" required class="form-control">
                                            <button type="submit" class="btn btn-success mt-2">Save Variation</button>
                                        </form>
                                    </div>

                                    <!-- Table for Existing Variations -->
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Variation Name</th>
                                                <th>Options</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variations as $variation)
                                            <tr>
                                                {{-- <td>{{ $variation->name }}</td> --}}
                                                <td>
                                                    <input type="text"
                                                        class="form-control variation-name"
                                                        data-id="{{ $variation->id }}"
                                                        value="{{ $variation->name }}">
                                                    <button class="btn btn-sm btn-primary saveVariationBtn"
                                                            data-id="{{ $variation->id }}">
                                                        Save
                                                    </button>
                                                </td>
                                                <td>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Type</th>
                                                                <th>Value</th>
                                                                <th>Name</th>
                                                                <th>Price</th>
                                                                <th>Stock</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($variation->options as $option)
                                                            <tr>
                                                                <td>{{ ucfirst($option->variation_type) }}</td>
                                                                <td>
                                                                    @if($option->variation_type == 'color')
                                                                        <div style="width: 30px; height: 30px; background: {{ $option->value }}; border: 1px solid #000;"></div>
                                                                    @elseif($option->variation_type == 'image')
                                                                        <img src="{{ asset('storage/'.$option->value) }}" alt="Image" width="50">
                                                                    @else
                                                                        {{ $option->value }}
                                                                    @endif
                                                                </td>
                                                                {{-- <td>{{ $option->variation_name }}</td> --}}
                                                                <td>
                                                                    <input type="text" class="form-control option-name"
                                                                        data-id="{{ $option->id }}"
                                                                        value="{{ $option->variation_name }}">
                                                                </td>
                                                                {{-- <td>{{ $option->price }}</td> --}}
                                                                <td>
                                                                    <input type="number" class="form-control option-price"
                                                                        data-id="{{ $option->id }}"
                                                                        value="{{ $option->price }}">
                                                                </td>
                                                                {{-- <td>{{ $option->stock }}</td> --}}
                                                                <td>
                                                                    <input type="number" class="form-control option-stock"
                                                                        data-id="{{ $option->id }}"
                                                                        value="{{ $option->stock }}">
                                                                </td>
                                                                <td>
                                                                    <form action="{{ route('products.destroyVariationOption', $option->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit" class="btn">
                                                                            <i class="fa fa-trash-o text-danger"></i>
                                                                        </button>
                                                                    </form>
                                                                    <button class="btn btn-sm btn-success saveOptionBtn"
                                                                            data-id="{{ $option->id }}">
                                                                        Save
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-success addOptionBtn" data-id="{{ $variation->id }}">Add Option</button>
                                                    <form action="{{ route('products.destroyVariation', $variation->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit" class="btn">
                                                            <i class="fa fa-trash-o text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    

                                    <!-- Variation Option Form (Initially Hidden) -->
                                    <div id="optionForm" style="display:none;">
                                        <h4>Add Option</h4>
                                        <form id="addOptionForm">
                                            @csrf
                                            <input type="hidden" name="variation_id" id="variation_id">
                                            
                                            <label>Option Type</label>
                                            <select name="type" id="optionType" class="form-control mb-3">
                                                <option value="" selected disabled>Choose</option>
                                                <option value="color">Color</option>
                                                <option value="image">Image</option>
                                                <option value="text">Text</option>
                                            </select>
                                            
                                            <div id="optionValueField" class="mb-3">
                                                <label>Option Value</label>
                                                <input type="text" name="value" id="optionValue" class="form-control">
                                            </div>
                                    
                                            <label>Option Name</label>
                                            <input type="text" name="variation_name" class="form-control mb-3">

                                            <label>Price</label>
                                            <input type="number" name="price" class="form-control mb-3">
                                            
                                            <label>Stock</label>
                                            <input type="number" name="stock" class="form-control mb-3">
                                    
                                            <button type="submit" class="btn btn-primary mt-2">Save Option</button>
                                        </form>
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
                                <form action="{{ route('products.inventory-edit',request()->segment(4)) }}" method="get">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ request()->segment(4) }}">
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    Save & Next
                                </button>
                                </form>
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
@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Show/hide variation form
        $("#createVariationBtn").click(function () {
            $("#variationForm").slideToggle();
        });

        // Handle variation submission
        $("#addVariationForm").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('products.variation.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        $("#variationsTable").append(`<tr>
                            <td>${response.variation.name}</td>
                            <td><ul></ul></td>
                            <td><button class="btn btn-sm btn-info addOptionBtn" data-id="${response.variation.id}">Add Option</button></td>
                        </tr>`);
                        $("#variationForm").slideUp();
                        $("#addVariationForm")[0].reset();
                        location.reload();
                    }
                }
            });
        });

        // Show variation option form
        $(".addOptionBtn").click(function () {
            let variationId = $(this).data("id");
            $("#variation_id").val(variationId);
            $("#optionForm").slideDown();
        });

        // Handle option type change
        $("#optionType").change(function () {
            let type = $(this).val();
            if (type === "color") {
                $("#optionValueField").html('<input type="color" name="value" class="form-control">');
            } else if (type === "image") {
                $("#optionValueField").html('<input type="file" name="value" class="form-control">');
            } else {
                $("#optionValueField").html('<input type="text" name="value" class="form-control">');
            }
        });

        // Handle option submission
        $("#addOptionForm").submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('products.variation-option.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        showToast('success', 'Success', 'Option added successfully!');

                        location.reload();
                        // // Find the correct table row and tbody to append the new row
                        // let variationRow = $(`button.addOptionBtn[data-id="${response.option.variation_id}"]`).closest("tr");
                        // let optionTable = variationRow.find("td:nth-child(2)").find("table tbody"); // Ensure correct tbody is selected

                        // let valueHtml = '';
                        // if (response.option.type.toLowerCase() === "color") {
                        //     valueHtml = `<div style="width: 30px; height: 30px; background: ${response.option.value}; border: 1px solid #000;"></div>`;
                        // } else if (response.option.type.toLowerCase() === "image") {
                        //     valueHtml = `<img src="${response.option.value}" width="50">`;
                        // } else {
                        //     valueHtml = response.option.value;
                        // }

                        // let newRow = `
                        //     <tr>
                        //         <td>${response.option.type}</td>
                        //         <td>${valueHtml}</td>
                        //         <td>${response.option.price}</td>
                        //         <td>${response.option.stock}</td>
                        //         <td>
                        //             <button class="btn btn-sm btn-primary editOptionBtn" data-id="${response.option.id}">Edit</button>
                        //             <button class="btn btn-sm btn-danger deleteOptionBtn" data-id="${response.option.id}">Delete</button>
                        //         </td>
                        //     </tr>`;

                        // optionTable.append(newRow);  // Append the new row

                        // Hide form and reset it
                        $("#optionForm").slideUp();
                        $("#addOptionForm")[0].reset();
                        location.reload();
                    }
                }
            });

        });

        $('.saveVariationBtn').click(function () {
            let id = $(this).data('id');
            let name = $(this).closest('tr').find('.variation-name').val();
            let updateVariationUrl = "{{ route('products.variation.update', ':id') }}";
            let url = updateVariationUrl.replace(':id', id);

            $.post(url, {
                _token: "{{ csrf_token() }}",
                name: name
            }, function (res) {
                if (res.success) {
                    showToast('success', 'Updated', res.message);
                }
            });
        });



        $('.saveOptionBtn').click(function () {
            let id = $(this).data('id');
            let row = $(this).closest('tr');

            let updateVariationOptionUrl = "{{ route('products.variation-option.update', ':id') }}";
            let urll = updateVariationOptionUrl.replace(':id', id);

            $.post(urll, {
                _token: "{{ csrf_token() }}",
                variation_name: row.find('.option-name').val(),
                price: row.find('.option-price').val(),
                stock: row.find('.option-stock').val()
            }, function (res) {
                if (res.success) {
                    showToast('success', 'Updated', res.message);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#copyVariationBtn').on('click', function() {
            let variationId = $('#copyVariationSelect').val();
            let productId = "{{ $product->id }}";

            if (!variationId) {
                alert('Please select a variation to copy.');
                return;
            }

            $.ajax({
                url: "{{ route('products.copyVariation') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    variation_id: variationId,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // refresh to show copied variation
                    }
                },
                error: function(xhr) {
                    alert('Error copying variation');
                }
            });
        });
    });

    $('#copyVariationSelect').select2({
        placeholder: 'Select existing variation',
        width: '100%'
    });

</script>

@endsection