<?php

namespace App\Livewire\Store;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListStore extends Component
{
    public $stores = [];

    public function mount()
    {
        $this->getStore();
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

            $this->stores = $responseData['data'];
        } catch (\Exception $e) {
            Log::error('Fetch Store Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching the stores. Please try again." . $e->getMessage());
        }
    }

    public function viewStore($storeId)
    {
        return $this->redirect(route('store.view', ['id' => $storeId]));
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.store.list-store');
    }
}
