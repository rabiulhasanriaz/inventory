@extends('layout.master')
@section('oms_class','menu-open')
@section('oms_lunch_class','menu-open')
@section('lunch_add_new_bill','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Lunch
      </h1>
    </section>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Lunch Bill</h3>
            </div>
            @if(session()->has('msg3'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('msg3') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            <!-- /.box-header -->
            <!-- form start -->
            {{Form::open(['action' => 'OmsController@lunch_add_new_bill_submit' , 'method' => 'post' , 'class' => 'form-horizontal'])}}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">What For</label>
                  <div class="col-sm-6">
                    <select class="form-control select2" name="reason">
                      <option value="">Select</option>
                      @foreach($q as $c)
                      <option value="{{ $c->qb_serial }}">{{ $c->qb_company_name }}( {{ $c->qb_mobile }} )</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Reason</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_reason" class="form-control" id="inputEmail3" placeholder="Place Your Reason..." required>
                  </div>
                  @if( $errors->has('oms_reason') )
                    <p class="text-danger">{{ $errors->first('oms_reason') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bill Date</label>
                  <div class="col-sm-6">
                    <input type="text" id="from5" data-date-format="yyyy-mm-dd" name="oms_bill_date" autocomplete="off" class="form-control" placeholder="Place Bill Date...." required>
                  </div>
                  @if( $errors->has('oms_bill_date') )
                    <p class="text-danger">{{ $errors->first('oms_bill_date') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Number Person</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_person" class="form-control" id="inputEmail3" placeholder="Place Person...." required>
                  </div>
                  @if( $errors->has('oms_person') )
                    <p class="text-danger">{{ $errors->first('oms_person') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Amount</label>
                  <div class="col-sm-6">
                    <input type="text" name="oms_debit" class="form-control" id="inputEmail3" placeholder="Place Your Amount.." required>
                  </div>
                  @if( $errors->has('oms_debit') )
                    <p class="text-danger">{{ $errors->first('oms_debit') }}</p>
                  @endif
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info pull-right">Add</button>
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

$( "#from5" ).datepicker({
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
