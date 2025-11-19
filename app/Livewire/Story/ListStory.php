<?php

namespace App\Livewire\Story;

use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ListStory extends Component
{
    public int $totalStories = 0;
    public int $page = 1;
    public array $stories = [];
    public array $links = [];

    public function mount()
    {
        $this->getStories();
    }

    public function getStories()
    {
        try {
            $headers = [
                "Authorization" => "Bearer " . session()->get('token'),
                "Accept" => "application/json"
            ];
            $response = Http::withHeaders($headers)
                ->get(ApiEndpoints::BASE_URL . ApiEndpoints::LIST_STORIES, [
                    'page' => $this->page,
                ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                noty()->error($responseData['message']);
                return;
            }

            $this->stories = $responseData['data']['data'];
            $this->links = $responseData['data']['links'];
            $this->totalStories = $responseData['data']['total'];

        } catch (\Exception $e) {
            Log::error('Fetch Stories Error: ' . $e->getMessage());
            noty()->error("An error occurred while fetching stories. Please try again." . $e->getMessage());
        }

    }


    public function gotoPage($url)
    {
        // Parse the query string
        $parsedUrl = parse_url($url);
        $query = $parsedUrl['query'] ?? '';

        // Convert query string to array
        parse_str($query, $queryParams);

        // Get the page parameter
        $this->page = $queryParams['page'] ?? null;

        $this->getOrders();
    }

    public function render()
    {
        return view('livewire.story.list-story');
    }
}
