@if($label || $showValue)
    <div class="pajak-progress-row">
        @if($label)
            <span class="pajak-progress-row__label">{{ $label }}</span>
        @endif
        <div {{ $attributes->merge(['class' => 'pajak-progress'])->class(["pajak-progress--$size->value"]) }}
             role="progressbar"
             aria-valuenow="{{ $percentage() }}"
             aria-valuemin="0"
             aria-valuemax="100">
            <div class="pajak-progress__fill pajak-progress__fill--{{ $color->value }}" style="width: {{ $percentage() }}%"></div>
        </div>
        @if($showValue)
            <span class="pajak-progress-row__value">{{ $percentage() }}%</span>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'pajak-progress'])->class(["pajak-progress--$size->value"]) }}
         role="progressbar"
         aria-valuenow="{{ $percentage() }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div class="pajak-progress__fill pajak-progress__fill--{{ $color->value }}" style="width: {{ $percentage() }}%"></div>
    </div>
@endif
