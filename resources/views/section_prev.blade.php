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
</style>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1>HARDWIRES Assessment: <b>{{ $sectionMeta->name }}</b></h1>
                    <h5>Tick the appropriate box</h5>
                    <p class="note">* Required</p>
                </div>
                <div class="card-body" style="background:transparent;">
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
                    @if(count($questions))
                    <form method="POST" action="{{ route('save_section') }}">
                        @csrf
                        <input type="hidden" value="{{ $customerHash }}" name="hash"/>
                        <input type="hidden" value="{{ $sectionMeta->id }}" name="section"/>

                        @foreach( $questions as $_question )
                        <div class="form-group row sect">
                            <label class="col-md-12 col-form-label"> {{ $_question['number'] }}.) {{ $_question['name'] }}<span class="req">*</span></label>
                            <div class="col-md-12">
                                @if(count($_question['options']))
                                @foreach($_question['options'] as $opt )
                                <br>
                                <div class="form-check form-check-inline">
                                    <input
                                        required
                                        class="form-check-input radio"
                                        type="radio"
                                        name="userOpt__{{ $_question['id'] }}"
                                        id="inlineRadio{{ $opt['id'] }}"
                                        value="{{ $opt['id'] }}"
                                    />
                                    <label class="form-check-label" for="inlineRadio{{ $opt['id'] }}">{{ $opt['option'] }}</label>
                                </div>
                                @endforeach
                                @else
                                <h6>No options</h6>
                                @endif
                            </div>
                        </div>
                        <!-- <br> -->
                        @endforeach
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button disabled type="submit" class="btn btn-primary">
                                    Submit Questionnaire
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <h3>Oops!... No questions found</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
