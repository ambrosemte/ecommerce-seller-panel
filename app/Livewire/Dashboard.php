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
    public function mount()
    {
        $this->getProfile();
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

            if ($response->successful()) {
                app(ProfileCacheService::class)->save($responseData['data']['user']);
            } else {
                noty()->error($responseData['message']);
            }
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
