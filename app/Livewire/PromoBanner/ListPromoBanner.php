<?php

namespace App\Livewire\PromoBanner;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListPromoBanner extends Component
{
    public int $totalBanners = 0;
    public array $banners = [];
    public array $links = [];

    public function mount()
    {
        $this->getBanners();
    }

    public function getBanners()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_PROMO_BANNER);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
            }

            $this->banners = $responseData['data']['data'];
            $this->links = $responseData['data']['links'];
            $this->totalBanners = $responseData['data']['total'];
        } catch (\Exception $e) {
            Log::error('Fetch Promo Banner Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching promo banner. Please try again.");
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.promo-banner.list-promo-banner');
    }
}
