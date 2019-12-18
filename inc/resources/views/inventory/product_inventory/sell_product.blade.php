@extends('layout.master')
@section('inventory_class','menu-open')
@section('pro_inv_class','menu-open')
@section('sell_pro','active')
@section('content')
<section class="content">
    <section class="content-header">
        @if(session()->has('sub_success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('sub_success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        @if(session()->has('sub_err'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('sub_err') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
          <h1>
            Sell
          </h1>
        </section>
                <!-- /.box-header -->
                <!-- form start -->
                {{ Form::open(['action' => 'Inventory\InventoryCartController@invTemporaryProduct' , 'method' => 'get' , 'class' => 'form-horizontal']) }}
                <div class="box">
                 <div class="box-header with-border">
                   <h3 class="box-title">Add Product</h3>
                 </div>
                 <!-- /.box-header -->
                 <!-- form start -->
                 <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 text-right control-label">Customer :</label>
                        <div class="col-sm-6">
                            <select name="customer" id="" class="form-control select2" style="width: 299px;" required>
                                <option value="">Select One</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->inv_cus_id }}">
                                    {{ $customer->inv_cus_name }} ({{ $customer->inv_cus_com_name }})
                                </option>  
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="box-body">
                <div class="col-sm-12 text-center">
                    
                <div class="col-sm-5">
                        <div class="form-group">
                                <label for="inputEmail3" style="margin-right:80px;" class="col-sm-2 control-label">Group:</label>
                                <div class="col-sm-5">
                                    <select name="group" style="width:200px;" id="product_category" class="form-control select2">
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                        <option value="{{ $group->inv_pro_grp_id }}">
                                            {{ $group->inv_pro_grp_name }} 
                                        </option>  
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                </div>
                <div class="col-sm-5">
                        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Type:</label>
                                <div class="col-sm-5 product_category_wrapper">
                                    <select name="type" style="width:200px;" id="product_model" class="form-control select2">
                                        <option value="">Select Type First</option>
                                        
                                    </select>
                                </div>
                        </div>
                </div>
                <div class="col-xs-2">
                    <button type="button" id="pro_search"  class="btn btn-info" name="searchbtn">Search</button>
                </div>
                </div>
                <div class="col-sm-12">
                        <div class="col-sm-6">
                                <div class="form-group">
                                        <label for="inputEmail3" style="width:150px;" class="col-sm-3 text-right control-label">Service Charge:</label>
                                        <div class="col-sm-3 product_category_wrapper">
                                            <input type="text" style="width:200px;" name="service" class="form-control" placeholder="Service Charges">
                                        </div>
                                </div>
                            </div>
                            <div class="col-sm-6" style="margin-left:-130px;">
                                    <div class="form-group">
                                            <label for="inputEmail3" style="width:150px;" class="col-sm-3 control-label">Delivery Charge:</label>
                                            <div class="col-sm-3 product_category_wrapper">
                                                <input type="text" style="width:200px;" name="delivery" class="form-control" placeholder="Delivery Charges">
                                            </div>
                                    </div>
                            </div>
                </div>
                </div>
            <div class="box-body">
            <div class="col-sm-12 product_wrapper">
                    <table id="sell_product_list_table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>A. Stock</th>
                                <th>Price</th>
                                <th style="width: 110px; text-align: center;">Sell</th>
                            </tr>
                            </thead>
                            <tbody id="product_table_body">
                            @php($sl=0)
                            @foreach ($sell_pro as $sell)
                                <tr>
                                    <td class="text-center">{{ ++$sl }}</td>
                                    <td>{{ $sell->inv_pro_det_pro_name }}</td>
                                    <td>{{ $sell->type_info['inv_pro_type_name'] }}</td>
                                    <td align="center">{{ $sell->inv_pro_det_available_qty }}</td>
                                    <td class="text-center"><input type="text" autocomplete="off" class="form-control" id="pro_price_{{ $sell->inv_pro_det_id }}" style="width: 100px;" value="{{ $sell->inv_pro_det_sell_price }}"></td>
                                    <td class="text-center">
                                        @if($sell->inv_pro_det_pro_warranty == 0)
                                        <input type="text" autocomplete="off" class="form-control" style="width: 50px;" id="pro_qty_{{ $sell->inv_pro_det_id }}" placeholder="Qty">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addtocart('{{ $sell->inv_pro_det_id }}')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        @else
                                        <input type="text" autocomplete="off" class="form-control" style="width: 50px;" placeholder="N/A" disabled>
                                        <button type="button" class="btn btn-success btn-sm" onclick="addWarrentyProduct('{{ $sell->inv_pro_det_id }}')">
                                                <i class="fa fa-plus"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                  </div>
                  <div class="box-body">

                    <div class="col-sm-12">
                                <div class="box shopping_cart">
                                        <div class="box-header with-border">
                                                <h3 class="box-title"><i class="fa fa-shopping-cart"></i>
                                                    Sales Invoice
                                                </h3>
                                                </div>   
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="show-cart-conten">
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-success btn-sm pull-right">Submit</button>
                            {{ Form::close() }}
                        </div>
                        </div>
              </div>
             </section>

             @include('pages.ajax.warrenty_product_get_sl_no');

             
@endsection

@section('custom_style')
<style>
  .form-control::-webkit-inner-spin-button,
  .form-control::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}
    .all-product-sl-no-main-wrapper {
        border: 2px solid #ddd;
        padding: 7px;
        height: 350px;
        overflow: scroll;
        position: absolute;
    }
    .product-warrenty-sl {
        margin-top: 5px;
    }
    .table>tbody>tr>td{
        padding: 2px;
    }
    .shopping_cart{
        height: 300px;
        border: 1px solid grey;
        overflow: scroll;
    }
    input {
        height: 27px !important;
    }
    .btn-sm {
        padding: 3px 8px;
    }
</style>
@endsection
@section('custom_script')
<script>

        $(document).ready(function(){
            $('#product_category').on("change",function(){
            let grp_id = $("#product_category").val();
            let link = "{{ route('buy.type_submit_ajax') }}";
            $.ajax({
                type: "GET",
                url: link,
                data: { grp_id: grp_id},
                success: function (result) {
                $(".product_category_wrapper").html(result);
                }
            });
            });

            $('#pro_search').on("click",function(){
                let type_id = $("#product_model").val();
                let link = "{{ route('buy.product-search') }}";
                $.ajax({
                    type: "GET",
                    url: link,
                    data: { type_id: type_id},
                    success: function (result) {
                    $(".product_wrapper").html(result);
                    }
                });
            });
        });

    $(document).ready(function(){

    @if(session()->has('print_invoice'))
    let sell_print = "{!! route('reports.sell-print', session()->get('print_invoice')) !!}";     
        let newTab = window.open(sell_print, '_blank');
        newTab.location.href = url
    @endif

    $( "#from" ).datepicker({
        daysOfWeekHighlighted: "7",
            todayHighlight: true,
            autoclose: true,
        });
    $( "#to" ).datepicker({
        daysOfWeekHighlighted: "7",
            todayHighlight: true,
        });

        var table = $('#sell_product_list_table').DataTable( {
            pageLength : 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
        } );
    });

    $(document).ready(function(){
        getCartProduct();
        $( "#from2" ).datepicker({
            daysOfWeekHighlighted: "7",
            todayHighlight: true,
            autoclose: true,
        });
        $( "#to" ).datepicker({
            daysOfWeekHighlighted: "7",
            todayHighlight: true,
        });
        
    });

    function addtocart(pro_det_id) {
        let pro_qty = parseFloat($("#pro_qty_"+pro_det_id).val());
        let pro_price = parseFloat($("#pro_price_"+pro_det_id).val());
        if(isNaN(pro_qty) || isNaN(pro_price)) {
            alert("quantity field can\'t be empty");
        } else if(pro_qty < 1) {
            alert("minimum quantity at least 1");
        } else {

            let route_url = "{{ route('buy.add-to-cart') }}";
            $.ajax({
                type: "GET",
                url: route_url,
                data: { pro_id: pro_det_id, pro_qty: pro_qty, pro_price: pro_price},
                success: function (result) {
                    if(result.status == 400) {
                        alert("Stock has been cross it's limit");
                    } else {
                        getCartProduct();
                    }
                }
            });
        }
    }

    function getCartProduct() {
        let route_url = "{{ route('buy.get-cart') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: {},
            success: function (result) {
                console.log("Riaz");
                $("#show-cart-conten").html(result);
            }
        });
    }

    function update_cart_quantity(content_id) {
        let pro_quantity = parseFloat($("#update_qty_"+content_id).val());
        if(pro_quantity < 1) {
            alert("Minimum Quantity is 1");
            return false;
        }
        let route_url = "{{ route('buy.update-cart') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: {content_id:content_id, pro_qty:pro_quantity},
            success: function (result) {
                getCartProduct();
            }
        });
    }

    function remove_cart(content_id) {
        let clickDel = confirm("Are you sure want to delete this?");
        if (clickDel == true) {
            
        }else{
            return false;
        }
        let route_url = "{{ route('buy.remove-cart') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: {content_id:content_id},
            success: function (result) {
                getCartProduct();
            }
        });
    }

    function addWarrentyProduct (pro_det_id) {
        let pro_price = parseFloat($("#pro_price_"+pro_det_id).val());
        
        let route_url = "{{ route('buy.add-to-cart-warrenty-product') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: { pro_id: pro_det_id, pro_price: pro_price},
            success: function (result) {
                // console.log(result);
                // return false;
                if(result.status == 404) {
                    alert("Invalid Warrenty Product");
                } else if(result.status == 406) {
                    alert("Invalid Warrenty Product");
                } else {
                    $("#warrenty_product_get_sl_no .modal-content").html(result);
                    $("#warrenty_product_get_sl_no").modal('show');
                }
            }
        });
        
    }


    function check_sl_no(value, pro_det_id) {
        let sl_no = value;
        let route_url = "{{ route('buy.add-warrenty-product-sl-no') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: { pro_id: pro_det_id, sl_no: sl_no},
            success: function (result) {
                
                if(result.status == 404) {
                    alert("Invalid Warrenty Product");
                } else if(result.status == 402) {
                    alert("Already Added");
                } else {
                    $("#all-added-warrenty-product-id ul").html(result);
                    $("#w_product_sl_scan_inp").val("");
                    getCartProduct();
                }
            }
        });
    }


    function remove_product_sl(product_id, sl_no) {

        let route_url = "{{ route('buy.remove-warrenty-product-sl') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: { pro_id: product_id, sl_no: sl_no},
            success: function (result) {
                if(result.status == 404) {
                    alert("Invalid Warrenty Product");
                } else if(result.status == 406) {
                    alert("Invalid Warrenty Product");
                } else {
                    $("#all-added-warrenty-product-id ul").html(result);
                    getCartProduct();
                }
            }
        });
    }
    
</script>
@endsection