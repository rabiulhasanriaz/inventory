@if($bankBalance>=500)
<span style="color: green; font-weight: bold;">
	Available Balance is {{$bankBalance}} Tk
</span>
@else
<span style="color: red; font-weight: bold;">
	Available Balance is {{$bankBalance}} Tk
</span>
@endif