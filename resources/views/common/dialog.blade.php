@use('Pajak\Ui\Common\Enums\Dialog\DialogType')

<dialog {{ $attributes->merge(['class' => 'pajak-dialog', 'id' => $id]) }} @if($open) open @endif data-pajak-dialog>
    @isset($icon)
        <div @class(['pajak-dialog__icon', "pajak-dialog__icon--$type->value"])>
            {{ $icon }}
        </div>
    @else
        <div @class(['pajak-dialog__icon', "pajak-dialog__icon--$type->value"])>
            @if($type === DialogType::Info)
                <x-heroicon-o-information-circle width="24" height="24" aria-hidden="true" />
            @elseif($type === DialogType::Success)
                <x-heroicon-o-check width="24" height="24" aria-hidden="true" />
            @elseif($type === DialogType::Warning)
                <x-heroicon-o-exclamation-circle width="24" height="24" aria-hidden="true" />
            @elseif($type === DialogType::Danger)
                <x-heroicon-o-exclamation-triangle width="24" height="24" aria-hidden="true" />
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
