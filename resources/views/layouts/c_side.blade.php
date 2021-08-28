<div class="sidebar-fixed position-fixed">

    <a class="logo-wrapper waves-effect">
        <img src="{{ asset('img/hw-logo.png') }}" class="img-fluid" alt="">
    </a>

    <div class="list-group list-group-flush">
    <a href="{{ route('index') }}" class="list-group-item {{ $active[0] }} waves-effect">
        <i class="fas fa-shopping-cart mr-3"></i>My Orders</a>

    <a href="{{ route('adm_account') }}" class="list-group-item {{ $active[1] ?? '' }} list-group-item-action waves-effect">
        <i class="fas fa-cogs mr-3"></i>Security</a>

</div>