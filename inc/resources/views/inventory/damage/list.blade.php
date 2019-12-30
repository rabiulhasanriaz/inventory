@extends('layout.master')
@section('inventory_class','menu-open')
@section('damage_class','menu-open')
@section('damage_list','active')
@section('content')
<section class="content">
            <section class="content-header">
                  <h1>
                    Damage
                  </h1>
                </section>
                <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">Damage List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                              <th>SL</th>
                              <th>Type Name</th>
                              <th>Product Name</th>
                              <th>Damage Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($sl=0)
                            @foreach ($damage_list as $damage)
                            <tr>
                                <td>{{ ++$sl }}</td>
                                <td>{{ $damage->getProductWarranty->type_info['inv_pro_type_name'] }}</td>
                                <td>{{ $damage->getProductWarranty['inv_pro_det_pro_name'] }}</td>
                                <td class="text-center">{{ $damage->inv_pro_inv_total_qty }}</td>
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