@once
    <x-kasir::tailwind/>
@endonce

@php
    $midtrans_snap_script = config('kasir.production_mode')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';

    $button_id = Str::random(5);
    $color = null;
    if (!isset($color)) {
        $color = 'blue';
    }
@endphp


<button id="kasir-snap-pay-button-{{ $button_id }}" class="w-full bg-blue-600 hover:bg-blue-700 font-semibold py-3 rounded-lg text-white transition-colors">
    {{ $slot }}
</button>

<script type="text/javascript"
        src={{ $midtrans_snap_script }} data-client-key={{ config('kasir.client_key') }}></script>
<script type="text/javascript">
    const kasirSnapPayButton = document.getElementById('kasir-snap-pay-button-{{ $button_id }}');
    kasirSnapPayButton.addEventListener('click', function () {
        window.snap.pay('{{ $token }}');
    });
</script>
