@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="/resources/views/vendor/mail/images/logo-brisamar.svg" class="logo" alt="Brisamar Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
