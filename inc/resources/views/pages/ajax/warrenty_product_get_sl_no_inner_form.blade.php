
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">{{ $product->inv_pro_det_pro_name }}</h4>
</div>
<div class="modal-body">
    <div class="box">
        <div class="box-body">
                <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"> SL No</label>
                        <div class="col-sm-6">
                            <input type="text" name="pro_warranty" id="w_product_sl_scan_inp" onchange="check_sl_no(this.value, '{{ $product->inv_pro_det_id }}')" class="form-control">
                        </div>
                </div>
                <div class="col-sm-offset-2 col-sm-6">
                    <div id="all-added-warrenty-product-id">
                        <div class="col-sm-12" style="margin-top: 5px;">
                            <ul class="list-group list-inline">
                                @if(!empty($product_sl_no)) 
                                    @foreach ($product_sl_no as $sl_no)
                                        <li class="list-group-item">{{ $loop->iteration }} | {{ $sl_no }} | 
                                        <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="remove_product_sl('{{ $product->inv_pro_det_id }}', '{{ $sl_no }}')"><span class="fa fa-remove" style="font-size: 20px; color: red;"></span></a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>