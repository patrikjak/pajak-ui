@isset($icon)
    <span class="pajak-cmdk__icon" aria-hidden="true">{{ $icon }}</span>
@endisset
<span class="pajak-cmdk__label" data-pajak-command-palette-label>{{ $label }}</span>
@if($hint !== null)
    <span class="pajak-cmdk__hint" data-pajak-command-palette-hint>{{ $hint }}</span>
@endif
