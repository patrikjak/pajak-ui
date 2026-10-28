<button {{ $attributes->merge(['class' => 'pajak-cmdk-trigger', 'type' => 'button']) }} data-pajak-command-palette-trigger="{{ $for }}" aria-haspopup="dialog">
    <x-heroicon-o-magnifying-glass width="16" height="16" aria-hidden="true" />
    <span class="pajak-cmdk-trigger__label">{{ $labelText() }}</span>
    <kbd class="pajak-cmdk-trigger__kbd" aria-hidden="true" data-pajak-command-palette-shortcut>Ctrl K</kbd>
</button>
