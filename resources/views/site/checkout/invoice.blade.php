<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>

        /*--------------------------------------------------------------
        Styles
        --------------------------------------------------------------*/

        /* Fonts */
        :root {
        --body-font: 'Roboto', sans-serif;
        }

        /* Bootstrap Override */
        body {
        --bs-font-sans-serif: 'Roboto', sans-serif;
        --bs-body-font-family: var(--bs-font-sans-serif);
        --bs-body-font-size: 1rem;
        --bs-body-font-weight: 400;
        --bs-body-line-height: 2;
        --bs-body-color: #383735;
        --bs-primary: #ffc400;
        --bs-primary-rgb: 244, 187, 0;
        --bs-border-color: #eeeeee;
        }
        @media (min-width: 576px){
            .container, .container-sm {
                max-width: 890px !important;
            }
        }
        .text-primary {
            --bs-text-opacity: 1;
            color: rgb(188 27 54) !important;
        }
        .border-primary {
            --bs-border-opacity: 1;
            border-color: rgb(188 27 54) !important;
        }
    </style>
</head>

<body>
    <section id="invoice">
        <div class="container">

            <div class="text-center pb-5">
                <img src="{{ asset('assets/admin-assets/images/navbar-logo2.png') }}" alt="" style="height: 50px;">
                {{-- <h1>{{ config('app.name', 'Laravel') }}</h1> --}}
            </div>

            {{-- <div class="d-md-flex justify-content-between" style="display:flex">
                <div>
                    <p class="fw-bold text-primary">Invoice To</p>
                    <h4>{{ $address_book->billing_first_name . ' ' . $address_book->billing_last_name }}</h4>
                    <ul class="list-unstyled m-0">
                        <li>Phone : {{ $address_book->billing_phone_number }}</li>
                        <li>Address : {{ $address_book->billing_address }}</li>
                        <li>Country : {{ $address_book->country->name }}, 
                            State : {{ $address_book->state->name }}, 
                            City : {{ $address_book->city->name }}</li>
                        <li>Pin : {{ $address_book->billing_zip_code }}</li>
                        <li>Email : {{ $address_book->billing_email }}</li>
                    </ul>

                </div>
                <div class="mt-md-0">
                    <p class="fw-bold text-primary">Invoice From</p>
                    <h4>{{ config('app.name', 'Laravel') }}</h4>
                    <ul class="list-unstyled m-0">
                       <li>Phone : +91 8697310898</li>
                        <li>Address : Bally Ghoshpara, Paulpara, Near Level Crossing</li>
                        <li>Country : India, 
                            State : West Bengal, 
                            City : Howrah</li>
                        <li>Pin : 711227</li>
                        <li>Email : priyanka.id2018@gmail.com</li>
                    </ul>
                </div>
            </div> --}}

            <div class="d-md-flex justify-content-between gap-4">

                <!-- Invoice To -->
                <div class="mb-4 mb-md-0">
                    <p class="fw-bold text-primary mb-1">Invoice To</p>
                    <h4>{{ $address_book->billing_first_name }} {{ $address_book->billing_last_name }}</h4>

                    <p class="mb-0">
                        Phone: {{ $address_book->billing_phone_number }} <br>
                        {{ $address_book->billing_address }} <br>

                        {{ optional($address_book->city)->name }},
                        {{ optional($address_book->state)->name }},
                        {{ optional($address_book->country)->name }}<br>

                        Pin: {{ $address_book->billing_zip_code }} <br>
                        Email: {{ $address_book->billing_email }}
                    </p>
                </div>

                <!-- Invoice From -->
                <div>
                    <p class="fw-bold text-primary mb-1">Invoice From</p>
                    <h4>{{ config('app.name', 'Laravel') }}</h4>

                    <p class="mb-0">
                        Phone: +91 8697310898 <br>
                        Bally Ghoshpara, Paulpara, Near Level Crossing <br>
                        Howrah, West Bengal, India <br>
                        Pin: 711227 <br>
                        Email: priyanka.id2018@gmail.com
                    </p>
                </div>

            </div>


            <div class=" d-md-flex justify-content-between align-items-center border-top border-bottom border-primary my-2 py-3" style="display:flex;margin-bottom:0;">
                <h2 class="display-6 fw-bold m-0">Invoice</h2>
                <div>
                    <p class="m-0"> <span class="fw-medium">Invoice No:</span> {{ $order->order_number }}</p>
                    <p class="m-0"> <span class="fw-medium">Invoice Date:</span> {{ format_datetime($order->created_at) }}</p>
                </div>

            </div>

            <div class="py-1">
                <table class="table table-striped border my-2" style="margin-bottom: 0 !important;">
                    <thead>
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>₹ {{ $item->price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ $item->price * $item->quantity }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td colspan="2" class="text-primary fs-5 fw-bold">Shipping Charges</td>
                            <td class="text-primary fs-5 fw-bold">₹ {{ $order->price_shipping }}</td>
                        </tr>
                        {{-- @if($order->coupone_discount > 0)
                        <tr>
                            <td></td>
                            <td colspan="2" class="text-primary fs-5 fw-bold">Coupon Discount</td>
                            <td class="text-primary fs-5 fw-bold">₹ {{ $order->coupone_discount }}</td>
                        </tr>
                        @endif --}}
                        <tr>
                            <td></td>
                            <td colspan="2" class="text-primary fs-5 fw-bold">Discount</td>
                            <td class="text-primary fs-5 fw-bold">₹ {{ $order->discounted_price }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" class="text-primary fs-5 fw-bold">Grand Total</td>
                            <td class="text-primary fs-5 fw-bold">₹ {{ $order->total_amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="d-md-flex justify-content-between my-2" style="display:flex;">

                <div>
                <h5 class="fw-bold my-4">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="mdi:location"
                                style="vertical-align:text-bottom"></iconify-icon>Bally Ghoshpara, Paulpara, Near Level Crossing,<br> Howrah 711227, West Bengal</li>
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="solar:phone-bold"
                                style="vertical-align:text-bottom"></iconify-icon> +91 8697310898</li>
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="ic:baseline-email"
                                style="vertical-align:text-bottom"></iconify-icon> priyanka.id2018@gmail.com</li>
                    </ul>
                </div>

                <div style="text-align: center;margin: auto 0;">
                    <h3 class="fw-bold my-4">Authorised Signatory</h3>
                </div>


            </div>

            <!-- <div class="text-center my-5">
                <p class="text-muted"><span class="fw-semibold">NOTICE: </span> A finance charge of 1.5% will be made on
                    unpaid balances after 30 days.</p>
            </div> -->

            <div id="footer-bottom">
                <div class="container border-top border-primary">
                    <div class="row mt-3">
                        
                    </div>
                </div>
            </div>

        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

</body>

</html>
