<?php

namespace App\Livewire\Product;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public bool $showLoader = false;
    public string $storeId = "";
    public string $categoryId = "";
    public string $productName = "";
    public string $productDescription = "";
    public string $inputType = "text";
    public array $stores = [];
    public array $categories = [];
    public array $variations = [];
    public array $specifications = [];
    public array $specificationKeys = [];
    public array $specificationValues = [];

    public function mount()
    {
        $this->getCategories();
        $this->getStores();
        $this->variations = [
            [
                'quantity' => null,
                'price' => null,
                'discount' => null,
                'images' => [],
                'specifications' => [
                    [
                        'key_id' => null,
                        'value' => null,
                        'type' => 'text',
                    ],
                ],
            ]
        ];
    }

    public function addVariation()
    {
        $this->variations[] = [
            'quantity' => null,
            'price' => null,
            'discount' => null,
            'images' => [],
            'specifications' => [
                [
                    'key_id' => null,
                    'value' => null,
                    'type' => 'text',
                ]
            ],
        ];
    }

    public function removeVariation($index)
    {
        // Prevent removing the last remaining variation
        if (count($this->variations) > 1) {
            unset($this->variations[$index]);
            $this->variations = array_values($this->variations);
        }
    }

    public function addSpecification($variationIndex)
    {
        $this->variations[$variationIndex]['specifications'][] = [
            'key_id' => null,
            'value' => '',
            'type' => 'text',
        ];
    }

    public function removeSpecification($variationIndex, $specIndex)
    {
        // Check if the specification exists and there’s more than one spec
        if (
            isset($this->variations[$variationIndex]['specifications'][$specIndex]) &&
            count($this->variations[$variationIndex]['specifications']) > 1
        ) {
            unset($this->variations[$variationIndex]['specifications'][$specIndex]);

            // Reindex the array to avoid gaps
            $this->variations[$variationIndex]['specifications'] = array_values($this->variations[$variationIndex]['specifications']);
        }
    }



    public function getStores()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_STORES);

        $responseData = $response->json();
        $this->stores = $responseData['data'];
    }

    public function getCategories()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_CATEGORIES);

        $responseData = $response->json();
        $this->categories = $responseData['data'];
    }

    public function getSpecifications()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_CATEGORIES . "/" . $this->categoryId);

        $responseData = $response->json();
        $this->specifications = $responseData['data'];
    }

    public function updateTypeAndGetSpecValue($variationIndex, $specIndex, $keyId)
    {
        // Find the specification key by its ID
        $key = collect($this->specifications)->firstWhere('id', $keyId);

        if ($key) {
            // Store the type in the specific specification entry
            $this->variations[$variationIndex]['specifications'][$specIndex]['type'] = $key['type'] ?? null;

            // Load specification values for the key
            $this->variations[$variationIndex]['specifications'][$specIndex]['values'] = $key['specification_values'] ?? [];
        }
    }

    public $rules = [
        'storeId' => 'required|string|max:255',
        'categoryId' => 'required|string',
        'productName' => 'required|string|max:255',
        'productDescription' => 'required|string',
        'variations.*.quantity' => 'required|integer',
        'variations.*.price' => 'required|numeric',
        'variations.*.discount' => 'nullable|numeric',
        'variations.*.images' => 'required|array|min:1',
        'variations.*.images.*' => 'required|file|image|max:5124',
        'variations.*.specifications.*.value' => 'required',
    ];

    public function createProduct()
    {
        // Initialize HTTP request with headers
        $httpRequest = Http::withHeaders([
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ]);

        // Attach main product details
        $httpRequest->attach('store_id', $this->storeId);
        $httpRequest->attach('category_id', $this->categoryId);
        $httpRequest->attach('name', $this->productName);
        $httpRequest->attach('description', $this->productDescription);

        // Attach variations data
        foreach ($this->variations as $index => $variation) {
            $httpRequest->attach("variations[$index][quantity]", $variation['quantity']);
            $httpRequest->attach("variations[$index][price]", $variation['price']);
            if (!is_null($variation['discount']) && $variation['discount'] != 0) {
                $httpRequest->attach("variations[$index][discount]", $variation['discount']);
            }

            // Attach specifications
            foreach ($variation['specifications'] as $specIndex => $spec) {
                $httpRequest->attach("variations[$index][specifications][$specIndex][key_id]", $spec['key_id'] ?? 'l');
                $httpRequest->attach("variations[$index][specifications][$specIndex][value]", $spec['value']);
            }

            // Attach images
            if (!empty($variation['images'])) {
                foreach ($variation['images'] as $imageIndex => $image) {
                    $httpRequest->attach(
                        "variations[$index][images][$imageIndex]",
                        file_get_contents($image->getRealPath()),
                        $image->getClientOriginalName()
                    );
                }
            }
        }

        $response = $httpRequest->post(ApiEndpoints::BASE_URL . ApiEndpoints::CREATE_PRODUCT);

        $responseData = $response->json();

        if ($response->successful()) {
            $this->reset();
            noty()->success($responseData['message']);
        } else {
            noty()->error($responseData['message'] ?? 'An error occurred.');
        }
    }


    #[Layout('components.layouts.app', ['title' => "Create Product"])]
    public function render()
    {
        return view('livewire.product.create-product');
    }
}
