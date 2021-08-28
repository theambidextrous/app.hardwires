<div class="sidebar-fixed position-fixed">

    <a class="logo-wrapper waves-effect">
        <img src="{{ asset('img/hw-logo.png') }}" class="img-fluid" alt="">
    </a>

    <div class="list-group list-group-flush">
    <a href="{{ route('index') }}" class="list-group-item {{ $active[0] }} waves-effect">
        <i class="fas fa-chart-pie mr-3"></i>Dashboard</a>
    
    <a href="{{ route('series') }}" class="list-group-item {{ $active[1] }} list-group-item-action waves-effect">
        <i class="fas fa-table mr-3"></i>Questionnaires</a>
    
    <a href="{{ route('responses') }}" class="list-group-item {{ $active[2] }} list-group-item-action waves-effect">
        <i class="fas fa-map mr-3"></i>Responses</a>
    
    <a href="{{ route('spec_orders') }}" class="list-group-item {{ $active[3] }} list-group-item-action waves-effect">
        <i class="fas fa-shopping-cart mr-3"></i>Special Orders</a>
    
    <a href="{{ route('adm_pricing') }}" class="list-group-item {{ $active[4] ?? '' }} list-group-item-action waves-effect">
        <i class="fas fa-bars mr-3"></i>Pricing</a>
    
    <a href="{{ route('adm_ngo') }}" class="list-group-item {{ $active[5] ?? '' }} list-group-item-action waves-effect">
        <i class="fas fa-users mr-3"></i>NGOs/Corporates</a>

    <a href="{{ route('adm_account') }}" class="list-group-item {{ $active[6] ?? '' }} list-group-item-action waves-effect">
        <i class="fas fa-cogs mr-3"></i>Security</a>

</div>