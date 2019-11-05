@extends('layout.master')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Lunch
      </h1>
    </section>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Update Rejected Balance</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{Form::open(['action' => ['OmsController@lunch_reject_update', $lunch_up_bills->sl_id], 'method' => 'post' , 'class' => 'form-horizontal'])}}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">What For</label>
                  <div class="col-sm-6">
                    <select class="form-control select2" name="lunch_customer">
                      <option value="">Select</option>
                      @foreach($lunch_c as $q)
                      <option value="{{ $q->qb_serial }}" {{ ($q-> qb_serial==$lunch_up_bills->oms_what_for)?'selected':'' }}>
                      {{ $q->qb_company_name }}( {{ $q->qb_mobile }} )
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Reason</label>
                  <div class="col-sm-6">
                    <input type="text" name="lunch_reason" class="form-control" id="inputEmail3" placeholder="Update Your Rejected Amount" value="{{ $lunch_up_bills->oms_reason }}" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Person</label>
                  <div class="col-sm-6">
                    <input type="text" name="lunch_person" class="form-control" id="inputEmail3" placeholder="Update Your Rejected Amount" value="{{ $lunch_up_bills->oms_person }}" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
                  <div class="col-sm-6">
                    <input type="text" name="lunch_cost" class="form-control" id="inputEmail3" placeholder="Update Your Rejected Amount" value="{{ $lunch_up_bills->oms_debit }}" required>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                 </div>
              </div>
              <!-- /.box-footer -->
            {{ Form::close() }}
          </div>
         </section>
@endsection
