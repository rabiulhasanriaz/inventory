@if($supplierBalance>=500)
<span style="color: green; font-weight: bold;">
	Available Balance is {{$supplierBalance}} Tk
</span>
@else
<span style="color: red; font-weight: bold;">
	Available Balance is {{$supplierBalance}} Tk
</span>
@endif