@php
    $midtrans_snap_script = config('kasir.production_mode')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp

<script type="text/javascript"
        src={{ $midtrans_snap_script }} data-client-key={{ config('kasir.client_key') }}></script>
<script type="text/javascript">
    const kasirSnapPayButton = document.getElementById('{{ $id }}');
    kasirSnapPayButton.addEventListener('click', function () {
        window.snap.pay('{{ $token }}');
    });
</script>
