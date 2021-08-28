@extends('layouts.app')


@section('css')
<style>
    @media (min-width: 1200px){
        .navbar, .page-footer, main {
            padding-left:0px!important;
        }
    }
    .note{
        color:red;
        padding:15px;
    }
    .req{
        color:red; 
    }
    .grey.lighten-3 {
        background-image: url(" {{ asset('img/bg-logo.png') }} ")!important;
    }
    .login-a{
        color:black;
        font-weight:500;
        font-size:16px;
    }
    h1 {
        font-size: 2.3rem!important;
        /* text-align:center!important; */
    }
</style>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>Thank you</h1>
                    <h5>Thank you for transacting with us. If your payment was successful, You will be able to see a new order on <b><a href="{{ route('index') }}" target="_top">My Orders</a></b> list</b>. Click on <b>Distribute</b> to send the  hardwires assessments to the people you ordered for.</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
