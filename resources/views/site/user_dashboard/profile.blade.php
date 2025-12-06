@extends('site.user_dashboard.dashboard')

@section('tab-title') Profile @endsection

@section('tab-pane-content')
    <!-- Profile Section -->
    <div class="tab-pane fade show active">
        <div class="card">
            <div class="card-header text-white" style="background-color: rgb(0, 0, 0);">Profile</div>
            <div class="card-body">
                <form method="post" action="{{ route('user-dashboard.profile.update') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>    
                        <textarea rows="3" name="address" id="address" placeholder="Street address. Apartment, suite, unit etc. (optional)" name="address" class="form-control">{{ Auth::user()->address }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection
