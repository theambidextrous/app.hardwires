@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
  <style>
    .q-light{
      color:#0d48a0;
      font-weight:bold;
    }
  </style>
@endsection

@section('side')
  @include('layouts.side', [ 'active' => [0,0,0,0,0,'active']] )
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
            <span><a href="{{ route('adm_ngo') }}" target="">NGOs/Corporates</a></span>
            <span>/</span>
            <span>{{ $thisItem->name }}</span>
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
              <div class="card-body text-center">
                <!-- <h4 class="title text-dark">Edit NGO</h4> -->
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
                <form class="" style="max-width:90%;" method="post" action="{{ route('adm_ngo_update') }}">
                  @csrf
                  <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label class="text-lef">Name</label>
                        <input type="hidden" name="id" value="{{ $thisItem->id }}"/>
                        <input type="text" placeholder="" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $thisItem->name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="text-lef">Email(login id)</label>
                        <input readonly type="email" placeholder="" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $thisItem->email }}">
                    </div>
                    <div class="col-md-4">
                        <label class="text-lef">Contact person name</label>
                        <input type="text" placeholder="" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ $thisItem->contact_name }}">
                    </div>
                  </div>
                  <hr>
                  <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label class="text-lef">Address</label>
                        <input type="text" placeholder="" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ $thisItem->address }}">
                    </div>
                    <div class="col-md-4">
                        <label class="text-lef">Phone</label>
                        <input type="text" placeholder="" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $thisItem->phone }}">
                    </div>
                    <div class="col-md-4">
                        <label class="text-lef">Invoice terms(in days)</label>
                        <input type="number" placeholder="" class="form-control @error('terms') is-invalid @enderror" name="terms" value="{{ $thisItem->terms }}">
                    </div>
                  </div>
                  <hr>
                  <button style="min-width:200px;" class="btn btn-primary btn-md my-0 p" type="submit"><i class="fas fa-save"></i> Save</button>
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
