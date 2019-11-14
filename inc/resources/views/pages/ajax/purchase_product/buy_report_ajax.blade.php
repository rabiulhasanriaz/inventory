<div class="load-details">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Warranty</th>
                    <th>Short Qty</th>
                    <th>Total Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            @php($sl=0)
            @php($balance=0)
            <tbody>
                @foreach ($detail_buy as $buy)
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ $buy->getProductWarranty['inv_pro_det_pro_name'] }}</td>
                    <td>{{ $buy->inv_pro_inv_tran_desc }}</td>
                    <td>
                        @if ($buy->getProductWarranty['inv_pro_det_pro_warranty'] == 0)
                            No Warranty
                        @else
                            {{ $buy->getProductWarranty['inv_pro_det_pro_warranty'] }} Days
                        @endif
                    </td>
                    <td>{{ $buy->inv_pro_inv_short_qty }}</td>
                    <td>{{ $buy->inv_pro_inv_total_qty }}</td>
                    <td class="text-right">{{ number_format($buy->inv_pro_inv_unit_price,2) }}</td>
                    <td class="text-right">{{ number_format($buy->inv_pro_inv_debit,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>