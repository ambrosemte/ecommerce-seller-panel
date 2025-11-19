<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Create Store</h3>
    </div>

    <form wire:submit.prevent="createStore">
        <div class="mb-3 row">
            <div class="col">
                <label for="storeName" class="form-label">Name</label>
                <input type="text" class="form-control" id="storeName" wire:model="storeName">
                @error('storeName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col">
                <label for="storeImage" class="form-label">Image</label>
                <input type="file" class="form-control" id="storeImage" wire:model="storeImage" accept="image/*">
                @error('storeImage')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            @if ($storeImage)
                <img src="{{ $storeImage->temporaryUrl() }}" class="w-24 h-24 rounded-lg shadow-md object-cover"
                    width="150">
            @endif

            <div wire:loading wire:target="storeImage" class="mt-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <span class="ms-2 text-muted">Uploading image...</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="createStore">Submit</span>
            <span wire:loading wire:target="createStore">
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </form>
</div>
