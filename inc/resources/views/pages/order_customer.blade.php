@extends('layout.master')
@section('sds_menu_class','menu-open')
@section('customer_class','menu-open')
@section('order_customer_class','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Customer List
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Ordered Customers List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  <th>Cus Name</th>
                  <th>Company Name</th>
                  <th>Client Mobile</th>
                  <th>Client Feedback</th>
                  <th>Client Message</th>
                  <th class="text-center">Price</th>
                </tr>
                </thead>
                <tbody>
                @php($sl=0)
                @foreach( $orders as $order )
                <tr>
                  <td>{{ ++$sl }}</td>
                  <td>{{ $order->order_info['qb_name'] }}</td>
                  <td>{{ $order->order_info['qb_company_name'] }}</td>
                  <td>{{ $order->cf_mobile }}</td>
                  <td align="center">
                    @if( $order->cf_client_feedback == 6 )
                    অর্ডার
                    @endif
                  </td>
                  <td>{{ $order->cf_client_message }}</td>
                  <td>{{ $order->cf_price }}</td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          </section>
          @endsection
