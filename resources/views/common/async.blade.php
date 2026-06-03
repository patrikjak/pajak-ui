<div {{ $attributes->merge(['class' => 'pajak-async']) }}
     data-pajak-async
     data-url="{{ $url }}"
     data-spinner-size="{{ $size->value }}">
    @if($skeleton->isNotEmpty())
        <div class="pajak-async__skeleton" aria-hidden="true">{{ $skeleton }}</div>
    @endif
    <div class="pajak-async__overlay" aria-hidden="true">
        <x-pajak::spinner :size="$size" />
        <span class="pajak-async__label">{{ $label }}</span>
    </div>
    <div class="pajak-async__content">{{ $slot }}</div>
</div>
