@extends('site.user_dashboard.dashboard')

@section('tab-title') Edit Address @endsection

@section('tab-pane-content')
    <!-- Address Section -->
    <div class="tab-pane fade show active">
        <div class="card">
            <div class="card-header text-white" style="background-color: rgb(0, 0, 0);">Edit Address</div>
            <div class="card-body">
                <form action="{{ route('user-dashboard.address.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input name="first_name" placeholder="First name" value="{{ $address->billing_first_name }}" class="form-control" type="text" required>
                        </div>
                        
                        <div class="form-group col-md-6">								
                            <input name="last_name" placeholder="Last name" class="form-control" value="{{ $address->billing_last_name }}" type="text" required>
                        </div>
                    </div>
                        
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input name="email" placeholder="Email address" class="form-control" value="{{ $address->billing_email }}" type="email" required>
                        </div>
                
                        <div class="form-group col-md-6">
                            <input name="phone" placeholder="Phone number" class="form-control" value="{{ $address->billing_phone_number }}" type="tel" required>
                        </div>
                    </div>
                            
                    <div class="form-row">
                        <div class="form-group col-md-4">  
                            <label for="country">Country:</label>
                            <div class="custom-select-wrapper">
                                <select id="country_id" name="country" required class="custom-select">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach($countrys as $country)
                                    <option value="{{ $country->id }}" @if($address->billing_country == $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">  
                            <label for="states_id">State:</label>
                            <div class="custom-select-wrapper">
                                <select id="states_id" name="state" required class="custom-select" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                    <option value="{{ $state->id }}" @if($address->billing_state == $state->id) selected @endif>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">  
                            <label for="citys_id">City:</label>
                            <div class="custom-select-wrapper">
                                <select id="citys_id" name="city" required class="custom-select" required>
                                    <option value="">Select City</option>
                                    @foreach($citys as $city)
                                    <option value="{{ $city->id }}" @if($address->billing_city == $city->id) selected @endif>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                        
                        
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="address">Address:</label>    
                            <textarea rows="3" name="address" id="address" placeholder="Street address. Apartment, suite, unit etc. (optional)" class="form-control">{{ $address->billing_address }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">Pin Code:</label>    
                            <input name="pincode" placeholder="Post code / Zip" value="{{ $address->billing_zip_code }}" class="form-control" type="text">
                        </div>
                    </div>

                    <!-- <hr class="my-4"> -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-info"> <span>Update Address</span> </button>
                    </div>
                    <!-- Shipping Address -->
                </form>
            </div>
        </div>
    </div>
@endsection
