<div class="pajak-cmdk__row" role="none" data-pajak-command-palette-row @if($keywords !== []) data-keywords="{{ implode(' ', $keywords) }}" @endif>
    @if($action !== null)
        <form action="{{ $action }}" method="{{ $method->htmlMethod() }}" class="pajak-cmdk__form" role="none">
            @csrf
            @if($method->needsSpoofing())
                @method($method->value)
            @endif

            <button {{ $attributes->merge(['class' => 'pajak-cmdk__option', 'type' => 'submit']) }} role="option" aria-selected="false" data-pajak-command-palette-option>
                @include('pajak::common.partials.command-palette-item-body')
            </button>
        </form>
    @elseif($event !== null)
        <button {{ $attributes->merge(['class' => 'pajak-cmdk__option', 'type' => 'button']) }} role="option" aria-selected="false" data-pajak-command-palette-option data-pajak-command="{{ $event }}">
            @include('pajak::common.partials.command-palette-item-body')
        </button>
    @else
        <a {{ $attributes->merge(['class' => 'pajak-cmdk__option', 'href' => $href ?? '#']) }} role="option" aria-selected="false" data-pajak-command-palette-option>
            @include('pajak::common.partials.command-palette-item-body')
        </a>
    @endif
</div>
