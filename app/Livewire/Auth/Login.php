<?php

namespace App\Livewire\Auth;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

use function Flasher\Noty\Prime\noty;
use function Illuminate\Log\log;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        try {
            $headers = [
                "Accept" => "application/json"
            ];

            $requestBody = [
                'email' => $this->email,
                'password' => $this->password
            ];

            $response = Http::withHeaders($headers)
                ->post(ApiEndpoints::BASE_URL . ApiEndpoints::LOGIN, $requestBody);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }
            session()->put('token', $responseData['data']['token']);

            noty()->success($responseData['message']);
            return $this->redirect(route('dashboard'));
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            noty()->error("An error occurred during login. Please try again.");
        }
    }

    #[Layout('components.layouts.auth', ['title' => "Login"])]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
