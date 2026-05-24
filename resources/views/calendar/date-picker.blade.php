<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $label }}</label>
    @endisset

    <div
        @class([
            'pajak-datepicker-wrap',
            'pajak-datepicker-wrap--disabled' => $disabled,
            'pajak-datepicker-wrap--range' => $range,
            'pajak-datepicker-wrap--time' => $time,
        ])
        data-pajak-datepicker
        @if($range) data-range @endif
        @if($time) data-time @endif
        @isset($min) data-min="{{ $min }}" @endisset
        @isset($max) data-max="{{ $max }}" @endisset
        @if(!$range && $value !== null) data-value="{{ $value }}" @endif
        @if($range && $start !== null) data-start="{{ $start }}" @endif
        @if($range && $end !== null) data-end="{{ $end }}" @endif
        data-placeholder="{{ $resolvedPlaceholder() }}"
        data-prev-label="{{ __('pajak::ui.calendar.prev_month') }}"
        data-next-label="{{ __('pajak::ui.calendar.next_month') }}"
        data-today-label="{{ __('pajak::ui.calendar.today') }}"
        data-apply-label="{{ __('pajak::ui.common.apply') }}"
        data-time-label="{{ __('pajak::ui.calendar.time') }}"
        data-start-time-label="{{ __('pajak::ui.calendar.start_time') }}"
        data-end-time-label="{{ __('pajak::ui.calendar.end_time') }}"
    >
        {{-- Hidden inputs submitted with the form --}}
        @if(!$range)
            <input type="hidden" name="{{ $name }}" class="pajak-datepicker__input" value="{{ $value ?? '' }}">
        @else
            <input type="hidden" name="{{ $name }}_start" class="pajak-datepicker__input-start" value="{{ $start ?? '' }}">
            <input type="hidden" name="{{ $name }}_end" class="pajak-datepicker__input-end" value="{{ $end ?? '' }}">
        @endif

        {{-- Trigger button --}}
        <button
            type="button"
            id="{{ $inputId() }}"
            class="pajak-datepicker__trigger"
            @if($disabled) disabled @endif
            aria-expanded="false"
            aria-haspopup="dialog"
        >
            <svg class="pajak-datepicker__ico" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span class="pajak-datepicker__display is-placeholder">{{ $resolvedPlaceholder() }}</span>
            <svg class="pajak-datepicker__chev" width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        {{-- Calendar panel (JS portals this to <body> when open) --}}
        <div class="pajak-datepicker__panel" role="dialog" aria-modal="true" hidden>

            {{-- Optional presets sidebar (range only) --}}
            @isset($presets)
                <div class="pajak-datepicker__presets">{{ $presets }}</div>
            @endisset

            <div class="pajak-datepicker__cal">
                {{-- Month/year navigation --}}
                <div class="pajak-datepicker__header">
                    <button type="button" class="pajak-datepicker__nav pajak-datepicker__nav--prev">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </button>
                    <div class="pajak-datepicker__title"></div>
                    <button type="button" class="pajak-datepicker__nav pajak-datepicker__nav--next">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                </div>

                {{-- Weekday header row (populated by JS) --}}
                <div class="pajak-datepicker__weekdays"></div>

                {{-- Day grid (populated by JS) --}}
                <div class="pajak-datepicker__grid"></div>

                {{-- Footer --}}
                <div class="pajak-datepicker__footer">
                    <span class="pajak-datepicker__meta"></span>
                    @if($time)
                        <div class="pajak-datepicker__time @if($range) pajak-datepicker__time--range @endif">
                            @if($range)
                                <input type="text" class="pajak-datepicker__time-input pajak-datepicker__time-input--start"
                                       maxlength="5" placeholder="HH:MM"
                                       aria-label="{{ __('pajak::ui.calendar.start_time') }}">
                                <span class="pajak-datepicker__time-sep">—</span>
                                <input type="text" class="pajak-datepicker__time-input pajak-datepicker__time-input--end"
                                       maxlength="5" placeholder="HH:MM"
                                       aria-label="{{ __('pajak::ui.calendar.end_time') }}">
                            @else
                                <input type="text" class="pajak-datepicker__time-input"
                                       maxlength="5" placeholder="HH:MM"
                                       aria-label="{{ __('pajak::ui.calendar.time') }}">
                            @endif
                        </div>
                    @endif
                    @if($range)
                        <button type="button" class="pajak-datepicker__action pajak-datepicker__action--apply">
                            {{ __('pajak::ui.common.apply') }}
                        </button>
                    @else
                        <button type="button" class="pajak-datepicker__action pajak-datepicker__action--today">
                            {{ __('pajak::ui.calendar.today') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
