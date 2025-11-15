<?php

namespace App\Livewire\Order;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListOrder extends Component
{
    public array $orders = [];

    public function mount()
    {
        $this->getOrders();
    }

    public function getOrders()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];
            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_ORDERS);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->orders = $responseData['data'];

        } catch (\Exception $e) {
            Log::error('Fetch Orders Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching the orders. Please try again." . $e->getMessage());
        }

    }

    public function acceptOrder($orderId)
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];
        $response = Http::withHeaders($headers)
            ->put(ApiEndpoints::BASE_URL . ApiEndPoints::ACCEPT_ORDER . "/" . $orderId);

        $responseData = $response->json();

        if ($response->successful()) {
            $this->getOrders();
            noty()->success($responseData['message']);
        } else {
            noty()->error($responseData['message']);
        }
    }

    public function declineOrder($orderId)
    {
        $headers = [
            "Authorization" => "Bearer " . session()->get('token'),
            "Accept" => "application/json"
        ];
        $response = Http::withHeaders($headers)
            ->put(ApiEndpoints::BASE_URL . ApiEndPoints::DECLINE_ORDER . "/" . $orderId);

        $responseData = $response->json();

        if ($response->successful()) {
            $this->getOrders();
            noty()->success($responseData['message']);
        } else {
            noty()->error($responseData['message']);
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.order.list-order');
    }
}
