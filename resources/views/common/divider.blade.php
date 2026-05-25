@use('Pajak\Ui\Common\Enums\Divider\DividerOrientation')
@use('Pajak\Ui\Common\Enums\Divider\DividerStyle')

@if($labeled)
    <div {{ $attributes->merge(['class' => 'pajak-divider pajak-divider--labeled']) }}>{{ $slot }}</div>
@elseif($orientation === DividerOrientation::Vertical)
    <span {{ $attributes->merge(['class' => 'pajak-divider pajak-divider--vertical']) }}></span>
@else
    <hr {{ $attributes->merge(['class' => 'pajak-divider'])->class([
        "pajak-divider--$strength->value" => $style === DividerStyle::Solid,
        "pajak-divider--$style->value" => $style !== DividerStyle::Solid,
    ]) }}>
@endif
