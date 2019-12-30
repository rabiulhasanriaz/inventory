@php($total = 0)
@php($sl = 0)
@foreach($cart_content as $content)

    <tr>
        <td>{{ ++$sl }}</td>
        <td>
            {{ $content->pro_warranty['inv_pro_det_pro_description'] }}, {{ $content->inv_pro_temp_type_name }}
            <br>
            <b>{{ implode(', ', explode(',',$content->inv_pro_temp_slno)) }}</b>
        </td>
        <td align="center">
            @if ($content->inv_pro_temp_qty < 10)
              0{{ $content->inv_pro_temp_qty }}
            @else
            {{ $content->inv_pro_temp_qty }}
            @endif
        </td>
        <td class="text-right">{{ number_format($content->inv_pro_temp_unit_price, 2) }}</td>
        @php($amount = $content->inv_pro_temp_unit_price * $content->inv_pro_temp_qty)
        <td class="text-right temp_cart">{{ number_format($amount,2) }}</td>
        <td class="text-center">
            <a href="javascript:void(0);" onclick="remove_cart({{ $content->inv_pro_temp_pro_id }})" class="btn btn-sm btn-danger">
                <i class="fa fa-minus"></i>
            </a>
        </td>
    </tr>
    @php($total += $amount)
@endforeach
<tr>
    <td colspan="4" class="text-right">Discount:</td>
    <td>
        <input type="number" onkeyup="total_sell_amount()" placeholder="Discount" name="discount" class="form-control text-right temp_cart_discount" style="width: 140px; float:right;">
    </td>
    <td></td>
</tr>
<tr>
    <td colspan="4" class="text-right">Delivery Charges:</td>
    <td>
        <input type="number" onkeyup="total_sell_amount()" placeholder="Delivery Charges" name="delivery" class="form-control text-right temp_cart_delivery" style="width: 140px; float:right;">
    </td>
    <td></td>
</tr>
{{-- <tr>
    <td colspan="3" class="text-right">Total Amount:</td>
    <td>
        <input type="number" class="form-control text-right" value="{{ $total }}" style="width: 140px; float:right;" disabled>
    </td>
    <td></td>
</tr> --}}