@extends('layouts.app')

@section('nav')
  @include('layouts.nav')
@endsection

@section('side')
    @if(Auth::user()->user_group == 1 )
        @include('layouts.side', [ 'active' => ['active', 0,0,0,0]])
    @else
        @include('layouts.c_side', [ 'active' => [0,'active',0,0,0]])
    @endif
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
            <span>My Orders</span>
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
                <h4 class="title text-dark">Order Hardwires Questionnaires</h4>
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

                @if($orgmeta->is_member == 'YES')
                  <div class="alert alert-info">Order at a discounted per unit rate of <b>R {{ number_format(\App\Models\Price::find(1)->discounted, 2)}}</b></div>
                @else
                  <div class="alert alert-info">Normal per unit rate of <b>R {{ number_format(\App\Models\Price::find(1)->normal, 2)}}</b> apply for orders below 20 persons.  Otherwise a discounted per unit rate of <b>R {{ number_format(\App\Models\Price::find(1)->discounted, 2)}}</b> apply</div>
                @endif
                <form class="d-flex justify-content-center" style="max-width:90%;" method="post" action="{{ route('new_c_order') }}">
                <!-- Default input -->
                    @csrf
                    <input type="number" placeholder="Quantity or Number of persons" class="form-control @error('Quantity') is-invalid @enderror" name="Quantity" value="{{ old('Quantity') }}">
                    
                    <input readonly type="text" class="form-control @error('org') is-invalid @enderror ml-1" name="org" value="{{ $orgmeta->name }}">
                    
                    <button style="min-width:100px;" class="btn btn-primary btn-sm my-0 p" type="submit"><i class="fas fa-save"></i> Order</button>
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
                <h4 class="title text-dark">My Orders</h4>
              <!-- Table  -->
              <table class="table table-hover">
                <!-- Table head -->
                <thead class="blue-grey lighten-4">
                  <tr>
                    <th>#Invoice</th>
                    <th>#Description</th>
                    <th>#Quantity</th>
                    <th>Unit Cost</th>
                    <th>Cost</th>
                    <th style="max-width:300px;">Actions</th>
                  </tr>
                </thead>
                <!-- Table head -->

                <!-- Table body -->
                <tbody>
                  @if(count($orderData))
                    @foreach( $orderData as $_rem )
                        <tr>
                            <th scope="row">{{ $_rem['id'] }}</th>
                            <td>{{ $_rem['item'] }}</td>
                            <td>{{ $_rem['qty'] }} <small>Persons</small></td>
                            <td>R{{ number_format( ($_rem['unit_cost']), 2) }}</td>
                            <td>R{{ number_format( ($_rem['cost']), 2) }}</td>
                            <td style="max-width:100px;">
                                <a href="{{ route('distribute', ['order' => $_rem['id'] ] ) }}" class="list-group-item list-group-item-action waves-effect">
                                    <span style="font-size:14px!important;" class="badge badge-lg badge-info badge-pill pull-right">Distribute
                                        <i class="fas fa-balance-scale ml-1"></i>
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
