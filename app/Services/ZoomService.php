<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ZoomService
{
    private $accountId;
    private $clientId;
    private $clientSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->accountId    = config('services.zoom.account_id');
        $this->clientId     = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->baseUrl      = config('services.zoom.base_url', 'https://api.zoom.us/v2');
    }

    // Get Access Token (cached)
    private function getAccessToken()
    {
        return Cache::remember('zoom_access_token', 3300, function () {
            $response = Http::asForm()->withBasicAuth(
                $this->clientId,
                $this->clientSecret
            )->post("https://zoom.us/oauth/token", [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

            if ($response->failed()) {
                throw new \Exception('Zoom Auth failed: ' . $response->body());
            }
            return $response->json()['access_token'];
        });
    }

    // Create Meeting
    public function createMeeting($userId = 'me', $data = [])
    {
        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/users/{$userId}/meetings", $data);

        return $response->json();
    }

    // Update Meeting
    public function updateMeeting($meetingId, $data = [])
    {
        $response = Http::withToken($this->getAccessToken())
            ->patch("{$this->baseUrl}/meetings/{$meetingId}", $data);

        return $response->json();
    }

    // Delete Meeting
    public function deleteMeeting($meetingId)
    {
        $response = Http::withToken($this->getAccessToken())
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");

        return $response->status() === 204;
    }

    // List Meetings
    public function listMeetings($userId = 'me')
    {
        $response = Http::withToken($this->getAccessToken())
            ->get("{$this->baseUrl}/users/{$userId}/meetings");

        return $response->json();
    }
}
