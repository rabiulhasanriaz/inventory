@extends('layout.master')
@section('inventory_class','menu-open')
@section('supplier_class','menu-open')
@section('inv_supplier_class','menu-open')
@section('supplier_list','active')
@section('content')
<section class="content">
<section class="content-header">
  <div class="row">
    <div class="pull-left">
      <h3 style="margin: 4px;">
        Supplier
      </h3>
    </div>
  </div>

    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Supplier Details</h3>
              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <h2 class="text-center">{{ $supplier_info->inv_sup_com_name }}</h2>
              <table class="table table-bordered table-striped">
                <tr>
                  	<th>Address</th>
                  	<td>{{ $supplier_info->inv_sup_address }}</td>
                </tr>
                <tr>
                	<th>Person</th>
                	<td>{{ $supplier_info->inv_sup_person }}</td>
                </tr>
                <tr>
                	<th>Mobile</th>
                	<td>{{ $supplier_info->inv_sup_mobile }}</td>
                </tr>
                <tr>
                    <th>Submit By</th>
                    <td>{{ $supplier_info->sup_submit_by['au_name'] }}</td>
                  </tr>
                <tr>
                	<th>Email</th>
                	<td>{{ $supplier_info->inv_sup_email }}</td>
                </tr>
                <tr>
                	<th>Complain Number</th>
                	<td>{{ $supplier_info->inv_sup_complain_num }}</td>
                </tr>
                <tr>
                    <th>Supplier Typer</th>
                    <td>
                        @if($supplier_info->inv_sup_type == 1)
                        Local
                        @elseif($supplier_info->inv_sup_type == 2)
                        Global
                        @endif
                    </td>
                </tr>
                <tr>
                	<th>Status</th>
                	<td>
                    @if($supplier_info->inv_sup_status == 1)
                    Activate
                    @elseif($supplier_info->inv_sup_status == 0)
                    Deactivate
                    @endif
                  </td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
         </section>
@endsection
