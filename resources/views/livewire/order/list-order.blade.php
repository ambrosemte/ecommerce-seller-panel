<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Orders List</h3>
    </div>

    <h4 class="text-secondary mb-3">Summary Stats</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h3 class="fw-bold">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-secondary mb-3">Orders Table</h4>
    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="d-flex align-items-center">
                        {{-- Circular image --}}
                        <img src="{{ $order['product']['product_variation']['product_media']['featured_media_url'] ?? asset('images/default.png') }}"
                            alt="{{ $order['product']['name'] }}" class="rounded-circle me-2" width="40" height="40">
                        {{ $order['product']['name'] }}
                    </td>
                    <td>{{ $order['quantity'] }}</td>
                    <td>{{ ucfirst($order['latest_status']['status']) }}</td>
                    <td>
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
                    </td>
                    <td>
                        <a href="{{route('product.view', $order['product']['id'])}}" class="btn btn-primary">View
                            Product</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        <nav>
            <ul class="pagination">
                @foreach ($links as $link)
                    <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                        <a class="page-link" role="button" wire:click.prevent="gotoPage('{{ $link['url'] }}')">
                            {!! $link['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>
