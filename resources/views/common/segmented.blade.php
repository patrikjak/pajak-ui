@use('Pajak\Ui\Common\Enums\Segmented\SegmentedVariant')
@use('Pajak\Ui\Common\Enums\Size')

<div {{ $attributes->merge(['class' => 'pajak-seg'])->class([
    "pajak-seg--$size->value" => $size !== Size::Md,
    "pajak-seg--$variant->value" => $variant !== SegmentedVariant::Default,
    'pajak-seg--full' => $full,
]) }} role="tablist" data-pajak-segmented>
    {{ $slot }}
</div>
