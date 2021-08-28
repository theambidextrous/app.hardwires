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
</style>
@endsection


@section('content')
<a class="login-a" href="{{ route('login') }}" target="_blank">Login</a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>STEP 1. SIGN UP </h1>
                    <h3><b>HARDWIRES Assessment: Personal Information</b></h3>
                    <hr>
                    <p>YOUR HARDWIRES 4 STEP PROCESS :</p>
                    
                    a) Read introduction
                    <br><br>
                    b) Fill in your email address correctly, your name and surname as well as your contact number in the required fields.
                    <br><br>
                    c) Complete each questionnaire truthfully. PLEASE find a quiet, peaceful spot, rather than a rushed work environment.
                    <br><br>
                    d.) Click on Submit button. A confirmation notification will appear once you have submitted your questionnaire. Please ensure that you receive this confirmation on submission of each questionnaire.
                    <br><br>
                    INTRODUCTION:
                    <br>
                    a.) PREAMBLE
                    <br>
                    There are 16 nasty HARDWIRES that, from early childhood days, could develop in your brain, without you even being aware of it happening. During every second that you are in a state of fear or experience a sense of, “I am not ok”, this hard wiring happens. Through advances in Neuro- behavioral Science, it has been proved that every single incident where you were handled unlovingly, (felt neglected, ignored, embarrassed, abused, harassed, scolded or where irritation and annoyance were shown towards you,) altered your brain. It caused an unhealthy distortion of your impulse control, your natural feelings, affections, inclinations, temper, habits and moral tendencies and affected the soundness of your mind.
                    <br><br>
                    b.) HONESTY
                    <br>
                    We know it is uncomfortable to make yourself vulnerable and to be totally honest and truthful about yourself!  Please know that even the slightest untruth will discredit the process… this is only for yourself! 
                    <br><br>
                    c.) CONFIDENTIALITY
                    <br>
                    The information will be kept strictly confidential by the A2B Transformation Movement in compliance with the ethical guidelines of Health Care Professionals.
                    <br><br>
                    d.) REPORT:
                    <br>
                    Your infographic graph will be sent to you within 24 hours of submission of all 16 completed HARDWIRES questionnaires. Should you not receive your graph within the specified timeframe, please email discover@hardwires.co.za immediately. On receipt of your graph, please book your free telephonic coaching session by following the guidelines accompanying your graph.
                    <br><br>
                    e. ) COPYRIGHT  
                    <br>
                    Duplication, replication and distribution of these questionnaires, or part thereof, is not permissible. It is the proprietary information of A2B Transformation Movement. All Rights Reserved.  
                    <br><br>
                    <p class="note">* Required</p>
                </div>
                <div class="card-body">
                    @if(Session::get('suc'))
                        <div class="alert alert-success">{{Session::get('suc')}}</div>
                    @endif
                    @if(Session::get('mess'))
                        <div class="alert alert-danger">{{Session::get('mess')}}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- SECTIONS -->
                    <div id="sectionUserForm" style="display:block;">
                        <form method="POST" name="initcustfrm" action="{{ route('init_customer') }}">
                            {{ csrf_field() }}
                            <div class="alert alert-danger" id="frm_err" style="display:none;"></div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email <span class="req">*</span></label>
                                <div class="col-md-6">
                                    <input type="hidden" value="{{ Config::get('app.series') }}" name="series"/>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Name and Surname <span class="req">*</span></label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required value="{{ old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Cell number<span class="req">*</span></label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Cell number of the person who referred you (if applicable) </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('referral_cell') is-invalid @enderror" name="referral_cell" >

                                    @error('referral_cell')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="button" onclick="initCustomer('initcustfrm')" class="btn btn-primary">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- code section -->
                    <div id="codeSectionForm" style="display:none;">
                        <form method="POST" name="emailverifyfrm" action="{{ route('verifyEmail') }}">
                            {{ csrf_field() }}
                            <div class="alert alert-danger" id="code_err" style="display:none;"></div>
                            <div class="alert alert-success" id="code_suc" style="display:none;"></div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-center">Verification Code <span class="req">*</span></label>
                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autocomplete="code" autofocus>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <p> Did not receive code? <a href="{{ route('welcome') }}"><b>Resend code</b></a> </p>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="button" onclick="verifyEmail('emailverifyfrm')" class="btn btn-primary">
                                        Verify
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
