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
@if(Auth::user()->user_group != 1 )
  @include('commons.c_home')
@else
<div class="container-fluid mt-5">

      <!-- Heading -->
      <div class="card mb-4 wow fadeIn">

        <!--Card content-->
        <div class="card-body d-sm-flex justify-content-between">

          <h4 class="mb-2 mb-sm-0 pt-1">
            <a href="{{ route('index') }}" target="">{{ Config::get('app.name') }}</a>
            <span>/</span>
            <span>Dashboard</span>
          </h4>

          <!-- <form class="d-flex justify-content-center">
            <input type="search" placeholder="Type your query" aria-label="Search" class="form-control">
            <button class="btn btn-primary btn-sm my-0 p" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form> -->

        </div>

      </div>
      <!-- Heading -->

      <!--Grid row-->
      <div class="row wow fadeIn">

        <!--Grid column-->
        <div class="col-md-6 mb-4">

          <!--Card-->
          <div class="card mb-4">

            <!-- Card header -->
            <div class="card-header text-center">
              Questionnaire
            </div>

            <!--Card content-->
            <div class="card-body">
              <div class="list-group list-group-flush">
                <a href="{{ route('series') }}" class="list-group-item list-group-item-action waves-effect">Code
                  <span class="badge badge-success badge-pill pull-right">{{ $series->id }}
                    <i class="fas fa-external-link-alt"></i>
                  </span>
                </a>
                <a href="{{ route('series') }}" class="list-group-item list-group-item-action waves-effect">Name
                  <span class="badge badge-primary badge-pill pull-right">{{ $series->name }}</span>
                </a>
              </div>
            </div>

          </div>
          <!--/.Card-->
        </div>
        <!--Grid column-->
        <div class="col-md-6 mb-4">
          <!--Card-->
          <div class="card mb-4">
            <!--Card content-->
            <div class="card-body">

              <!-- List group links -->
              <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action waves-effect">Revenue
                  <span class="badge badge-success badge-pill pull-right">R {{ $rev }}
                    <i class="fas fa-arrow-up ml-1"></i>
                  </span>
                </a>
                <a class="list-group-item list-group-item-action waves-effect">Completed
                  <span class="badge badge-danger badge-pill pull-right">{{ $completed }}
                    <i class="fas fa-bars ml-1"></i>
                  </span>
                </a>
                <a class="list-group-item list-group-item-action waves-effect">Spec. Orders
                  <span class="badge badge-primary badge-pill pull-right">R {{ $orders }}
                    <i class="fas fa-shopping-cart ml-1"></i>
                  </span>
                </a>
              </div>
              <!-- List group links -->
            </div>
          </div>
          <!--/.Card-->

        </div>

        <!--Grid column-->
        <div class="col-md-12 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <div class="card-body">
              <h4 class="title">Recent Sign Ups</h4>
              <!-- Table  -->
              <table id="dt" class="table table-hover mdl-data-table reportables">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Cell number</th>
                    <th>Referred By</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                @foreach( $recents as $rec )
                  <tr>
                    <th scope="row">{{ $rec['id'] }}</th>
                    <td>{{ $rec['name'] }}</td>
                    <td>{{ $rec['email'] }}</td>
                    <td>{{ $rec['phone'] }}</td>
                    <td>{{ $rec['referral_cell'] }}</td>
                  </tr>
                @endforeach
                </tbody>
                <!-- Table body -->
              </table>
              <a href="{{ route('responses') }}" class="list-group-item list-group-item-action waves-effect">
                <span class="badge badge-danger badge-pill pull-right">View All
                  <i class="fas fa-arrow-down ml-1"></i>
                </span>
              </a>
            </div>
          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->
    </div>
  @endif
@endsection

@section('foot')
  @include('layouts.foot')
@endsection
