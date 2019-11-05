@extends('layout.master')
@section('oms_class','menu-open')
@section('oms_convence_class','menu-open')
@section('convence_add_new_bill','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Convence
      </h1>
    </section>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Convence Bill</h3>
            </div>
            @if(session()->has('msg4'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('msg4') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            <!-- /.box-header -->
            <!-- form start -->
            {{Form::open(['action' => 'OmsController@convence_add_new_bill_submit' , 'method' => 'post' , 'class' => 'form-horizontal'])}}
              <div class="box-body">
              <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">What For</label>
                  <div class="col-sm-6">
                    <select class="form-control select2" name="oms_what_for" required>
                    	<option value="">Select One</option>
                      @foreach($customers as $customer)
                      <option value="{{ $customer->qb_serial }}">{{ $customer->qb_company_name }}( {{ $customer->qb_mobile }} )</option>
                      @endforeach
                    </select>
                  </div>
                  @if( $errors->has('oms_what_for') )
                    <p class="text-warning">{{ $errors->first('oms_what_for') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bill Date</label>
                  <div class="col-sm-6">
                    <input type="text" data-date-format="yyyy-mm-dd" name="oms_bill_date" autocomplete="off" class="form-control" id="from4" placeholder="Place Bill Date...." required>
                  </div>
                  @if( $errors->has('oms_bill_date') )
                    <p class="text-warning">{{ $errors->first('oms_bill_date') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">From</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_dfrom" required class="form-control" id="inputEmail3" placeholder="Place Boarding point...">
                  </div>
                  @if( $errors->has('oms_dfrom') )
                    <p class="text-warning">{{ $errors->first('oms_dfrom') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Mode</label>
                  <div class="col-sm-6">
                    <select class="form-control select2" name="oms_mode" required>
                      <option value="">Select One</option>
                      <option value="Boat">Boat</option>
                      <option value="Bus">Bus</option>
                      <option value="CNG">CNG</option>
                      <option value="Leguna">Leguna</option>
                      <option value="Lonch">Lonch</option>
                      <option value="Rent-A-Car">Rent-A-Car</option>
                      <option value="Rickshaw">Rickshaw</option>
                      <option value="Taxi-Cab">Taxi-Cab</option>
                      <option value="Train">Train</option>
                      <option value="Bike">Bike</option>
                    </select>
                  </div>
                  @if( $errors->has('oms_mode') )
                    <p class="text-warning">{{ $errors->first('oms_mode') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">To/Destinition</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_dto" required class="form-control" id="inputEmail3" placeholder="Place Destinition point...">
                  </div>
                  @if( $errors->has('oms_dto') )
                    <p class="text-warning">{{ $errors->first('oms_dto') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Amount</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_debit" required class="form-control" id="inputEmail3" placeholder="Place Your Amount..">
                  </div>
                  @if( $errors->has('oms_debit') )
                    <p class="text-warning">{{ $errors->first('oms_debit') }}</p>
                  @endif
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                 </div>
              </div>
              <!-- /.box-footer -->
            {{Form::close()}}
          </div>
         </section>
@endsection
@section( 'custom_script' )
<script>

$(document).ready(function(){

$( "#from4" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
        endDate: "today",
        autoclose: true,
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});
</script>
@endsection
