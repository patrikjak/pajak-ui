@use('Pajak\Ui\Common\Enums\Dialog\DialogType')

<dialog {{ $attributes->merge(['class' => 'pajak-dialog', 'id' => $id]) }} @if($open) open @endif data-pajak-dialog>
    @isset($icon)
        <div @class(['pajak-dialog__icon', "pajak-dialog__icon--$type->value"])>
            {{ $icon }}
        </div>
    @else
        <div @class(['pajak-dialog__icon', "pajak-dialog__icon--$type->value"])>
            @if($type === DialogType::Info)
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            @elseif($type === DialogType::Success)
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            @elseif($type === DialogType::Warning)
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            @elseif($type === DialogType::Danger)
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            @endif
        </div>
    @endisset

    @isset($title)
        <h3 class="pajak-dialog__title">{{ $title }}</h3>
    @endisset

    @isset($description)
        <p class="pajak-dialog__description">{{ $description }}</p>
    @endisset

    {{ $slot }}

    @isset($actions)
        <div @class(['pajak-dialog__actions', 'pajak-dialog__actions--stacked' => $stacked])>
            {{ $actions }}
        </div>
    @endisset
</dialog>
