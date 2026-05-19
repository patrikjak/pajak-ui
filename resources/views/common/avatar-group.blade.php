<div {{ $attributes->merge(['class' => 'pajak-avatar-group']) }}>
    {{ $slot }}
    @if($overflow > 0)
        <span class="pajak-avatar pajak-avatar--{{ $size->value }} pajak-avatar--overflow">+{{ $overflow }}</span>
    @endif
</div>
