@extends('site.user_dashboard.dashboard')

@section('tab-title') Order Details @endsection

@section('tab-pane-content')
    <!-- Orders Section -->
    <div class="tab-pane fade show active">
        <div class="card">
            <div class="card-header text-white" style="background-color: rgb(0, 0, 0);">Order Details</div>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <!-------------order------------->
                    <div id="orders" class="product-order">
                        <div class="order-details-container">
                            <div class="order-head d-flex justify-content-between text-center">
                                <h2 class="title">Order Number:&nbsp;#{{ $order->order_number }}</h2>
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
                            </div>
                            <div class="order-body">
                                <div class="row">
                                    <div class="col-12 card p-3 mb-4">
                                        <div class="row order-row-item">
                                            <div class="col-6">
                                                Status
                                            </div>
                                            <div class="col-6">
                                                <strong>{{ ucfirst($order->order_status) }}</strong>
                                            </div>
                                        </div>
                                        <div class="row order-row-item">
                                            <div class="col-6">
                                                Payment Method
                                            </div>
                                            <div class="col-6">
                                                {{ $order->payment_method }}
                                            </div>
                                        </div>
                                        <div class="row order-row-item">
                                            <div class="col-6">
                                                Payment Status
                                            </div>
                                            <div class="col-6">
                                                {{ ucfirst($order->payment_status) }}
                                            </div>
                                        </div>
                                        <div class="row order-row-item">
                                            <div class="col-6">
                                                Date
                                            </div>
                                            <div class="col-6">
                                                <?= format_datetime($order->created_at); ?>
                                            </div>
                                        </div>
                                        <div class="row order-row-item">
                                            <div class="col-6">
                                                Updated
                                            </div>
                                            <div class="col-6">
                                                <?= time_ago($order->updated_at); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row shipping-container">
                                    <div class="col-md-12 col-lg-6 m-b-sm-15">
                                        <h3 class="block-title">Shipping Address</h3>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                First Name
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_first_name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Last Name
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_last_name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Email
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_email; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Phone Number
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_phone_number; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Address
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_address; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Country
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->country->name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                State
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->state->name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                City
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->city->name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Pincode
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->shipping_zip_code; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <h3 class="block-title">Billing Address</h3>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                First Name
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_first_name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Last Name
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_last_name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Email
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_email; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Phone Number
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_phone_number; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Address
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_address; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Country
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->country->name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                State
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->state->name; ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                City
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->city->name ?>
                                            </div>
                                        </div>
                                        <div class="row shipping-row-item">
                                            <div class="col-5">
                                                Pincode
                                            </div>
                                            <div class="col-7">
                                                <?= $address_book->billing_zip_code; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php $is_order_has_physical_product = false; ?>
                                <div class="row table-orders-container">
                                    <div class="col-6 col-table-orders">
                                        <h3 class="block-title">Products</h3>
                                    </div>
                                    <div class="col-12 card p-3 mb-4">
                                        <div class="table-responsive">
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
                    <!-------------address------------>
                </div>
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
