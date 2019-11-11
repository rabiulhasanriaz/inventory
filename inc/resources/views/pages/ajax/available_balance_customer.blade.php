@if($customerBalance>=500)
<span style="color: green; font-weight: bold;">
	Available Balance is {{$customerBalance}} Tk
</span>
@else
<span style="color: red; font-weight: bold;">
	Available Balance is {{$customerBalance}} Tk
</span>
@endif