@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://app.congeladosbrisamar.es/logo-icono-letras-horizontal.svg" class="logo" alt="Brisamar Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
