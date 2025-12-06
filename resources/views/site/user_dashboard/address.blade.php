@extends('site.user_dashboard.dashboard')

@section('tab-title') Address @endsection

@section('tab-pane-content')
    <!-- Address Section -->
    <div class="tab-pane fade show active">
        <div class="card">
            <div class="card-header text-white" style="background-color: rgb(0, 0, 0);">Address</div>
            <div class="card-body">
                <div class="row">
                    @if (!empty($addresses))
                        @foreach ($addresses as $addr)
                            {!! getAddressById($addr->id) !!}
                        @endforeach
                    @endif
                </div>

                <div class="accordion add-address mt-3" id="address1">
                    <button class="collapsed btn rounded sjkgjhgk" type="button" onclick="$('#billing').toggle(400);">Add New Address</button>
                    <div id="billing" class="card mb-4 mt-5 accordion-collapse collapse" data-bs-parent="#address" style="display: none;">
                        <form action="{{ route('user-dashboard.address.save') }}" method="post">
                            @csrf
                            <div class="">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Add New Address</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input name="first_name" placeholder="First name" value="{{ old('first_name') }}" class="form-control" type="text" required>
                                        </div>
                                        
                                        <div class="form-group col-md-6">								
                                            <input name="last_name" placeholder="Last name" class="form-control" value="{{ old('last_name') }}" type="text" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input name="email" placeholder="Email address" class="form-control" value="{{ old('email') }}" type="email" required>
                                        </div>
                                
                                        <div class="form-group col-md-6">
                                            <input name="phone" placeholder="Phone number" class="form-control" value="{{ old('phone') }}" type="tel" required>
                                        </div>
                                    </div>
                                        
                                    <div class="form-row">
                                        <div class="form-group col-md-4">  
                                            <label for="country">Country:</label>
                                            <div class="custom-select-wrapper">
                                                <select id="country_id" name="country" required class="custom-select">
                                                    <option value="" selected disabled>Choose...</option>
                                                    @foreach($countrys as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">  
                                            <label for="states_id">State:</label>
                                            <div class="custom-select-wrapper">
                                                <select id="states_id" name="state" required class="custom-select" required>
                                                    <option value="">Select Country</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">  
                                            <label for="citys_id">City:</label>
                                            <div class="custom-select-wrapper">
                                                <select id="citys_id" name="city" required class="custom-select" required>
                                                    <option value="">Select State</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <label for="address">Address:</label>    
                                        <textarea rows="3" name="address" id="address" placeholder="Street address. Apartment, suite, unit etc. (optional)" class="form-control">{{ old('address') }}</textarea>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input name="pincode" placeholder="Post code / Zip" value="{{ old('pincode') }}" class="form-control" type="text">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- <hr class="my-4"> -->
                            <div class="text-center mb-3">
                                <button type="submit" class="btn btn-info"> <span>Add Address</span> </button>
                            </div>
                            <!-- Shipping Address -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
