@extends('layouts.app')

@section('title','Order Details')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Order Details</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                </ol>
            </div>
            @if($order->order_status == 'delivered')
                <div class="col-md-4">
                    <div class="float-end d-none d-md-block">
                        <div class="dropdown">
                            @php $invoice_url = route('invoice',$order->order_number) @endphp
                            <a href="javascript:void(0)" onclick="javascript:popupCenter({url: '{{$invoice_url}}', title: 'Invoise', w: 1000, h: 600});" class="btn btn-info" aria-expanded="false">
                                <i class="mdi mdi-cloud-download me-2"></i> Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('order.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">Order Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Status</label>
                                    <div class="col-sm-4">
                                    @if($order->order_status == 'delivered')
                                        <label class="btn btn-success btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                    @elseif($order->order_status == 'cancelled')
                                        <label class="btn btn-danger btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                    @else
                                        <label class="btn btn-secondary btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                        @if($order->order_status != 'delivered' || $order->order_status != 'cancelled')
                                        @can('Order Edit')
                                        <a href="#" class="btn btn-primary" data-bs-placement="top"  title="Edit this Item" data-toggle="modal" data-target="#updateStatusModal_<?= $order->id; ?>"><i class="fa fa-edit option-icon"></i>Update order Status</a>
                                        @endcan
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Number</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->order_number }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Method</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">
                                            {{ ucfirst($order->payment_method) }}
                                        </strong>
                                    </div>
                                </div>
                                {{-- <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Currency</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->price_currency }}</strong>
                                    </div>
                                </div> --}}
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Status</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ ucfirst($order->payment_status) }}</strong><br>
                                        @if($order->payment_status == 'pending' && $order->order_status != 'cancelled' && $order->order_status != 'cancelled' && $order->payment_method == 'COD')
                                        @can('Order Edit')
                                        <a href="#" class="btn btn-primary" data-bs-placement="top"  title="Edit this Item" data-toggle="modal" data-target="#updatePaymentStatusModal_<?= $order->id; ?>"><i class="fa fa-edit option-icon"></i>Update Payment Status</a>
                                        @endcan
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Transaction ID</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->transaction_id ? $order->transaction_id : '' }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Date</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->payment_date ? format_datetime($order->payment_date) : '' }}</strong>
                                    </div>
                                </div>
                                {{-- <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Note</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $address_book->addl_info }}</strong>
                                    </div>
                                </div> --}}
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">User Name</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->name ?? ""}}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Phone Number</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->phone ?? '' }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->email ?? "" }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Billing Address</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">
                                            <b><?= $address_book->billing_first_name.' '.$address_book->billing_last_name ?></b><br>
                                            <b>Address : <?= $address_book->billing_address ?></b><br>
                                            <b>Country : <?= $address_book->country->name ?>, State : <?= $address_book->state->name ?>, City : <?= $address_book->city->name ?>, Pin : <?= $address_book->billing_zip_code ?></b><br>
                                            <b>Landmark : <?= $address_book->billing_landmark ?></b><br>
                                            <b>Phone : <?= $address_book->billing_phone_number ?></b><br>
                                            <b>Email : <?= $address_book->billing_email ?></b><br>
                                        </strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Shipping Address</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">
                                            {{-- <b><?= $address_book->shipping_first_name.' '.$address_book->shipping_last_name ?></b><br>
                                            <b>Address : <?= $address_book->shipping_address ?></b><br>
                                            <b>Country : <?= $address_book->country->name ?>, State : <?= $address_book->state->name ?>, City : <?= $address_book->city->name ?>, Pin : <?= $address_book->billing_zip_code ?></b><br>
                                            <b>Landmark : <?= $address_book->shipping_landmark ?></b><br>
                                            <b>Phone : <?= $address_book->shipping_phone_number ?></b><br>
                                            <b>Email : <?= $address_book->shipping_email ?></b><br> --}}
                                            Same as Billing Address
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">Order Items</div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th>Product Id</th> --}}
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Gst</th>
                                    <th>Total</th>
                                    <!-- <th class="max-width-120">Options</th> -->
                                </tr>
                            </thead>

                            <tbody>
                                @php $subtotal = 0; @endphp
                                @php $gst = 0; @endphp
                                @php $shipping = 0; @endphp
                                @foreach ($order_items as $item)
                                <tr>
                                    {{-- <td>{{ $item->product_id }}</td> --}}
                                    <td>
                                        <a href="{{ route('product.details',$item->product->slug) }}" target="_blank">
                                            <img src="{{ getProductMainImage($item->product->id) }}" data-src="" alt="" class="lazyload img-responsive post-image" style="width:80px;" />
                                        </a>
                                        {{ $item->product_name }}
                                    </td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ 0.00 }}</td>
                                    @php $subtotal += $item->subtotal @endphp
                                    <td>{{ $item->subtotal }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-4">
                            <div class="col-lg-4">
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">Subtotal</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $subtotal }}</strong></div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">GST</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $gst }}</strong></div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">Shipping</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $shipping }}</strong></div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">Coupon Discount</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $order->coupone_discount }} @if($order->coupone_discount > 0) ( Coupon Code - {{ $order->coupone_code }} ) @endif</strong></div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">Total Discount</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $order->discounted_price }}</strong></div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label float-end">Total</label>
                                    <div class="col-sm-8"><strong class="float-end">{{ $order->total_amount }}</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateStatusModal_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('order.update-order-status') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Update Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-order-status">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="order_status" class="form-control" aria-label="Default select example" id="orderOption" onchange="toggleFields()">
                                <option value="pending" <?php echo ($order->order_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo ($order->order_status == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="processing" <?php echo ($order->order_status == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo ($order->order_status == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo ($order->order_status == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo ($order->order_status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <div id="cancellCauseField" class="d-none mt-3">
                                <label class="control-label" for="trackingNumber">Explain the reasons for the cancellation</label>
                                <textarea type="text" class="form-control mb-3" id="trackingNumber" name="cancel_cause"></textarea>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updatePaymentStatusModal_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('order.update-payment-status') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Update Payment Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-order-status">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="order_status" class="form-control" aria-label="Default select example" id="shippingOption" onchange="toggleFields()">
                                <option value="pending" <?php echo ($order->payment_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="success" <?php echo ($order->payment_status == 'success') ? 'selected' : ''; ?>>Success</option>
                            </select>
                        </div>
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
	const popupCenter = ({url, title, w, h}) => {
        // Fixes dual-screen position                             Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title, 
        `
        scrollbars=yes,
        width=${w / systemZoom}, 
        height=${h / systemZoom}, 
        top=${top}, 
        left=${left}
        `
        )
        if (window.focus) newWindow.focus();
        newWindow.print();
    }
</script>
@endsection