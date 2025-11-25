<?php

namespace App\Livewire;

use App\Constants\ApiEndpoints;
use App\Service\ProfileCacheService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{

    public int $totalSales = 0;
    public int $totalSalesPercentage = 0;
    public string $totalSalesSign = '';
    public int $totalOrders = 0;
    public int $totalOrdersPercentage = 0;
    public string $totalOrdersSign = '';
    public int $totalProducts = 0;
    public int $totalProductsPercentage = 0;
    public string $totalProductsSign = '';
    public array $recentOrders = [];
    public array $recentProducts = [];
    public array $ordersStatus = [];
    public array $salesTrend = [];

    public function mount()
    {
        $this->getProfile();
        $this->getDashboard();
    }

    public function getProfile()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::GET_PROFILE);
            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            app(ProfileCacheService::class)->save($responseData['data']['user']);
        } catch (\Exception $e) {
            Log::error('Fetch Profile Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching profile. Please try again.");
        }
    }

    public function getDashboard()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::GET_DASHBOARD);
            $responseData = $response->json();
            //dd($responseData);

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->totalSales = $responseData['data']['total_sales'];
            $this->totalSalesPercentage = $responseData['data']['total_sales_percentage']['value'];
            $this->totalSalesSign = $responseData['data']['total_sales_percentage']['sign'];
            $this->totalOrders = $responseData['data']['total_orders'];
            $this->totalOrdersPercentage = $responseData['data']['total_orders_percentage']['value'];
            $this->totalOrdersSign = $responseData['data']['total_orders_percentage']['sign'];
            $this->totalProducts = $responseData['data']['total_products'];
            $this->totalProductsPercentage = $responseData['data']['total_products_percentage']['value'];
            $this->totalProductsSign = $responseData['data']['total_products_percentage']['sign'];
            $this->recentOrders = $responseData['data']['recent_orders'];
            $this->recentProducts = $responseData['data']['recent_products'];
            $this->ordersStatus = $responseData['data']['orders_status'];
            $this->salesTrend = $responseData['data']['sales_trend'];

        } catch (\Exception $e) {
            Log::error('Fetch Profile Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching profile. Please try again.");
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
