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
            <a href="{{ route('questions', ['sectionId' => $sectionId ] ) }}" target="">Back to Questions</a>
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
                <h4 class="title text-dark">Modify this question</h4>
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
                <form class="" style="max-width:90%;" method="POST" action="{{ route('save_q') }}">
                <!-- Default input -->
                    @csrf
                    {{ method_field('POST') }}
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <label class="text-lef">Question</label>
                            <input type="hidden" name="questionId" value="{{ $thisItem->id }}"/>
                            <textarea type="text" placeholder="Enter your question here..." class="form-control @error('name') is-invalid @enderror" name="name">{{ $thisItem->name }}</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="text-lef">Number</label>
                            <input type="text" placeholder="" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ $thisItem->number }}">
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
                            @if(count($options))
                            @foreach($options as $_opt)
                            <div class="row mb-1">
                                <div class="col-md-7">
                                    <label class="text-lef">Option label</label>
                                    <input type="text" placeholder="label" class="form-control ml-1" name="option[]" value="{{ $_opt['option'] }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-lef">Option value</label>
                                    <input type="number" placeholder="value" class="form-control ml-1" name="value[]" value="{{ $_opt['value'] }}">
                                </div>
                                <div class="col-md-1">
                                    <!-- <a href="#" class="remove_field"><i class="fas fa-times"></i></a> -->
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <button id="add_field_button" type="button" class="btn btn-link btn-sm pull-right">Add More Options</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input style="min-width:200px;" class="btn btn-primary btn-md my-0 p" type="submit"></input>
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
