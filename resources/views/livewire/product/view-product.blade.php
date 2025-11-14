<div class="container">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">View Product</h3>
    </div>

    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="{{ $product['product_variations'][0]['product_media']['featured_media_url'] ?? 'https://via.placeholder.com/500x300' }}"
                    class="img-fluid rounded-start" alt="{{ $product['name'] }}">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h3 class="card-title">{{ $product['name'] }}</h3>
                    <p class="card-text"><strong>Description:</strong></p>
                    <p>{{ $product['description'] }}</p>

                    @if (!empty($product['product_variations']))
                        <h5 class="mt-4">Variations</h5>

                        @foreach ($product['product_variations'] as $index => $variation)
                            <div class="card mt-3">
                                <div class="card-header">
                                    Variation {{ $index + 1 }}
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        {{-- Variation Image --}}
                                        <div class="col-md-4">
                                            @if (!empty($variation['product_media']))
                                                <img src="{{ asset($variation['product_media']['featured_media_url']) }}"
                                                    alt="Variation Image" class="img-fluid rounded border"
                                                    style="max-height: 200px;">
                                            @else
                                                <div class="text-muted">No image available</div>
                                            @endif
                                        </div>

                                        {{-- Variation Info --}}
                                        <div class="col-md-8">
                                            <p><strong>Price:</strong> NGN{{ number_format($variation['price'], 2) }}</p>
                                            <p><strong>Quantity:</strong> {{ $variation['quantity'] }}</p>
                                            <p><strong>Discount:</strong> {{ $variation['discount'] ?? '0' }}</p>
                                            @if (!empty($variation['product_specifications']))
                                                <ul class="list-group">
                                                    @foreach ($variation['product_specifications'] as $spec)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $spec['specification_key']['name'] ?? 'N/A' }}
                                                            <span class="badge bg-secondary">
                                                                {{ is_array($spec['specification_value']) ? implode(', ', $spec['specification_value']) : $spec['specification_value'] }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">No specifications for this variation.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No variations available.</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('product') }}" class="btn btn-outline-secondary">Back to
                            Products</a>
                        <a href="" class="btn btn-primary">Edit
                            Product</a>
                        <a class="btn btn-danger" wire:click="deleteProduct">Delete
                            Product</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
