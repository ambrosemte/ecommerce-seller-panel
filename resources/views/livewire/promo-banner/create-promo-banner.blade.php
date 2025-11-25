<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Create Promo Banner</h3>
    </div>

    <form wire:submit.prevent="createPromoBanner">
        <div class="mb-3 row">
            <div class="col">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" wire:model="title">
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col">
                <label for="subtitle" class="form-label">Subtitle</label>
                <input type="text" class="form-control" id="subtitle" wire:model="subtitle">
                @error('subtitle')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                <small class="text-body-secondary">
                    Recommended image size: 1200 × 675 px (16:9)
                </small> </br>
                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col">
                <label for="button-text" class="form-label">Button Text</label>
                <input type="text" class="form-control" id="button-text" wire:model="buttonText">
                @error('buttonText')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 rounded-lg shadow-md object-cover" width="150">
            @endif

            <div wire:loading wire:target="image" class="mt-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <span class="ms-2 text-muted">Uploading image...</span>
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="storeId" class="form-label">Store</label>
                <select class="form-control" id="storeId" wire:model.live="storeId">
                    <option value="">Select Store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store['id'] }}">{{ $store['name'] }}</option>
                    @endforeach
                </select>
                @error('storeId')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col">
                <label for="storeId" class="form-label">Product (Optional)</label>

                @if (empty($storeId))
                    <input type="text" class="form-control" disabled placeholder="Select Store first">

                    <small class="text-warning d-block mt-1">
                        ⚠️ Please select a Store first.
                    </small>

                @elseif  (!empty($storeId))
                    <input type="text" class="form-control" placeholder="Search product..."
                        wire:model.live.debounce.300ms="search">

                    @if(!empty($searchedProducts))
                        <ul class="list-group mt-1">
                            @foreach($searchedProducts as $product)
                                <li class="list-group-item list-group-item-action"
                                    wire:click="selectProduct('{{ $product['id'] }}','{{ $product['name'] }}')">
                                    {{ $product['name'] }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input type="radio" id="status_enabled" class="form-check-input" value="true" wire:model="status">
                    <label for="status_enabled" class="form-check-label">Enabled</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="status_disabled" class="form-check-input" value="false" wire:model="status">
                    <label for="status_disabled" class="form-check-label">Disabled</label>
                </div>
                <small class="text-body-secondary">
                    Activating this banner will disable the previously active one.
                </small></br>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="createPromoBanner">
            <span wire:loading.remove wire:target="createPromoBanner">Submit</span>
            <span wire:loading wire:target="createPromoBanner">
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </form>
</div>
