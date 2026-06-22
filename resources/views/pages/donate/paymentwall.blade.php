@section('title', __('Paymentwall'))

<div class="card p-0 mb-4">
    <div class="card-body">
        <p class="text-center">Pay securely via Paymentwall</p>
        <iframe src="https://api.paymentwall.com/api/ps/?key={{ $data['public_key'] }}&uid={{ Auth::user()->jid }}&widget={{ $data['widget_code'] }}" width="100%" height="800" frameborder="0"></iframe>
    </div>
</div>
