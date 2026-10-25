<div
    class="pajak-color-picker__panel"
    role="group"
    aria-label="{{ __('pajak::ui.color_picker.panel') }}"
    data-pajak-color-panel
    @unless($inline) hidden @endunless
>
    <div
        class="pajak-color-picker__area"
        tabindex="0"
        role="slider"
        aria-label="{{ __('pajak::ui.color_picker.area') }}"
        data-pajak-color-area
    >
        <span class="pajak-color-picker__handle" data-pajak-color-handle></span>
    </div>

    <div
        class="pajak-color-picker__hue"
        tabindex="0"
        role="slider"
        aria-label="{{ __('pajak::ui.color_picker.hue') }}"
        aria-valuemin="0"
        aria-valuemax="360"
        data-pajak-color-hue
    >
        <span class="pajak-color-picker__hue-handle" data-pajak-color-hue-handle></span>
    </div>

    @if(count($presets()) > 0)
        <div class="pajak-color-picker__presets" data-pajak-color-presets>
            @foreach($presets() as $preset)
                <button
                    type="button"
                    class="pajak-color-picker__preset"
                    style="--pajak-preset: {{ $preset }}"
                    title="{{ $preset }}"
                    aria-label="{{ $preset }}"
                    data-pajak-color-preset="{{ $preset }}"
                    @if($disabled) disabled @endif
                ></button>
            @endforeach
        </div>
    @endif
</div>
