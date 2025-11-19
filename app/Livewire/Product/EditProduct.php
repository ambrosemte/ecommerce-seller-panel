<?php

namespace App\Livewire\Product;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;

    public bool $showLoader = false;
    public string $id = "";
    public string $storeId = "";
    public string $categoryId = "";
    public string $productName = "";
    public string $productDescription = "";
    public array $stores = [];
    public array $categories = [];
    public array $variations = [];
    public array $specifications = []; // keys for selected category
    public array $tempImages = []; // new uploads per variation

    public function mount($id)
    {
        $this->id = $id;

        // load dropdowns first
        $this->getCategories();
        $this->getStores();

        // load product and map to same structure used in CreateProduct
        $this->loadProduct();
    }

    protected function loadProduct()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::VIEW_PRODUCT . "/{$this->id}");
            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message'] ?? 'Failed to fetch product.');
                return;
            }

            $product = $responseData['data'] ?? null;
            if (!$product) {
                noty()->error('Product data not found.');
                return;
            }

            // Main fields
            $this->storeId = $product['store_id'] ?? '';
            $this->categoryId = $product['category_id'] ?? '';
            $this->productName = $product['name'] ?? '';
            $this->productDescription = $product['description'] ?? '';

            // Load specification keys for the category so we can map values
            $this->getSpecifications();

            // Map variations
            $this->variations = [];

            foreach ($product['product_variations'] ?? [] as $vIndex => $variation) {

                /** --------------------------
                 *  MAP SPECIFICATIONS
                 * -------------------------*/
                $specs = [];

                foreach ($variation['product_specifications'] ?? [] as $spec) {

                    // find key definition
                    $key = collect($this->specifications)->firstWhere('id', $spec['specification_key_id']);

                    $type = $key['type'] ?? ($spec['specification_key']['type'] ?? 'text');
                    $value = $spec['specification_value'] ?? null;

                    // normalize multiple values
                    if ($type === 'multiple') {

                        if (is_string($value) && str_contains($value, ',')) {
                            $value = array_map('trim', explode(',', $value));
                        } elseif (is_string($value)) {
                            $decoded = json_decode($value, true);
                            $value = is_array($decoded) ? $decoded : [$value];
                        }
                    }

                    $specs[] = [
                        'key_id' => $spec['specification_key_id'],
                        'value' => $value,
                        'type' => $type,
                        'values' => $key['specification_values']
                            ?? ($spec['specification_key']['values'] ?? []),
                    ];
                }

                /** --------------------------
                 *  MAP EXISTING IMAGES
                 * -------------------------*/
                $existingImages = [];

                if (!empty($variation['product_media']['media_url'])) {
                    foreach ($variation['product_media']['media_url'] as $url) {
                        $existingImages[] = $url;
                    }
                }

                /** --------------------------
                 *  ADD VARIATION ENTRY
                 * -------------------------*/
                $this->variations[] = [
                    'id' => $variation['id'],
                    'quantity' => $variation['quantity'],
                    'price' => $variation['price'],
                    'discount' => $variation['discount'],
                    'images' => [],              // new uploads
                    'existing_images' => $existingImages,
                    'specifications' => count($specs) ? $specs : [
                        [
                            'key_id' => null,
                            'value' => null,
                            'type' => 'text',
                            'values' => [],
                        ]
                    ]
                ];
            }

            // Ensure at least one variation
            if (empty($this->variations)) {
                $this->variations = [
                    [
                        'quantity' => null,
                        'price' => null,
                        'discount' => null,
                        'images' => [],
                        'existing_images' => [],
                        'specifications' => [
                            [
                                'key_id' => null,
                                'value' => null,
                                'type' => 'text',
                                'values' => [],
                            ]
                        ],
                    ]
                ];
            }

            // prepare tempImages array keyed by variation index
            $this->tempImages = [];
            foreach ($this->variations as $i => $v) {
                $this->tempImages[$i] = [];
            }

        } catch (\Exception $e) {
            Log::error('Load Product Error: ' . $e->getMessage());
            noty()->error('An error occurred while loading product.');
        }
    }

    public function getStores()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_STORES);
        $this->stores = $response->json()['data'] ?? [];
    }

    public function getCategories()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_CATEGORIES);
        $this->categories = $response->json()['data'] ?? [];
    }

    public function getSpecifications()
    {
        if (empty($this->categoryId)) {
            $this->specifications = [];
            return;
        }

        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_SPECIFICATIONS_BY_CATEGORY . "/{$this->categoryId}");
        $this->specifications = $response->json()['data'] ?? [];
    }

    public function addVariation()
    {
        $this->variations[] = [
            'quantity' => null,
            'price' => null,
            'discount' => null,
            'images' => [],
            'existing_images' => [],
            'specifications' => [
                ['key_id' => null, 'value' => null, 'type' => 'text', 'values' => []]
            ],
        ];
    }

    public function removeVariation($index)
    {
        if (count($this->variations) > 1) {
            unset($this->variations[$index]);
            $this->variations = array_values($this->variations);
        }
    }

    public function addSpecification($variationIndex)
    {
        $this->variations[$variationIndex]['specifications'][] = [
            'key_id' => null,
            'value' => null,
            'type' => 'text',
            'values' => [],
        ];
    }

    public function removeSpecification($variationIndex, $specIndex)
    {
        if (
            isset($this->variations[$variationIndex]['specifications'][$specIndex]) &&
            count($this->variations[$variationIndex]['specifications']) > 1
        ) {
            unset($this->variations[$variationIndex]['specifications'][$specIndex]);
            $this->variations[$variationIndex]['specifications'] = array_values($this->variations[$variationIndex]['specifications']);
        }
    }

    // Handle file input updates for new images
    public function updatedTempImages($value, $key)
    {
        // key example: "tempImages.1"
        $parts = explode('.', $key);
        $variationIndex = intval(end($parts));

        $this->validate([
            "tempImages.$variationIndex.*" => 'file|image|max:5124',
        ]);

        if (isset($this->tempImages[$variationIndex]) && is_array($this->tempImages[$variationIndex])) {
            foreach ($this->tempImages[$variationIndex] as $file) {
                $this->variations[$variationIndex]['images'][] = $file;
            }

            // clear temp for that index
            unset($this->tempImages[$variationIndex]);
        }
    }

    public function removeImage($variationIndex, $imageIndex)
    {
        if (isset($this->variations[$variationIndex]['images'][$imageIndex])) {
            unset($this->variations[$variationIndex]['images'][$imageIndex]);
            $this->variations[$variationIndex]['images'] = array_values($this->variations[$variationIndex]['images']);
        }
    }

    public function removeExistingImage($variationIndex, $imageIndex)
    {
        if (isset($this->variations[$variationIndex]['existing_images'][$imageIndex])) {
            unset($this->variations[$variationIndex]['existing_images'][$imageIndex]);
            $this->variations[$variationIndex]['existing_images'] = array_values($this->variations[$variationIndex]['existing_images']);
        }
    }

    public function updateTypeAndGetSpecValue($variationIndex, $specIndex, $keyId)
    {
        $key = collect($this->specifications)->firstWhere('id', $keyId);

        if ($key) {
            $type = $key['type'] ?? 'text';
            $this->variations[$variationIndex]['specifications'][$specIndex]['type'] = $type;
            $this->variations[$variationIndex]['specifications'][$specIndex]['values'] = $key['specification_values'] ?? [];

            // reset existing value to match type
            $this->variations[$variationIndex]['specifications'][$specIndex]['value'] = $type === 'multiple' ? [] : null;
        }
    }

    public function updateProduct()
    {
        // Basic validation
        $rules = [
            'storeId' => 'required|string',
            'categoryId' => 'required|string',
            'productName' => 'required|string|max:100',
            'productDescription' => 'required|string|max:255',
        ];

        // Dynamic validation rules for variations
        foreach ($this->variations as $i => $variation) {
            $rules["variations.$i.quantity"] = 'required|integer';
            $rules["variations.$i.price"] = 'required|numeric';
            $rules["variations.$i.discount"] = 'nullable|numeric';

            // Specifications validation
            foreach ($variation['specifications'] as $sIndex => $spec) {
                if (($spec['type'] ?? 'text') === 'multiple') {
                    $rules["variations.$i.specifications.$sIndex.value"] = 'required|array|min:1';
                } else {
                    $rules["variations.$i.specifications.$sIndex.value"] = 'required';
                }
            }

            // New uploaded images
            $rules["variations.$i.images.*"] = 'nullable|file|image|max:5124';
        }

        $this->validate($rules);

        try {
            $httpRequest = Http::withHeaders([
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ]);

            // Attach product main fields
            $httpRequest->attach('name', $this->productName);
            $httpRequest->attach('description', $this->productDescription);

            // Attach variations
            foreach ($this->variations as $vIndex => $variation) {
                if (!empty($variation['id'])) {
                    $httpRequest->attach("variations[$vIndex][id", $variation['id']);
                }
                $httpRequest->attach("variations[$vIndex][quantity]", $variation['quantity']);
                $httpRequest->attach("variations[$vIndex][price]", $variation['price']);
                if ($variation['discount'] != null && $variation['discount'] !== '') {
                    $httpRequest->attach("variations[$vIndex][discount]", $variation['discount']);
                }

                // Existing images sent as array
                foreach (array_values($variation['existing_images'] ?? []) as $eIndex => $url) {
                    $httpRequest->attach("variations[$vIndex][existing_images][$eIndex]", $url);
                }

                // New uploaded images
                if (!empty($variation['images'])) {
                    foreach ($variation['images'] as $imgIndex => $image) {
                        $httpRequest->attach(
                            "variations[$vIndex][images][$imgIndex]",
                            file_get_contents($image->getRealPath()),
                            $image->getClientOriginalName()
                        );
                    }
                }

                // Specifications
                foreach ($variation['specifications'] as $sIndex => $spec) {
                    $httpRequest->attach("variations[$vIndex][specifications][$sIndex][key_id]", $spec['key_id'] ?? '');

                    $value = $spec['value'] ?? null;
                    if (($spec['type'] ?? '') === 'multiple') {
                        $httpRequest->attach(
                            "variations[$vIndex][specifications][$sIndex][value]",
                            json_encode($value)
                        );
                    } else {
                        $httpRequest->attach(
                            "variations[$vIndex][specifications][$sIndex][value]",
                            (string) $value
                        );
                    }
                }
            }

            // Send request to API endpoint
            $response = $httpRequest->post(ApiEndpoints::BASE_URL . ApiEndpoints::EDIT_PRODUCT . "/{$this->id}/update");
            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message'] ?? 'An error occurred while updating the product.');
                return;
            }

            noty()->success($responseData['message'] ?? 'Product updated successfully.');
            return redirect()->route('product');

        } catch (\Exception $e) {
            Log::error('Update Product Error: ' . $e->getMessage());
            noty()->error('An error occurred while updating the product.');
        }
    }



    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.product.edit-product');
    }
}
