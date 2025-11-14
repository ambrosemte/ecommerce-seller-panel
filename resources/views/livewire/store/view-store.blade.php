<div class="container">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">View Store</h3>
    </div>

    <div class="row g-4"> <!-- Bootstrap grid with gap -->

        <!-- Store Card (Full Width) -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <!-- Store Info -->
                    <div class="d-flex align-items-center">
                        <!-- Store Logo -->
                        <img src="{{ $store['image_url'] ?? asset('default-logo.png') }}"
                             alt="Store Logo"
                             class="rounded-circle me-3"
                             width="50" height="50"
                             onerror="this.onerror=null;this.src='{{ asset('default-logo.png') }}';">

                        <!-- Store Details -->
                        <div>
                            <h5 class="mb-1">{{ $store['name'] }}</h5>
                            <p class="text-muted mb-0">{{ $store['followers_count'] }} Followers</p>
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <button wire:click="deleteStore" wire:confirm.prompt="Are you sure you want to delete this store?\n\nType DELETE to confirm|DELETE" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        @if (empty($products))
            <div class="col-12 text-center">
                <p class="text-muted">No products available.</p>
            </div>
        @else
            @foreach ($products as $product)
                <div class="col-lg-4 col-md-6 col-sm-12"> <!-- Responsive Grid -->
                    <div class="card h-100"> <!-- Equal height for all product cards -->
                        <img src="{{ $product['product_variations'][0]['product_media']['featured_media_url'] ?? asset('default-product.jpg') }}"
                             alt="Product Image" class="card-img-top" height="250"
                             onerror="this.onerror=null;this.src='{{ asset('default-product.jpg') }}';">

                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text">{{ $product['description'] }}</p>
                            <a href="{{route('product.view',$product['id'])}}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div> <!-- End row -->
</div> <!-- End container -->
