<div class="card">
    <div class="card-header text-white text-center" style="background-color: rgb(0, 0, 0);">
        {{ auth()->user()->name }}
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item @if(request()->segment(2) == 'profile') active @endif">
            <a href="{{ route('user-dashboard.profile') }}">
                Profile
            </a>
        </li>
        <li class="list-group-item @if(request()->segment(2) == 'orders') active @endif">
            <a href="{{ route('user-dashboard.orders') }}">
                Orders
            </a>
        </li>
        <li class="list-group-item @if(request()->segment(2) == 'address') active @endif">
            <a href="{{ route('user-dashboard.address') }}">
                Address
            </a>
        </li>
    </ul>
</div>