@props(['url'])

<tr style="display: none;">
    <td>
        {{ config('company.name') }}
    </td>
</tr>

<tr>
    <td class="header">
        <a href="{{ $url ?? config('company.website_url') }}" style="display: inline-block;">
            @if (trim($slot) === 'Congelados_Brisamar_App')
                <img src="{{ config('company.logo_url_small') }}" class="logo" alt="Logo {{ config('company.name') }}">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>