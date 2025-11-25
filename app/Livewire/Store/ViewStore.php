<?php

namespace App\Livewire\Store;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewStore extends Component
{
    public string $id = '';
    public array $store = [];
    public array $products = [];

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

        $response = Http::withHeaders($headers)
            ->get(ApiEndpoints::BASE_URL . ApiEndpoints::VIEW_STORE . "/{$this->id}");

        $responseData = $response->json();

        if (!$response->successful()) {
            noty()->error($responseData['message']);
            return $this->redirect(route('store'));
        }

        $this->store = $responseData['data'];
        $this->products = $responseData['data']['products'];
    }

    public function deleteStore()
    {

        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)
            ->delete(ApiEndpoints::BASE_URL . ApiEndpoints::DELETE_STORE . "/{$this->id}");

        $responseData = $response->json();

        if (!$response->successful()) {
            noty()->error($responseData['message']);
            return;
        }

        noty()->success($responseData['message']);
        return $this->redirect(route('store'));
    }

    public function activateDeactivateStore($status)
    {

        $status ? $this->deactivateStore() : $this->activateStore();
    }

    public function activateStore()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)
            ->patch(ApiEndpoints::BASE_URL . ApiEndpoints::ACTIVATE_STORE . "/{$this->id}");

        $responseData = $response->json();

        if (!$response->successful()) {
            noty()->error($responseData['message']);
            return;
        }

        $this->viewStore();
        noty()->success($responseData['message']);
    }

    public function deactivateStore()
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];

        $response = Http::withHeaders($headers)
            ->patch(ApiEndpoints::BASE_URL . ApiEndpoints::DEACTIVATE_STORE . "/{$this->id}");

        $responseData = $response->json();

        if (!$response->successful()) {
            noty()->error($responseData['message']);
            return;
        }

        $this->viewStore();
        noty()->success($responseData['message']);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.store.view-store');
    }
}
