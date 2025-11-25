<?php

namespace App\Livewire\PromoBanner;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use function Laravel\Prompts\search;

class EditPromoBanner extends Component
{
    use WithFileUploads;

    public string $id = '';
    public string $title = '';
    public string $subtitle = '';
    public string $buttonText = '';
    public string $actionUrl = '';
    public string $search = '';
    public string $storeId = '';
    public string $existingImage = '';
    public string $selectedProduct = '';
    public bool $status;
    public array $stores = [];
    public array $searchedProducts = [];
    public $image;

    public function mount($id)
    {
        $this->id = $id;
        $this->show();
        $this->getStore();
    }

    public function updatedStoreId()
    {
        $this->search = '';
        $this->selectedProduct = '';
    }

    public function updatedSearch($value)
    {
        if (!empty($value)) {
            $this->searchProduct($value);
        }
    }

    public function selectProduct(string $id, string $name)
    {
        $this->selectedProduct = $id;
        $this->search = $name;
        $this->searchedProducts = [];
    }


    public function getStore()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_STORES);

            $responseData = $response->json();
            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->stores = $responseData['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('Fetch Store Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching the stores. Please try again." . $e->getMessage());
        }
    }

    public function searchProduct(string $query)
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::SEARCH_PRODUCT, [
                    "query" => $query,
                ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->searchedProducts = $responseData['data'];

        } catch (\Exception $e) {
            Log::error('Search Product Error: ' . $e->getMessage());
            noty()->error("An error occurred while searching product. Please try again.");
        }
    }

    public function show()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::VIEW_PROMO_BANNER . "/{$this->id}");

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->title = $responseData['data']['title'] ?? '';
            $this->subtitle = $responseData['data']['subtitle'] ?? '';
            $this->buttonText = $responseData['data']['button_text'] ?? '';
            $this->storeId = $responseData['data']['store_id'] ?? '';
            $this->search = $this->selectedProduct = $responseData['data']['product_id'] ?? '';
            $this->status = $responseData['data']['is_active'];
            $this->existingImage = $responseData['data']['image_url'] ?? '';

        } catch (\Exception $e) {
            Log::error('Fetch Promo Banner Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching the promo banner. Please try again." . $e->getMessage());
        }
    }

    public function editPromoBanner()
    {
        $this->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'buttonText' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'status' => 'boolean',
            'storeId' => 'required|string',
            'selectedProduct' => 'nullable|string',
        ]);

        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $payload = [
                "title" => $this->title,
                "subtitle" => $this->subtitle,
                "button_text" => $this->buttonText,
                "is_active" => $this->status,
                "store_id" => $this->storeId,
                "product_id" => $this->selectedProduct
            ];

            $request = Http::withHeaders($headers);

            if (!empty($this->image)) {
                $request->attach('image', file_get_contents($this->image->getRealPath()), $this->image->getClientOriginalName());
            }

            $response = $request->post(ApiEndpoints::BASE_URL . ApiEndpoints::EDIT_PROMO_BANNER . "/{$this->id}", $payload);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            noty()->success($responseData['message']);
            //return redirect()->route('promo.banner');
        } catch (\Exception $e) {
            Log::error('Edit Promo Banner Error: ' . $e->getMessage());
            noty()->error("An error occurred while editing promo banner. Please try again.");
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.promo-banner.edit-promo-banner');
    }
}
