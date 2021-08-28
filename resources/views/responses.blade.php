@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
  @include('layouts.side', [ 'active' => [0,0,'active',0,0]])
@endsection

@section('content')
<div class="container-fluid mt-5">

      <!-- Heading -->
      <div class="card mb-4 wow fadeIn">

        <!--Card content-->
        <div class="card-body d-sm-flex justify-content-between">

          <h4 class="mb-2 mb-sm-0 pt-1">
            <a href="{{ route('index') }}" target="">{{ Config::get('app.name') }}</a>
            <span>/</span>
            <span>Responses</span>
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
                <h4 class="title text-dark">Customer Questionnaire Responses</h4>
              <!-- Table  -->
              <table class="table table-hover mdl-data-table reportables">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>Ref</th>
                    <th>Attempt</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>#Completed</th>
                    <th style="max-width:1000px;">Graph</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($data))
                    @foreach( $data as $_rem )
                        <tr>
                            <td>{{ $_rem['ref'] }}</td>
                            <td>{{ $_rem['attempt'] }}</td>
                            <td>{{ $_rem['name'] }}</td>
                            <td>{{ $_rem['email'] }}</td>
                            <td>{{ $_rem['comp'] }} of 16</td>
                            <td style="max-width:100px;">
                                <a href="{{ route('c_response', ['cid' => $_rem['id'], 'attempt' => $_rem['attempt']  ] ) }}" class="list-group-item list-group-item-action waves-effect">
                                    <span class="badge badge-lg badge-primary badge-pill">View
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
