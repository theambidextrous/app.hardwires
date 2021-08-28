@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
  <style>
    thead, tfoot, tbody { display: block!important; }

    tbody {
        height: 200px!important;       /* Just for the demo          */
        overflow-y: auto!important;    /* Trigger vertical scroll    */
        overflow-x: hidden!important;  /* Hide the horizontal scroll */
    }
  </style>
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
            <span><a href="{{ route('responses') }}" target="">Customer Responses</a></span>
            <span>/</span>
            <span>{{ $thisItem->ref }} - {{ $thisItem->name }}</span>
          </h4>
        </div>

      </div>
      <!-- Heading -->
      <!-- Graph -->
      <div class="card mb-4 wow fadeIn">

        <!--Card content-->
        <div class="card-body justify-content-between">
          <h4 class="mb-2 mb-sm-0 pt-1">
            @if($hasGraph == 16)
                <div class="row wow fadeIn">
                    <div class="col-md-6">
                      <a target="_blank" href="{{ route('stream', ['file' => $thisGraph ]) }}" class="list-group-item list-group-item-action waves-effect">
                            <span class="badge badge-lg badge-danger badge-pill pull-right">
                                View Graph
                            </span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a target="_blank" href="{{ route('stream', ['file' => $thisReport ]) }}" class="list-group-item list-group-item-action waves-effect">
                            <span class="badge badge-lg badge-danger badge-pill pull-right">
                                Download Full Report
                            </span>
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">User has no graph yet. The graph will appear here once they complete the 16 sections</div>
            @endif
          </h4>
        </div>

      </div>
      <!-- Graph -->

      @if(count($data))
      @foreach($data as $_sec)
      <!--Grid row-->
      <div class="row wow fadeIn">

        <!--Grid column-->
        <div class="col-md-12 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <div class="card-body">
                <h4 class="title text-dark"><b>{{ $_sec['name'] }} :-</b> Responses by Participant {{ $thisItem->ref }}</h4>
              <!-- Table  -->
              <table class="table table-hover tbodyscroll">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th style="width:60%;">Question</th>
                    <th style="width:20%;">Choice</th>
                    <th style="width:10%;">Point</th>
                    <th style="width:10%;">Summation</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                    @php($sum = 0)
                    @if(count($_sec['responses']))
                        @foreach( $_sec['responses'] as $_rem )
                            @php($sum += $_rem['points'])
                            <tr>
                                <td style="width:60%;">{{ $_rem['label'] }}</td>
                                <td style="width:20%;">{{ $_rem['choice_text'] }}</td>
                                <td style="width:10%;">{{ $_rem['points'] }}</td>
                                <td style="width:10%;">{{ $_rem['running_sum'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <!-- Table body -->
                <tfoot class="blue-grey lighten-2">
                    <tr>
                        <td><b>Score</b></td>
                        <td></td>
                        <td></td>
                        <td><b>{{ $sum }} ({{ floor(($sum/$_sec['total']) * 100) }}%)</b></td>
                    </tr>
                </tfoot>
              </table>
              <!-- Table  -->

            </div>

          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->
      @endforeach
      @endif

@endsection

@section('foot')
  @include('layouts.foot')
@endsection
