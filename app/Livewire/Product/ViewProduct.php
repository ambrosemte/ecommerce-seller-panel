<?php

namespace App\Livewire\Product;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewProduct extends Component
{
    public string $productId = '';
    public array $product = [];

    public function mount($id)
    {
        $this->productId = $id;
        $this->getProduct();
    }

    public function getProduct()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];
            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::VIEW_PRODUCT . "/" . $this->productId);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return $this->redirect(route('product'));
            }

            $this->product = $responseData['data'];
        } catch (\Exception $e) {
            Log::error('Get Product Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching the product. Please try again.");
        }

    }

    public function deleteProduct()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];
            $response = Http::withHeaders($headers)
                ->delete(ApiEndpoints::BASE_URL . ApiEndpoints::DELETE_PRODUCT . "/" . $this->productId);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return $this->redirect(route('store'));
            }

            noty()->success($responseData['message']);
            return $this->redirect(route('product'));
        } catch (\Exception $e) {
            Log::error('Delete Product Error: ' . $e->getMessage());
            noty()->error("An error occurred while deleting the product. Please try again.");
        }

    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.product.view-product');
    }
}
