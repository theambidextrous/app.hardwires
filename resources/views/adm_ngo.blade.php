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
            <span>NGOs/Corporates</span>
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
              <!-- Table  -->
              <table class="table table-hover">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Person</th>
                    <th>Invoice Terms</th>
                    <th style="min-width:200px;">Actions</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($ngos))
                    @foreach( $ngos as $_rem )
                        <tr>
                            <th class="q-light" scope="row">{{ $_rem['id'] }}</th>
                            <td> {{ $_rem['name'] }} </td>
                            <td> {{ $_rem['email'] }} </td>
                            <td> {{ $_rem['contact_name'] }} </td>
                            <td> {{ $_rem['terms'] }} days</td>
                            <td style="max-width:100px;">
                                <a href="{{ route('adm_view_ngo', ['id' => $_rem['id'] ]) }}" class="list-group-item list-group-item-action waves-effect">
                                    <span class="badge badge-lg badge-secondary badge-pill pull-right">Manage
                                        <i class="fas fa-question-circle ml-1"></i>
                                    </span>
                                </a>
                            </td>
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
