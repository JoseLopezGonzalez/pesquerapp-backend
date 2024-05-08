@props(['url'])
<tr>
    <td style="display:none">
        Congelados Brisamar S.L.
    </td>

<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Congelados_Brisamar_App')
<img src="https://congeladosbrisamar.es/logos/logo-brisamar-small.png" class="logo" alt="Brisamar Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
