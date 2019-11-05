@extends('layout.master')
@section('sds_menu_class','menu-open')
@section('followup_class','menu-open')
@section('nonfollow_cus_class','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Follow Up
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title"> Customers List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  <th>Cus ID</th>
                  <th>Cus Name</th>
                  <th>Company Name</th>
                  <th>Mobile</th>
                  <th>Location</th>
                  <th>Reason</th>
                  <th>Result</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @php($i)
                  @php($sl=0)
                  @foreach( $non_followed_cus as $follow )
                  <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ $follow->qb_id }}</td>
                    <td>{{ $follow->qb_name }}</td>
                    <td>{{ $follow->qb_company_name }}</td>
                    @if( ($follow->qb_mobile && $follow->qb_mobile1 && $follow->qb_mobile2) == true  )
                    <td>{{ $follow->qb_mobile }}, {{ $follow->qb_mobile1 }}, {{ $follow->qb_mobile2 }} </td>
                    @elseif( ($follow->qb_mobile && $follow->qb_mobile1) == true )
                    <td>{{ $follow->qb_mobile }}, {{ $follow->qb_mobile1 }}</td>
                    @else
                    <td>{{ $follow->qb_mobile }}</td>
                    @endif
                    <td>{{ $follow->qb_address }}</td>
                    <td>{{ $follow->reas_info['sr_reason'] }}</td>
                    <td>
                      @for( $i=0; $i<$follow->qb_result; $i++ )
                      <b style="color: green; font-size: 20px;">*</b>
                      @endfor
                    </td>
                    <td align="center"><a href="{{ url('/client_feedback',['id' => $follow->qb_id]) }}" class="btn btn-info btn-sm">Follow Up</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          </section>
          @endsection
