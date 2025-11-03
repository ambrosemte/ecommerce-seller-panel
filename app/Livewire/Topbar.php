<?php

namespace App\Livewire;

use App\Constants\ApiEndpoints;
use App\Service\ProfileCacheService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\HttpKernel\Profiler\Profile;

class Topbar extends Component
{
    public string $name = '';
    public string $profileImage = '';

    public function mount()
    {
        $profileCache = app(ProfileCacheService::class)->get();
        $this->name = $profileCache['name'] ?? '';
        $this->profileImage = $profileCache['image_url'] ?? '';
    }

    public function logout()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];

            $response = Http::withHeaders($headers)
                ->post(ApiEndpoints::BASE_URL . ApiEndpoints::LOGOUT);
            $responseData = $response->json();

            noty()->error($responseData['message']);
            return $this->redirect(route('login'));

        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            noty()->error("An error occurred while logging out. Please try again.");
        }
    }

    public function render()
    {
        return view('livewire.topbar');
    }
}
