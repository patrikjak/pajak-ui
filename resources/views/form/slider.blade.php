<div @class(['pajak-slider', 'pajak-slider--disabled' => $disabled, 'pajak-slider--range' => $range, 'pajak-slider--error' => $error !== null])>
    @isset($label)
        <label class="pajak-slider__label" for="{{ $inputId() }}">{{ $label }}</label>
    @endisset

    @if($range)
        <div
            class="pajak-slider__track-wrap"
            data-pajak-slider
            data-range
            data-min="{{ $min }}"
            data-max="{{ $max }}"
            data-step="{{ $step }}"
            data-value-min="{{ $value }}"
            data-value-max="{{ $resolvedValueMax() }}"
            @isset($suffix) data-suffix="{{ $suffix }}" @endisset
            role="group"
            aria-label="{{ $label ?? $name }}"
        >
            <div class="pajak-slider__track">
                <div
                    class="pajak-slider__fill"
                    style="left: {{ $fillMinPercent() }}%; width: {{ $fillMaxPercent() - $fillMinPercent() }}%;"
                ></div>
            </div>

            <div
                class="pajak-slider__thumb"
                data-which="min"
                tabindex="{{ $disabled ? '-1' : '0' }}"
                role="slider"
                aria-label="{{ $label ?? $name }} minimum"
                aria-valuemin="{{ $min }}"
                aria-valuemax="{{ $max }}"
                aria-valuenow="{{ $value }}"
                style="left: {{ $fillMinPercent() }}%;"
            >
                <span class="pajak-slider__bubble">{{ $value }}{{ $suffix ? " {$suffix}" : '' }}</span>
            </div>

            <div
                class="pajak-slider__thumb"
                data-which="max"
                tabindex="{{ $disabled ? '-1' : '0' }}"
                role="slider"
                aria-label="{{ $label ?? $name }} maximum"
                aria-valuemin="{{ $min }}"
                aria-valuemax="{{ $max }}"
                aria-valuenow="{{ $resolvedValueMax() }}"
                style="left: {{ $fillMaxPercent() }}%;"
            >
                <span class="pajak-slider__bubble">{{ $resolvedValueMax() }}{{ $suffix ? " {$suffix}" : '' }}</span>
            </div>

            <input type="hidden" name="{{ $name }}[min]" class="pajak-slider__input-min" value="{{ $value }}">
            <input type="hidden" name="{{ $name }}[max]" class="pajak-slider__input-max" value="{{ $resolvedValueMax() }}">
        </div>
    @else
        <div
            class="pajak-slider__track-wrap"
            data-pajak-slider
            data-min="{{ $min }}"
            data-max="{{ $max }}"
            data-step="{{ $step }}"
            data-value="{{ $value }}"
            @isset($suffix) data-suffix="{{ $suffix }}" @endisset
            @if($showBubble) data-show-bubble @endif
        >
            <div class="pajak-slider__track">
                <div
                    class="pajak-slider__fill"
                    style="left: 0; width: {{ $fillPercent() }}%;"
                ></div>
            </div>

            <div
                class="pajak-slider__thumb"
                id="{{ $inputId() }}"
                tabindex="{{ $disabled ? '-1' : '0' }}"
                role="slider"
                aria-label="{{ $label ?? $name }}"
                aria-valuemin="{{ $min }}"
                aria-valuemax="{{ $max }}"
                aria-valuenow="{{ $value }}"
                style="left: {{ $fillPercent() }}%;"
            >
                <span class="pajak-slider__bubble">{{ $value }}{{ $suffix ? " {$suffix}" : '' }}</span>
            </div>

            <input
                type="hidden"
                name="{{ $name }}"
                class="pajak-slider__input"
                value="{{ $value }}"
                @if($disabled) disabled @endif
                {{ $attributes->except(['class', 'name']) }}
            >
        </div>
    @endif

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
