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
                    <h1>Submitted successfully!</h1>
                    @if(Session::get('suc'))
                        <h5>{{Session::get('suc')}} <br> 
                        <b>*Note: Make sure to check your Spam box just in case you do not see it.</b>
                        </h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
