@use('Pajak\Ui\Common\Enums\AccordionVariant')

<div {{ $attributes->merge(['class' => 'pajak-acc'])->class(["pajak-acc--$variant->value" => $variant !== AccordionVariant::Default]) }} data-pajak-accordion="{{ $mode->value }}">
    {{ $slot }}
</div>
