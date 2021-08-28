@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
    @if(Auth::user()->user_group == 1 )
        @include('layouts.side', [ 'active' => ['active', 0,0,0,0]])
    @else
        @include('layouts.c_side', [ 'active' => ['active', 0,0,0,0]])
    @endif
@endsection

@section('content')
<div class="container-fluid mt-5">

      <!-- Heading -->
      <div class="card mb-4 wow fadeIn">

        <!--Card content-->
        <div class="card-body d-sm-flex justify-content-between">

          <h4 class="mb-2 mb-sm-0 pt-1">
            <a href="{{ route('index') }}" target="">My Orders</a>
            <span>/</span>
            <span>Distribute</span>
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
                <h4 class="title text-dark">Distribute Questionnaires</h4>
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
                <div class="alert alert-info"> You can only distribute to <b>{{ $thisItem->qty }} </b> persons. So far <b>{{ $used }} </b> units have been used. <b>This action cannot be undone</b></div>
                <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('distribute_new') }}">
                <!-- Default input -->
                    @csrf
                    <input type="hidden" name="org_invoice" value="{{ $thisItem->id }}"/>
                    <input type="hidden" name="max_persons" value="{{ $thisItem->qty }}"/>
                    <input required type="email" placeholder="Email Address" class="form-control @error('email') is-invalid @enderror mr-1" name="email" value="{{ old('email') }}">

                    <input required type="text" placeholder="Name and Surname" class="form-control @error('name') is-invalid @enderror mr-1" name="name" value="{{ old('name') }}">
                    
                    <input required type="text" placeholder="Cell Number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                    
                    <button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> SEND</button>
                </form>
            </div>

          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->

      <!--Grid row-->
      <div class="row wow fadeIn">

        <!--Grid column-->
        <div class="col-md-12 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <div class="card-body">
                <h4 class="title text-dark">Persons on this order</h4>
                <div class="alert alert-info"> The following persons were notified via email to start hardwires Assessment.</div>
              <!-- Table  -->
              <table class="table table-hover mdl-data-table reportables">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#Ref</th>
                    <th>#Attempt</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($persons))
                    @foreach( $persons as $_rem )
                        <tr>
                            <td>{{ $_rem['ref'] }}</td>
                            <td>{{ $_rem['attempt'] }}</td>
                            <td>{{ $_rem['name'] }}</td>
                            <td>{{ $_rem['email'] }}</td>
                            <td>{{ $_rem['phone'] }}</td>
                        </tr>
                    @endforeach
                  @endif
                </tbody>
                <!-- Table body -->
              </table>
              <!-- Table  -->

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
