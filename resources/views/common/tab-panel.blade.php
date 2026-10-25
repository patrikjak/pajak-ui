<div {{ $attributes->merge(['class' => 'pajak-tabpanel', 'id' => $id, 'role' => 'tabpanel']) }} @unless($active) hidden @endunless>
    {{ $slot }}
</div>
