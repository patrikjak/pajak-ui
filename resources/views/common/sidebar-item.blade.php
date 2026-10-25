@php($itemClasses = ['is-active' => $active, 'is-warn' => $warn, 'is-disabled' => $disabled, 'is-danger' => $danger])

<div class="pajak-sb-item-wrap">
    @if($action !== null)
        <form action="{{ $action }}" method="{{ $method->htmlMethod() }}" class="pajak-sb-item-form">
            @csrf
            @if($method->needsSpoofing())
                @method($method->value)
            @endif

            <button {{ $attributes->merge(['class' => 'pajak-sb-item', 'type' => 'submit'])->class($itemClasses) }} @if($disabled) disabled aria-disabled="true" @endif>
                @include('pajak::common.partials.sidebar-item-body')
            </button>
        </form>
    @else
        <a {{ $attributes->merge(['class' => 'pajak-sb-item', 'href' => $href])->class($itemClasses) }} @if($active) aria-current="page" @endif @if($disabled) aria-disabled="true" tabindex="-1" @endif>
            @include('pajak::common.partials.sidebar-item-body')
        </a>
    @endif

    @if($slot->isNotEmpty())
        <div class="pajak-sb__sub">{{ $slot }}</div>
    @endif
</div>
