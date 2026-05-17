<div {{ $attributes->merge(['class' => 'pajak-form-section']) }}>
    @if(isset($title) || isset($description))
        <div class="pajak-form-section__meta">
            @isset($title)
                <h3 class="pajak-form-section__title">{{ $title }}</h3>
            @endisset
            @isset($description)
                <p class="pajak-form-section__description">{{ $description }}</p>
            @endisset
        </div>
    @endif

    <div class="pajak-form-section__body">
        {{ $slot }}
    </div>
</div>
