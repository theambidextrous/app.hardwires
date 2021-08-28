@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
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
            <div class="card-body">
                <h4 class="title text-dark">Create New Section</h4>
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
                <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('create_sec') }}">
                <!-- Default input -->
                    @csrf
                    <input type="hidden" name="series" value="{{ $thisItem->id }}"/>
                    <input type="text" placeholder="Name your section" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                    
                    <input type="text" readonly placeholder="Belongs to {{ $thisItem->name }}" class="form-control ml-1" name="nothing" value="">
                    
                    <button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> Save</button>
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
                <h4 class="title text-dark">Questionnaire Sections</h4>
              <!-- Table  -->
              <table class="table table-hover">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#ID</th>
                    <th>Section Name</th>
                    <th style="max-width:100px;">Questions</th>
                    <th style="max-width:100px;">Preview</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($sections))
                    @foreach( $sections as $_rem )
                        <tr>
                            <th scope="row">{{ $_rem['id'] }}</th>
                            <td>{{ $_rem['name'] }}</td>
                            <td style="max-width:100px;">
                                <a href="{{ route('questions', ['sectionId' => $_rem['id'] ] ) }}" class="list-group-item list-group-item-action waves-effect">
                                    <span class="badge badge-lg badge-info badge-pill pull-right">Questions
                                        <i class="fas fa-question-circle ml-1"></i>
                                    </span>
                                </a>
                            </td>
                            <td style="max-width:100px;">
                                <a target="_blank" href="{{ route('section_prev', ['sectionId' => $_rem['id'] ] ) }}" class="list-group-item list-group-item-action waves-effect">
                                    <span class="badge badge-lg badge-info badge-pill pull-right">Preview
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
