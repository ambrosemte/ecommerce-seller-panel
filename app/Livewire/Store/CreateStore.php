<?php

namespace App\Livewire\Store;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateStore extends Component
{
    use WithFileUploads;

    public bool $showLoader = false;
    public string $storeName;
    public $storeImage;

    public function validateCreateStore()
    {
        $this->validate([
            'storeName' => 'required|string|max:255',
            'storeImage' => 'required|image|max:5120', // Ensure it's an image (Max 5MB)
        ]);
        $this->showLoader = true;
        $this->dispatch('callCreateStore');
    }

    public function createStore()
    {
        try {
            // Convert Livewire file to a real file
            $imagePath = $this->storeImage->getRealPath();
            $imageName = $this->storeImage->getClientOriginalName();

            // API Headers
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->attach('image', file_get_contents($imagePath), $imageName)
                ->post(ApiEndpoints::BASE_URL . ApiEndpoints::CREATE_STORE, [
                    "name" => $this->storeName,
                ]);
            $responseData = $response->json();

            if ($response->successful()) {
                $this->reset();
                noty()->success($responseData['message']);
            } else {
                noty()->error($responseData['message']);
            }
        } catch (\Exception $e) {
            Log::error('Create Store Error: ' . $e->getMessage());
            noty()->error("An error occurred while creating the store. Please try again.");
        }
    }

    #[Layout('components.layouts.app', ['title' => "Create Store"])]
    public function render()
    {
        return view('livewire.store.create-store');
    }
}
