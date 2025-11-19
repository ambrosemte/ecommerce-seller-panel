<?php

namespace App\Livewire\Story;
use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateStory extends Component
{
    use WithFileUploads;

    public bool $showLoader = false;
    public string $storeId = '';
    public string $caption = '';
    public string $search = '';
    public string $selectedProduct = '';

    public ?int $duration = null;
    public array $searchedProducts = [];
    public $mediaFile;

    public $stores = [];

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

    // Select product and clear search results
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

    public function createStory()
    {
        $this->validate([
            'caption' => 'required|string|max:1000',
            'mediaFile' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:10240',
            'storeId' => 'required|string',
            'duration' => 'nullable|integer|min:1|max:168',
            'selectedProduct' => 'nullable|string',
        ]);

        try {
            // Convert Livewire file to a real file
            $mediaPath = $this->mediaFile->getRealPath();
            $mediaName = $this->mediaFile->getClientOriginalName();

            // API Headers
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->attach('file', file_get_contents($mediaPath), $mediaName)
                ->post(ApiEndpoints::BASE_URL . ApiEndpoints::CREATE_STORY, [
                    "caption" => $this->caption,
                    "duration_hours" => $this->duration,
                    "store_id" => $this->storeId,
                    "product_id"=>$this->selectedProduct
                ]);
            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->reset();
            $this->getStore();
            noty()->success($responseData['message']);
        } catch (\Exception $e) {
            Log::error('Create Story Error: ' . $e->getMessage());
            noty()->error("An error occurred while creating story. Please try again.");
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
                    "store_id" => $this->storeId,
                ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->searchedProducts = $responseData['data'];

        } catch (\Exception $e) {
            Log::error('Create Story Error: ' . $e->getMessage());
            noty()->error("An error occurred while creating story. Please try again.");
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.story.create-story');
    }
}
