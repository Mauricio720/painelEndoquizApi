<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Endoquiz')
<img src="{{asset('storage/general_icons/Endoquiz.png')}}" class="logo" alt="Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
