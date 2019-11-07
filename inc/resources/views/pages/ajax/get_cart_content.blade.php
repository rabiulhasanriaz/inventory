@foreach($cart_content as $content)
    <tr>
        <td>
            {{ $content->name }}, {{ App\Inv_product_detail::get_type_name($content->id) }}
            <br>
            <b>{{ $content->options->has('pro_sl_nl') ? implode(',', $content->options->pro_sl_nl) : '' }}</b>
        </td>
        <td>
        {{--<input type="text" class="form-control" style="width: 80px;" value="{{ $content->qty }}" id="update_qty_{{ $content->id }}">--}}
        {{ $content->qty }}
        </td>
        <td class="text-right">{{ $content->price }}</td>
        <td>{{ $content->subtotal }}</td>
        <td>
            <a href="javascript:void(0);" onclick="remove_cart({{ $content->id }})" class="btn btn-sm btn-danger">-</a>
        </td>
    </tr>
@endforeach