<div>
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
            @else
                <img src="https://placehold.co/150" class="w-24 h-24 rounded-lg shadow-md object-cover">
            @endif
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Submit</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </form>
    @script
    <script>
        $wire.on('callCreateStore', () => {
            $wire.call('createStore');
        });
    </script>
    @endscript
</div>
