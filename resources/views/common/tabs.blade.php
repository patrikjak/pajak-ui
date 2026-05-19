@use('Pajak\Ui\Common\Enums\Tabs\TabsVariant')

<div {{ $attributes->merge(['class' => 'pajak-tabs'])->class(["pajak-tabs--$variant->value" => $variant !== TabsVariant::Underline]) }} role="tablist" data-pajak-tabs>
    {{ $slot }}
</div>
