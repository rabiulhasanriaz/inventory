@extends('layout.master')
@section('inventory_class','menu-open')
@section('product_class','menu-open')
@section('inv_pro_grp','menu-open')
@section('pro_grp_list','active')
@section('content')
<section class="content">
<section class="content-header">
  <div class="row">
    <div class="pull-left">
      <h3 style="margin: 4px;">
        Product Group
      </h3>
    </div>
  </div>

    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Group Details</h3>
              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <h2 class="text-center">{{ $pro_grp_info->inv_pro_grp_name }}</h2>
              <table class="table table-bordered table-striped">
                <tr>
                  	<th>Company ID</th>
                  	<td>{{ $pro_grp_info->inv_pro_grp_com_id }}</td>
                </tr>
                <tr>
                  	<th>Submitted By</th>
                  	<td>{{ $pro_grp_info->grp_submit_by['au_name'] }}</td>
                </tr>
                <tr>
                	<th>Status</th>
                	<td>
                    @if($pro_grp_info->inv_pro_grp_status == 1)
                    Activate
                    @elseif($pro_grp_info->inv_pro_grp_status == 0)
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
