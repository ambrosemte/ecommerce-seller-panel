<?php

namespace App\Livewire\PromoBanner;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePromoBanner extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $subtitle = '';
    public string $buttonText = '';
    public string $actionUrl = '';
    public string $search = '';
    public string $storeId = '';
    public string $selectedProduct = '';
    public bool $status;
    public array $stores = [];
    public array $searchedProducts = [];
    public $image;

    public function mount()
    {
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

    public function createPromoBanner()
    {
        $this->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'buttonText' => 'nullable|string',
            'image' => 'required|image|max:5120',
            'status' => 'boolean',
            'storeId' => 'required|string',
            'selectedProduct' => 'nullable|string',
        ]);

        try {
            // Convert Livewire file to a real file
            $imagePath = $this->image->getRealPath();
            $imageName = $this->image->getClientOriginalName();

            // API Headers
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->attach('image', file_get_contents($imagePath), $imageName)
                ->post(ApiEndpoints::BASE_URL . ApiEndpoints::CREATE_PROMO_BANNER, [
                    "title" => $this->title,
                    "subtitle" => $this->subtitle,
                    "button_text" => $this->buttonText,
                    "is_active" => $this->status,
                    "store_id" => $this->storeId,
                    "product_id" => $this->selectedProduct
                ]);
            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            noty()->success($responseData['message']);
            return redirect()->route('promo.banner');
        } catch (\Exception $e) {
            Log::error('Create Promo Banner Error: ' . $e->getMessage());
            noty()->error("An error occurred while creating promo banner. Please try again.");
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.promo-banner.create-promo-banner');
    }
}
