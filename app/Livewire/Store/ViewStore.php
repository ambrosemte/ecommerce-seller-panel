<?php

namespace App\Livewire\Store;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewStore extends Component
{
    public string $id;
    public $store;
    public array $products = [];
    public array $productVariations = [];
    public $productMedia;

    public function mount()
    {
        $this->viewStore();
    }

    public function viewStore()
    {

        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->get(ApiEndpoints::BASE_URL . ApiEndpoints::VIEW_STORE . "/" . $this->id);

        $responseData = $response->json();
        if (!$response->successful()) {
            noty()->error($responseData['message']);
            return $this->redirect(route('store'));
        }

        $this->store = $responseData['data'];
        $this->products = $responseData['data']['products'];
        // $this->productVariations = $responseData['data']['products'][0]['product_variations'][0] ?? [];
        //$this->productMedia = $responseData['data']['products'][0]['product_variations'][0]['product_media'] ?? [];
    }

    public function deleteStore()
    {

        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)->delete(ApiEndpoints::BASE_URL . ApiEndpoints::DELETE_STORE . "/{$this->id}");

        $responseData = $response->json();

        if ($response->successful()) {
            noty()->success($responseData['message']);
            return $this->redirect(route('store'));
        } else {
            noty()->error($responseData['message']);
        }
    }

    #[Layout('components.layouts.app', ['title' => "View Store"])]
    public function render()
    {
        return view('livewire.store.view-store');
    }
}
