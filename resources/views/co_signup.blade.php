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
<a class="login-a" href="{{ route('login') }}" target="_blank">Corporate/NGO Login</a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>Corporate/NGO Sign Up</h1>
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
                    <form method="POST" action="{{ route('new_corporate') }}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Office Email(<small>Used as login email</small>) <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="hidden" value="{{ Config::get('app.series') }}" name="series"/>
                                <input type="hidden" name="is_member" value="NO"/>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Registered Name for the Corporate/NGO <span class="req">*</span></label>
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
                            <label for="password" class="col-md-4 col-form-label text-md-right">Registered Office Address <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" required value="{{ old('address') }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Office Cell number<span class="req">*</span></label>
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
                            <label for="password" class="col-md-4 col-form-label text-md-right">Contact Person Full Name <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" >

                                @error('contact_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Contact Person Cell Number <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" name="contact_phone" >

                                @error('contact_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Member of A2B Programme? <span class="req">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control @error('is_member') is-invalid @enderror" name="is_member" >
                                    <option value="nn">Choose one</option>
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                                @error('is_member')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Set a Password <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Re-enter Password <span class="req">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control @error('c_password') is-invalid @enderror" name="c_password" >
                                @error('c_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Sign Up
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
