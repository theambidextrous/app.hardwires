@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
  @include('layouts.side', [ 'active' => [0,0,0,'active',0]])
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
            <span>Special Orders</span>
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
                <h4 class="title text-dark">Allocate Questionnaire</h4>
                <small class="alert-warning">*Note: By clicking on <b>SAVE</b> button, you initiate an email to the specified user granting them access to the questionnaire before they make any <b>payment</b>.</small>
                <hr>
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
                <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('new_order') }}">
                <!-- Default input -->
                    @csrf
                    <input required type="email" placeholder="Email Address" class="form-control @error('email') is-invalid @enderror mr-1" name="email" value="{{ old('email') }}">

                    <input required type="text" placeholder="Name and Surname" class="form-control @error('name') is-invalid @enderror mr-1" name="name" value="{{ old('name') }}">
                    
                    <input required type="text" placeholder="Cell Number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                    
                    <button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> SAVE</button>
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
                <h4 class="title text-dark">Special Orders</h4>
                <small>This refers to customers given access to questionnaires before they make payment.</small>
              <!-- Table  -->
              <table class="table table-hover mdl-data-table reportables">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#Ref</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($data))
                    @foreach( $data as $_rem )
                        <tr>
                            <td>{{ $_rem['ref'] }}</td>
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
