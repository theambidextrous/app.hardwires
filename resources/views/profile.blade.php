@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
  @if(Auth::user()->user_group == 1 )
        @include('layouts.side', [ 'active' => ['active', 0,0,0,0]])
    @else
        @include('layouts.c_side', [ 'active' => [0,0,'active',0,0]])
    @endif
@endsection

@section('content')
<div class="container-fluid mt-5">

      <!-- Heading -->
      <div class="card mb-4 wow fadeIn">

        <!--Card content-->
        <div class="card-body d-sm-flex justify-content-between">

          <h4 class="mb-2 mb-sm-0 pt-1">
            <a href="{{ route('index') }}" target="_blank">{{ Config::get('app.name') }}</a>
            <span>/</span>
            <span>My Account</span>
          </h4>
        </div>

      </div>
      <!-- Heading -->

      <!--Grid row-->
      <div class="row wow fadeIn">

        <!--Grid column-->
        <div class="col-md-12 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <div class="card-body">
                <h4 class="title text-dark">Change Password</h4>
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
                <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('change_pwd') }}">
                <!-- Default input -->
                    @csrf
                    <input required type="password" placeholder="Enter New Password" class="form-control @error('password') is-invalid @enderror mr-3" name="password" value="{{ old('password') }}">

                    <input required type="password" placeholder="Re-enter New Password" class="form-control @error('confirm_password') is-invalid @enderror mr-3" name="confirm_password" value="{{ old('confirm_password') }}">

                    <button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> Save </button>
                </form>

            </div>

          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->

@endsection

@section('foot')
  @include('layouts.foot')
@endsection
