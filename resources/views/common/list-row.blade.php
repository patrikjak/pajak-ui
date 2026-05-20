<div {{ $attributes->merge(['class' => 'pajak-list__row'])->class(['pajak-list__row--clickable' => $clickable]) }}>
    @isset($leading)
        <div class="pajak-list__leading">{{ $leading }}</div>
    @endisset
    <div class="pajak-list__body">
        @isset($title)
            <div class="pajak-list__title">{{ $title }}</div>
        @endisset
        @isset($subtitle)
            <div class="pajak-list__subtitle">{{ $subtitle }}</div>
        @endisset
        {{ $slot }}
    </div>
    @isset($trailing)
        <div class="pajak-list__trailing">{{ $trailing }}</div>
    @endisset
</div>
