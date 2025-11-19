<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Products List</h3>
    </div>

    <h4 class="text-secondary mb-3">Summary Stats</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Product</h5>
                    <h3 class="fw-bold">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-secondary mb-3">Products Table</h4>
    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Store</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="d-flex align-items-center">
                        {{-- Circular image --}}
                        <img src="{{ $product['product_variations'][0]['product_media']['featured_media_url'] ?? asset('images/default.png') }}"
                            alt="{{ $product['name'] }}" class="rounded-circle me-2" width="40" height="40">
                        {{ $product['name'] }}
                    </td>
                    <td>{{ $product['store']['name'] }}</td>
                    <td>{{ $product['category']['name'] }}</td>
                    <td>
                        <a href="{{route('product.view', $product['id'])}}" class="btn btn-primary">View Product</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No products found.</td>
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
