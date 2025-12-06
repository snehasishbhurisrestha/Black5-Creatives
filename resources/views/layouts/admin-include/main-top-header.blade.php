<div id="header_top" class="header_top">
    <div class="container">
        <div class="hleft">
            <a class="header-brand" href="{{ route('dashboard') }}"><i class="fa fa-user-circle-o brand-logo"></i></a>
            <div class="dropdown">
                <a href="javascript:void(0)" class="nav-link icon menu_toggle"><i class="fe fe-align-center"></i></a>
                <a href="{{ route('dashboard') }}" class="nav-link icon"><i class="fa fa-dashboard" data-toggle="tooltip" data-placement="right" title="Dashboard"></i></a>

                @can('Order Show')
                <a href="{{ route('order.index') }}"  class="nav-link icon app_inbox"><i class="fa fa-shopping-cart" data-toggle="tooltip" data-placement="right" title="Orders"></i></a>
                @endcan
                @can('Role Show')
                <a href="{{ route('roles') }}"  class="nav-link icon app_inbox"><i class="fa fa-key" data-toggle="tooltip" data-placement="right" title="Roles"></i></a>
                @endcan
                @can('Permission Show')
                <a href="{{ route('permission') }}"  class="nav-link icon app_inbox"><i class="fa fa-lock" data-toggle="tooltip" data-placement="right" title="Permission"></i></a>
                @endcan
                {{-- <a href="javascript:void(0)" class="nav-link icon theme_btn"><i class="fe fe-feather"></i></a> --}}
                <a href="javascript:void(0)" class="nav-link icon settingbar"><i class="fe fe-settings"></i></a>
            </div>
        </div>
        <div class="hright">
            {{-- <a href="javascript:void(0)" class="nav-link icon right_tab"><i class="fe fe-align-right"></i></a> --}}
            {{-- <a href="login.html" class="nav-link icon settingbar"><i class="fe fe-power"></i></a>   --}}
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a class="nav-link icon settingbar" href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fe fe-power"></i>
                </a>
            </form>               
        </div>
    </div>
</div>