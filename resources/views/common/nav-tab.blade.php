<div {{ $attributes->merge(['class' => 'pajak-tabbar__tab'])->class(['is-active' => $active]) }} role="tab" @if($active) aria-selected="true" @else aria-selected="false" @endif>
    @isset($icon)
        <span class="pajak-tabbar__icon">
            {{ $icon }}

            @if($dot)
                <span class="pajak-tabbar__dot" aria-hidden="true"></span>
            @endif
        </span>
    @endisset

    <span class="pajak-tabbar__label">{{ $label }}</span>
</div>
