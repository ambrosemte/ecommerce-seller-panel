<div class="container">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Orders List</h3>
    </div>

    @foreach($orders as $order)
        <div class="card mb-3">
            <div class="row g-0">
                {{-- Product Image --}}
                <div class="col-md-2 p-0 d-flex align-items-stretch">
                    <img src="{{ asset($order['product']['product_variations'][0]['product_media']['featured_media_url']) }}"
                        alt="Product Image" class="w-100 h-100 rounded-start" style="max-height: 155px;object-fit: fill;">
                </div>

                {{-- Product & Order Info --}}
                <div class="col-md-10">
                    <div class="card-body">
                        <h5 class="card-title">{{ $order['product']['name'] }}</h5>
                        <p class="card-text mb-1"><strong>Quantity:</strong> {{ $order['quantity'] }}</p>
                        <p class="card-text mb-2"><strong>Status:</strong> {{ ucfirst($order['latest_status']['status']) }}
                        </p>

                        {{-- Action Buttons --}}
                        @if($order['latest_status']['status'] === 'Order Placed')
                            <button wire:click="acceptOrder('{{ $order['id'] }}')"
                                class="btn btn-success btn-sm me-2">Accept</button>
                            <button wire:click="declineOrder('{{ $order['id'] }}')"
                                class="btn btn-danger btn-sm">Decline</button>
                        @elseif($order['latest_status']['status'] === 'Cancelled')
                            <button wire:click="acceptOrder('{{ $order['id'] }}')"
                                class="btn btn-success btn-sm me-2">Accept</button>
                        @elseif($order['latest_status']['status'] === 'Order Confirmed')
                            <button wire:click="declineOrder('{{ $order['id'] }}')"
                                class="btn btn-danger btn-sm">Decline</button>
                        @else
                            <span class="text-muted">Already {{ $order['latest_status']['status'] }}</span>
                        @endif

                        {{-- View Product Button --}}
                        <a href="{{ route('product.view', $order['product']['id']) }}"
                            class="btn btn-outline-primary btn-sm ms-3">
                            View Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
