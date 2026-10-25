<a {{ $attributes->merge(['class' => 'pajak-action-tile', 'href' => $href]) }}>
    @isset($icon)
        <span class="pajak-action-tile__icon pajak-action-tile__icon--{{ $color->value }}" aria-hidden="true">{{ $icon }}</span>
    @endisset

    <span class="pajak-action-tile__body">
        <span class="pajak-action-tile__title">{{ $title }}</span>
        @if(filled($description))
            <span class="pajak-action-tile__description">{{ $description }}</span>
        @endif
    </span>
</a>
