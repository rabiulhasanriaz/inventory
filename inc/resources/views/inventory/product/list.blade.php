@extends('layout.master')
@section('inventory_class','menu-open')
@section('product_class','menu-open')
@section('inv_pro_class','menu-open')
@section('pro_list','active')
@section('content')
<section class="content">
        @if(session()->has('det_up'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('det_up') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        @if(session()->has('pro_del'))
              <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('pro_del') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
        @endif
            <section class="content-header">
                  <h1>
                    Product
                  </h1>
                </section>
                <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">Product List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                              <th>SL</th>
                              <th>Type Name</th>
                              <th>Product Name</th>
                              <th>Available Stock</th>
                              <th>Warrenty</th>
                              <th>Buy Price</th>
                              <th>Sell Price</th>
                              <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($sl=0)
                            @foreach ($pro_det as $detail)
                            <tr>
                                <td>{{ ++$sl }}</td>
                                <td>{{ $detail->type_info['inv_pro_type_name'] }}</td>
                                <td>{{ $detail->inv_pro_det_pro_name }}</td>
                                <td>{{ $detail->inv_pro_det_available_qty }}</td>
                                <td>{{ $detail->inv_pro_det_pro_warranty }} days</td>
                                <td class="text-right">{{ $detail->inv_pro_det_buy_price }}</td>
                                <td class="text-right">{{ $detail->inv_pro_det_sell_price }}</td>
                                <td align="center">
                                    <a href="{{ route('inventory.product_show',$detail->inv_pro_det_id) }}" class="action_btn btn_show">
                                      <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.product_delete',$detail->inv_pro_det_id) }}" onclick="return deleteprocat()" class="action_btn btn_delete">
                                      <i class="fa fa-trash"></i>
                                    </a>
                                    <a href="{{ route('inventory.product_edit_page',$detail->inv_pro_det_id) }}" class="action_btn btn_edit">
                                      <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                        <!-- /.box-body -->
                      </div>
                     </section>
@endsection
@section('custom_style')
        <style>
            .action_btn{
                border: 1px solid;
                padding: 5px;
            }
            .btn_show{
                background-color: green;
                color: white;
            }
            .btn_delete{
                background-color: red;
                color: white;
            }
            .btn_edit{
                background-color: cornflowerblue;
                color: white;
            }
        </style>
        @endsection
        @section('custom_script')
    <script type="text/javascript">
      function deleteprocat(){
        let clickDel = confirm("Are you sure want to delete this?");
        if (clickDel == true) {
          return true;
        }else{
          return false;
        }
      }
    </script>
    @endsection