<input
    type="hidden"
    name="{{ $name }}"
    @isset($value) value="{{ $value }}" @endisset
    {{ $attributes->except(['type', 'name', 'value']) }}
>
