@php
	$totalCredit=0;
	$totalDebit=0;
	$sl=0;
	$totalPrice=0;
	$totalUnitPrice=0;
	$totalQuantaty=0;
@endphp
	<div class="row">
		<div class="col-sm-12">
			<b>Invoice No: </b> {{request()->invoice_id}} <br>
			<b>Issue Date: </b> {{App\Inv_product_inventory::getIssueDateByInvoice(request()->invoice_id)->inv_pro_inv_issue_date}}
		</div>
	</div>

	<div class="row" style="margin-top: 20px;">
		<div class="col-sm-12">

			@if($isProductTrans)

			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="text-align:center;">SL</th>

						<th style="text-align:center;">Description</th>
						<th style="text-align:center;">Product</th>
					
						<th style="text-align:center;">Quantaty</th>
						<th style="text-align:center;">Unit Price</th>
						<th style="text-align:center;">Price</th>


					</tr>
				</thead>
				<tbody>
				
					@foreach($invoiceInfos as $invoiceInfo)

					@php
						$totalQuantaty+=$invoiceInfo->inv_pro_inv_qty;
						$totalPrice+=$invoiceInfo->inv_pro_inv_qty*$invoiceInfo->inv_pro_inv_unit_price;
						$totalUnitPrice+=$invoiceInfo->inv_pro_inv_unit_price;
					@endphp
					<tr>
						<td style="text-align: center;">
							{{ ++$sl }}
						</td>

						<td style="text-align: center;"> 
							{{$invoiceInfo->inv_pro_inv_tran_desc}}
						</td>
						<td style="text-align: right;">
							{{$invoiceInfo->getProductInfo['inv_pro_det_pro_name']}}
						</td>
						<td style="text-align: right;">
							{{$invoiceInfo->inv_pro_inv_qty}}
						</td>
						<td style="text-align: right;">
							{{ number_format($invoiceInfo->inv_pro_inv_unit_price,2) }}
						</td>
						<td style="text-align: right;">
							{{ number_format($invoiceInfo->inv_pro_inv_qty*$invoiceInfo->inv_pro_inv_unit_price,2) }}
						</td>

					</tr>
					@endforeach

				</tbody>
				<tfoot>
					<tr>
						<td style="text-align: right;font-weight: bolder;" colspan="3">Total: </td>
						<td style="text-align: right;font-weight: bolder;">{{$totalQuantaty}}</td>
						<td style="text-align: right;font-weight: bolder;">{{$totalUnitPrice}}</td>
						<td style="text-align: right;font-weight: bolder;">{{$totalPrice}}</td>
					</tr>
				</tfoot>

			</table>
			@else

			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="text-align:center;">SL</th>

						<th style="text-align:center;">Description</th>
						<th style="text-align:center;">Credit</th>
						<th style="text-align:center;">Debit</th>
						<th style="text-align:center;">Balance</th>


					</tr>
				</thead>
				<tbody>
				
					@foreach($invoiceInfos as $invoiceInfo)

					@php
						$totalCredit+=$invoiceInfo->inv_pro_inv_credit;
						$totalDebit+=$invoiceInfo->inv_pro_inv_debit;
					@endphp
					<tr>
						<td style="text-align: center;">
							{{ ++$sl }}
						</td>

						<td style="text-align: center;"> 
							{{$invoiceInfo->inv_pro_inv_tran_desc}}
						</td>
						<td style="text-align: right;">
							{{ number_format($invoiceInfo->inv_pro_inv_credit,2) }}
						</td>
						<td style="text-align: right;">
							{{ number_format($invoiceInfo->inv_pro_inv_debit,2) }}
						</td>
						<td style="text-align: right;">
							{{ number_format(($invoiceInfo->inv_pro_inv_credit) - ($invoiceInfo->inv_pro_inv_debit),2) }}
						</td>

					</tr>
					@endforeach

				</tbody>
				<tfoot>
					<tr>
						<td style="text-align: right;font-weight: bolder;" colspan="2">Total: </td>
						<td style="text-align: right;font-weight: bolder;">{{ number_format($totalCredit,2)}} </td>
						<td style="text-align: right;font-weight: bolder;">{{number_format($totalDebit,2)}} </td>
						<td style="text-align: right;font-weight: bolder;">{{ number_format($totalCredit-$totalDebit,2) }} </td>
					</tr>
				</tfoot>

			</table>
			@endif
		</div>
	</div>