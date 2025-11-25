<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Create Story</h3>
    </div>

    <form wire:submit.prevent="createStory" method="POST">
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
                <label for="caption" class="form-label">Caption</label>
                <input type="text" class="form-control" id="caption" wire:model="caption">
                @error('caption')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="media" class="form-label">Media</label>
                <input type="file" class="form-control" id="media" wire:model="mediaFile" accept="image/*,video/*">
                @error('mediaFile')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col">
                <label for="duration" class="form-label">Duration(Hours)</label>
                <input type="number" inputmode="numeric" class="form-control" id="duration" wire:model="duration">
                @error('duration')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            @if ($mediaFile)
                @if (Str::startsWith($mediaFile->getMimeType(), 'image'))
                    <img src="{{ $mediaFile->temporaryUrl() }}" class="w-24 h-24 rounded-lg shadow-md object-cover" width="150">
                @elseif (Str::startsWith($mediaFile->getMimeType(), 'video'))
                    <video width="250" class="rounded-lg shadow-md" controls>
                        <source src="{{ $mediaFile->temporaryUrl() }}" type="{{ $mediaFile->getMimeType() }}">
                        Your browser does not support the video tag.
                    </video>
                @endif
            @endif

            <div wire:loading wire:target="mediaFile" class="mt-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <span class="ms-2 text-muted">Uploading media...</span>
            </div>
        </div>
        @if (!empty($storeId))
            <div class="mb-3 row">
                <div class="col">
                    <label for="product" class="form-label">Product (Optional)</label>
                    <input type="text" id="product" class="form-control" placeholder="Search product..."
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
                </div>
            </div>
        @endif

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="createStory">Submit</span>
            <span wire:loading wire:target="createStory">
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </form>
</div>
