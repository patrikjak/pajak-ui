<div {{ $attributes->merge(['class' => 'pajak-email-infocard']) }} style="background: #F7F9FF; border: 1px solid #E2E8F4;">
    @isset($title)
        <div class="pajak-email-infocard__title">{{ $title }}</div>
    @endisset

    {{ $slot }}
</div>
