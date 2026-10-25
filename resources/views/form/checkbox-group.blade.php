<div {{ $attributes->merge(['class' => 'pajak-checkbox-group', 'role' => 'group'])->class(['pajak-checkbox-group--with-select-all' => $selectAll !== null]) }} data-pajak-checkbox-group>
    @if($label !== null)
        <span class="pajak-checkbox-group__label">{{ $label }}</span>
    @endif

    @if($selectAll !== null)
        <label class="pajak-checkbox pajak-checkbox-group__select-all">
            <input class="pajak-checkbox__input" type="checkbox" data-pajak-checkbox-select-all>
            @include('pajak::form.partials.checkbox-box')

            <span class="pajak-checkbox__content">
                <span class="pajak-checkbox__label">{{ $selectAll }}</span>
            </span>
        </label>
    @endif

    <div class="pajak-checkbox-group__items">{{ $slot }}</div>
</div>
