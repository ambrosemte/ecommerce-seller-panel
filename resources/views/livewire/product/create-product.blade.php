<div class="container">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Create Product</h3>
    </div>

    <form wire:submit.prevent="createProduct">
        <!-- Store & Category Selection -->
        <div class="mb-3 row">
            <div class="col">
                <label for="storeId" class="form-label">Store</label>
                <select class="form-control" id="storeId" wire:model="storeId">
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
                <label for="categoryId" class="form-label">Category</label>
                <select class="form-control" id="categoryId" wire:model="categoryId" wire:change="getSpecifications">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
                @error('categoryId')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Product Name & Description -->
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" wire:model="productName">
            @error('productName')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="productDescription" class="form-label">Description</label>
            <textarea class="form-control" id="productDescription" wire:model="productDescription"></textarea>
            @error('productDescription')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Variations -->
        <div class="mb-3">
            <label class="form-label">Variations</label>
            @foreach($variations as $index => $variation)
                <div class="card p-3 mb-2">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" wire:model="variations.{{ $index }}.quantity" min="1">
                            @error("variations.$index.quantity")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" wire:model="variations.{{ $index }}.price" min="0">
                            @error("variations.$index.price")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" wire:model="variations.{{ $index }}.discount" min="1"
                                max="100">
                            @error("variations.$index.discount")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Variation Images -->
                    <div class="mt-2">
                        <label class="form-label">Images</label>
                        <input type="file" class="form-control" wire:model="variations.{{ $index }}.images" multiple
                            accept="image/*">
                        @error("variations.$index.images")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if (isset($variations[$index]['images']))
                            <div class="d-flex mt-2">
                                @foreach($variations[$index]['images'] as $image)
                                    <img src="{{ $image->temporaryUrl() }}" width="50" class="me-2">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Specifications -->
                    <div class="mt-2">
                        <label class="form-label">Specifications</label>
                        @foreach($variations[$index]['specifications'] ?? [] as $specIndex => $spec)
                            <div class="row mb-2">
                                <div class="col">
                                    <select class="form-control"
                                        wire:model="variations.{{ $index }}.specifications.{{ $specIndex }}.key_id"
                                        wire:change="updateTypeAndGetSpecValue({{ $index }}, {{ $specIndex }},$event.target.value)">
                                        <option value="">Select Key</option>
                                        @foreach($specifications as $key)
                                            <option value="{{ $key['id'] }}">{{ $key['name'] }} </option>
                                        @endforeach
                                    </select>
                                    @error("variations.$index.specifications.$specIndex.key_id")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col">
                                    @if($spec['type'] == 'text')
                                        <input type="text" class="form-control"
                                            wire:model="variations.{{ $index }}.specifications.{{ $specIndex }}.value"
                                            placeholder="Value">
                                    @elseif($spec['type'] == 'integer')
                                        <input type="number" class="form-control"
                                            wire:model="variations.{{ $index }}.specifications.{{ $specIndex }}.value"
                                            placeholder="Value">
                                    @elseif($spec['type'] == 'multiple')
                                        <select class="form-control" multiple
                                            wire:model="variations.{{ $index }}.specifications.{{ $specIndex }}.value">
                                            @foreach($spec['values'] ?? [] as $val)
                                                <option value="{{ $val['value'] }}">{{ $val['value'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control"
                                            wire:model="variations.{{ $index }}.specifications.{{ $specIndex }}.value">
                                            <option value="">Select an option</option>
                                            @foreach($spec['values'] ?? [] as $val)
                                                <option value="{{ $val['value'] }}">{{ $val['value'] }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error("variations.$index.specifications.$specIndex.value")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-auto">
                                    <button type="button" wire:click="removeSpecification({{ $index }}, {{ $specIndex }})"
                                        class="btn btn-danger">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                            wire:click="addSpecification({{ $index }})">
                            + Add Specification
                        </button>
                    </div>

                    <button type="button" class="btn btn-danger btn-sm mt-3"
                        wire:click="removeVariation({{ $index }})">Remove Variation</button>
                </div>
            @endforeach
            <button type="button" class="btn btn-outline-primary mt-3" wire:click="addVariation">+ Add
                Variation</button>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Submit</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </form>
</div>
