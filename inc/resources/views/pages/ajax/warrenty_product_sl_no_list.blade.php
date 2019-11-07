@php($pro_sl_nl = $content->options->pro_sl_nl)
@foreach ($pro_sl_nl as $sl_no)
    <li class="list-group-item">{{ $loop->iteration }} | {{ $sl_no }} | 
        <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="remove_product_sl('{{ $content->id }}', '{{ $sl_no }}')"><span class="fa fa-remove" style="font-size: 20px; color: red;"></span></a>
    </li>
@endforeach
