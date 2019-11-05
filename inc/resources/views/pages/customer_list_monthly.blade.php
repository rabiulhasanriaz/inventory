@extends('layout.master')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Customer List
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">The User's Customer</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  <th>Cus ID</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Location</th>
                  <th>Reason</th>
                  <th>Result</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @php($i)
                @php($sl=0)
                @foreach( $monthly_cus as $customer )
                <tr>
                  <td>{{ ++$sl }}</td>
                  <td>{{ $customer->qb_serial }}</td>
                  <td>{{ $customer->qb_name }}</td>
                  <td>{{ $customer->qb_mobile }}</td>
                  <td>{{ $customer->qb_address }}</td>
                  <td>{{ $customer->reas_info->reason_info['sc_catagory'] }}</td>
                  <td>
                    @for( $i = 1; $i <= $customer->qb_result; ++$i)
                    <b style="color: green; font-size: 20px;">*</b>
                    @endfor
                  </td>
                  <td align="center"><a href="{{ url('/client_feedback',['id' => $customer->qb_id]) }}" class="btn btn-info btn-sm">Follow Up</a></td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          </section>
          @endsection
