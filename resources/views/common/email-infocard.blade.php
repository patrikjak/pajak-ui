<div {{ $attributes->merge(['class' => 'pajak-email-infocard']) }}>
    @isset($title)
        <div class="pajak-email-infocard__title">{{ $title }}</div>
    @endisset

    {{ $slot }}
</div>
