@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
  @include('layouts.side', [ 'active' => [0,0,0,0,'active',0,0]])
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
            <span>Pricing</span>
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
                <h4 class="title text-dark">Adjust Prices</h4>
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
                <table class="table table-hover">
                    <tbody>
                        <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('adm_price_update') }}">
                        <!-- Default input -->
                            @csrf
                            <input type="hidden" name="name" value="{{ $pricing->name ?? 'hardwires' }}"/>
                            <tr>
                                <td><i>Normal price</i></td>
                                <td><i>Discounted price</i></td>
                                <td><i>Update price</i></td>
                            </tr>
                            <tr>
                                <td> <input type="number" placeholder="" class="form-control @error('normal') is-invalid @enderror" name="normal" value="{{ $pricing->normal ?? ''  }}"></td>
                                <td><input type="number" placeholder="" class="form-control @error('discounted') is-invalid @enderror ml-1" name="discounted" value="{{ $pricing->discounted ?? ''  }}"></td>
                                <td><button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> Save</button></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
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
