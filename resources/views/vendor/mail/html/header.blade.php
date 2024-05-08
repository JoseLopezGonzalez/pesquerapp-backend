@props(['url'])
<tr style="display:none">
<td >
    Congelados Brisamar S.L.
</td>
</tr>
<tr>
   

<td class="header">
<a href="https://congeladosbrisamar.es" style="display: inline-block;">
@if (trim($slot) === 'Congelados_Brisamar_App')
<img src="https://congeladosbrisamar.es/logos/logo-brisamar-small.png" class="logo" alt="Brisamar Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
