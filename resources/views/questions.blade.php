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
  @include('layouts.side', [ 'active' => [0,'active',0,0,0]])
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
            <span><a href="{{ route('series') }}" target="">Questionnaires</a></span>
            <span>/</span>
            <span><a href="{{ route('sections', [ 'seriesId' => $parentItem['id'] ]) }}" target="">{{ $parentItem['name'] }}</a></span>
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
                <h4 class="title text-dark">Add New Question</h4>
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
                <form class="" style="max-width:90%;" method="post" action="{{ route('create_q') }}">
                <!-- Default input -->
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <label class="text-lef">Question</label>
                            <input type="hidden" name="section" value="{{ $thisItem->id }}"/>
                            <input type="text" placeholder="Enter your question here..." class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="text-lef">Number</label>
                            <input type="text" placeholder="" class="form-control @error('number') is-invalid @enderror" name="number" value="">
                        </div>
                        <div class="col-md-3">
                            <label class="text-lef">Can select multiple</label>
                            <select class="form-control" name="is_check">
                                <option selected value="0">No - select just one</option>
                                <option value="1">Yes - can select all that apply</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <small>Enter question options below, maximum of 6</small>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-12" id="input_fields_wrap">
                            <div class="row mb-1">
                                <div class="col-md-7">
                                    <label class="text-lef">Option label</label>
                                    <input type="text" placeholder="label" class="form-control ml-1" name="option[]" value="">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-lef">Option value</label>
                                    <input type="number" placeholder="value" class="form-control ml-1" name="value[]" value="">
                                </div>
                                <div class="col-md-1">
                                    <!-- <a href="#" class="remove_field"><i class="fas fa-times"></i></a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <button id="add_field_button" type="button" class="btn btn-link btn-sm pull-right">Add More Options</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <button style="min-width:200px;" class="btn btn-primary btn-md my-0 p" type="submit"><i class="fas fa-save"></i> Save</button>
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
                <h4 class="title text-dark">Questions in this Section</h4>
              <!-- Table  -->
              <table class="table table-hover">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#No</th>
                    <th>Question</th>
                    <th style="min-width:200px;">Actions</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($questions))
                    @foreach( $questions as $_rem )
                        <tr>
                            <th class="q-light" scope="row">{{ $_rem['number'] }}</th>
                            <td>
                              <p class="q-light">{{ $_rem['name'] }}</p>
                              <hr>
                              <ul>
                              @foreach( $_rem['options'] as $opts )
                                <li>{{ $opts['option'] }}({{ $opts['value'] }})</li>
                              @endforeach
                              </ul>
                            </td>
                            <td style="max-width:100px;">
                                <a href="{{ route('manage_q', ['questionId' => $_rem['id']]) }}" class="list-group-item list-group-item-action waves-effect">
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
