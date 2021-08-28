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
    .col-form-label {
        font-size: 18px;
    }
    .radio{
        margin: 10px!important;
        transform: scale(2)!important;
    }
    .card-header {
        background-color: rgb(247 247 247)!important;
    }
    .card{
        background-color: transparent!important;
        box-shadow: none!important;
    }
    .sect{
        background: white!important;
        padding: 15px!important;
        border-radius: 15px!important;
    }

    @media only screen and (max-width: 1300px) {
        .no-padding {
            padding: 0rem!important;
        }
        .embed-responsive {
            height: 550px!important;
        }
    }
</style>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>STEP 2. MAKE PAYMENT</h1>
                    <!-- <h5>Tick the appropriate box</h5> -->
                </div>
                <div class="card-body no-padding">
                    <!-- <form action="{{ Config::get('app.pg_proc_url') }}" name="frmSubmit" id="frmSubmit" method="post" target="payFormIframe">
                        <input type="hidden" name="PAY_REQUEST_ID" value="{{ $PAY_REQUEST_ID }}" />
                        <input type="hidden" name="CHECKSUM" value="{{ $CHECKSUM }}" />
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4"></div>
                        </div>
                    </form>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" name="payFormIframe" id="payFormIframe" scrollbar="auto" allowfullscreen></iframe>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
window.payfast_do_onsite_payment({"uuid":"{{ $PAY_REQUEST_ID }}"});

// window.onload = function(){
//   document.forms['frmSubmit'].submit();
// }
</script>
@endsection
