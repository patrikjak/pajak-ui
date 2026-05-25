@use('Pajak\Ui\Common\Enums\AvatarStatus')

<span {{ $attributes->merge(['class' => 'pajak-avatar'])->class([
    "pajak-avatar--$size->value",
    "pajak-avatar--$color->value",
    'pajak-avatar--ring' => $ring,
]) }}>
    {{ $initials }}
    @isset($status)
        <span class="pajak-avatar__dot pajak-avatar__dot--{{ $status->value }}"></span>
    @endisset
</span>
