@extends('layout.master')
@section('inventory_class','menu-open')
@section('product_class','menu-open')
@section('inv_pro_class','menu-open')
@section('pro_list','active')
@section('content')
<section class="content">
        <section class="content-header">
              <h1>
                Product Details
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Update Product Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {{Form::open(['action' => ['Inventory\InvProductController@pro_edit',$product->inv_pro_det_id], 'method' => 'post' , 'class' => 'form-horizontal', 'id' => 'customer_create_form'])}}
                      <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"> Group</label>
                            <div class="col-sm-6">
                                <select name="group" class="form-control select2" id="product_category" required>
                                    <option value="">Select</option>
                                    @foreach ($pro_det_grp as $grp)
                                    <option {{ ($grp->inv_pro_grp_id==$product->type_info['inv_pro_type_grp_id'])?'selected':'' }} value="{{ $grp->inv_pro_grp_id }}">
                                        {{ $grp->inv_pro_grp_name }}
                                    </option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Type Name
                                    <b class="text-danger" >*</b>
                                </label>
                                <div class="col-sm-6 product_category_wrapper">
                                    <select name="type" class="form-control select2" id="product_model" required>
                                        <option value="">Select</option>
                                        @foreach ($types as $type)
                                        <option {{ ($type->inv_pro_type_id==$product->inv_pro_det_type_id)?'selected':'' }}  value="{{ $type->inv_pro_type_id }}">
                                            {{ $type->inv_pro_type_name }}
                                        </option> 
                                        @endforeach
                                    </select>
                                </div>
                                    @if($errors->has('type'))
                                        <p class="text-danger">{{ $errors->first('type') }}</p>
                                    @endif
                        </div>
                        @if (App\Inv_product_detail::suppliers_info($product->inv_pro_det_id) == null)
                        <div class="form-group supplier_list">
                          <label for="inputEmail3" class="col-sm-2 control-label">Supplier
                              <b class="text-danger" ></b>
                          </label>
                          <div class="col-sm-6" id="select_div">
                              <select name="supplier[]" class="form-control select2" id="select" required>
                                  <option value="">Select</option>                          
                                  @foreach($suppliers as $supplier)           
                                    <option value="{{ $supplier->inv_sup_id }}">
                                      {{ $supplier->inv_sup_com_name }}
                                    </option>
                                  @endforeach
                              </select>
                              
                          </div>
                          <button type="button" class="btn btn-success btn-sm add_new_supplier_btn"><big>+</big></button>
                      </div>
                        @else
                        @foreach (App\Inv_product_detail::suppliers_info($product->inv_pro_det_id) as $si)
                        <div class="form-group supplier_list">
                          <label for="inputEmail3" class="col-sm-2 control-label">Supplier
                              <b class="text-danger" ></b>
                          </label>
                          <div class="col-sm-6" id="select_div">
                              <select name="supplier[]" class="form-control select2" id="select" required>
                                  <option value="">Select</option>                          
                                  @foreach($suppliers as $supplier)           
                                    <option value="{{ $supplier->inv_sup_id }}" {{ ($supplier->inv_sup_id==$si->inv_sup_id)?'selected':'' }}>
                                      {{ $supplier->inv_sup_com_name }}
                                    </option>
                                  @endforeach
                              </select>
                              
                          </div>
                          @if ($loop->first)
                          <button type="button" class="btn btn-success btn-sm add_new_supplier_btn"><big>+</big></button>
                          @else 
                          <button type="button" onclick="remove_supplier(this)" class="btn btn-danger btn-sm remove_a_supplier_btn"><big>X</big></button> 
                          @endif
                      </div>
                        @endforeach
                            
                        @endif
                        
                        <div id="others-supplier"></div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"> Product Name
                              <b class="text-danger">*</b>
                          </label>
                          <div class="col-sm-6">
                            <input type="text" name="pro_name" autocomplete="off" value="{{ $product->inv_pro_det_pro_name }}" class="form-control" id="inputEmail3" placeholder="Enter Product Model Name" required>
                          </div>
                            @if($errors->has('pro_name'))
                                <p class="text-danger">{{ $errors->first('pro_name') }}</p>
                            @endif
                        </div>
                        <div id="others-supplier"></div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"> Product Buy Price
                              <b class="text-danger">*</b>
                          </label>
                          <div class="col-sm-6">
                            <input type="text" name="pro_buy" autocomplete="off" value="{{ $product->inv_pro_det_buy_price }}" class="form-control" id="inputEmail3" placeholder="Enter Product Model Name" required>
                          </div>
                            @if($errors->has('pro_buy'))
                                <p class="text-danger">{{ $errors->first('pro_buy') }}</p>
                            @endif
                        </div>
                        <div id="others-supplier"></div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"> Product Sell Price
                              <b class="text-danger">*</b>
                          </label>
                          <div class="col-sm-6">
                            <input type="text" name="pro_sell" autocomplete="off" value="{{ $product->inv_pro_det_sell_price }}" class="form-control" id="inputEmail3" placeholder="Enter Product Model Name" required>
                          </div>
                            @if($errors->has('pro_sell'))
                                <p class="text-danger">{{ $errors->first('pro_sell') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Product Description</label>
                                <div class="col-sm-6">
                                  <input type="text" name="pro_desc" autocomplete="off" value="{{ $product->inv_pro_det_pro_description }}" class="form-control" id="inputEmail3" placeholder="Enter Product Model Name" required>
                                </div> 
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Short Quantity</label>
                          <div class="col-sm-6">
                            <input type="text" name="pro_short" autocomplete="off" value="{{ $product->inv_pro_det_short_qty }}" class="form-control" id="inputEmail3" placeholder="Enter Product Short Quantity" required>
                          </div> 
                  </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="" class="btn btn-info pull-right">UPDATE</button>
                         </div>
                      </div>
                      <!-- /.box-footer -->
                    {{ Form::close() }}
                  </div>


          <div class="hidden" id="supplier-list-container">
              <div class="form-group supplier_list">
                <label for="inputEmail3" class="col-sm-2 control-label">Supplier
                    <b class="text-danger" ></b>
                </label>
                <div class="col-sm-6 " id="select_div">
                    <select name="supplier[]" class="form-control " id="" required>
                        <option value="">Select</option>   
                        @foreach($suppliers as $supplier)           
                          <option value="{{ $supplier->inv_sup_id }}">{{ $supplier->inv_sup_com_name }}</option>
                        @endforeach                                 
                    </select>
                    
                </div>
                <button type="button" onclick="remove_supplier(this)" class="btn btn-danger btn-sm remove_a_supplier_btn"><big>X</big></button>
              </div>
              
            </div>
                 </section>
@endsection
@section('custom_script')
<script>
    $(document).ready(function(){
    $('#product_category').on("change",function(){
      let grp_id = $("#product_category").val();
      let link = "{{ route('inventory.product_submit_ajax') }}";
      $.ajax({
        type: "GET",
        url: link,
        data: { grp_id: grp_id},
        success: function (result) {
            console.log(result);
          $(".product_category_wrapper").html(result);
        }
      });
    });
  });

  $(".add_new_supplier_btn").click(function () {
  let new_supplier = $("#supplier-list-container").html();
  $("#others-supplier").append(new_supplier);
  });

  function remove_supplier(btn) {
    $(btn).parent().remove();
  }
</script>
@endsection