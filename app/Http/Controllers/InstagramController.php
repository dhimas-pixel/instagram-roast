<?php

namespace App\Http\Controllers;

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstagramController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function scrapeInstagramProfile(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $username = $request->input('username');

        // Set the headers for Instagram API request
        $headers = [
            'x-ig-app-id' => '936619743392459',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
            'Accept-Language' => 'en-US,en;q=0.9,ru;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept' => '*/*',
        ];

        try {
            // Request to Instagram API
            $response = Http::withHeaders($headers)->get("https://i.instagram.com/api/v1/users/web_profile_info/?username={$username}");

            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json();

                // Prepare data for Gemini API
                $scrapedPayload = [
                    'username' => $data['data']['user']['username'],
                    'full_name' => $data['data']['user']['full_name'],
                    'profile_pic_url' => $data['data']['user']['profile_pic_url'],
                    'biography' => $data['data']['user']['biography'],
                    'follower_count' => $data['data']['user']['edge_followed_by']['count'],
                    'following_count' => $data['data']['user']['edge_follow']['count'],
                    'post_count' => $data['data']['user']['edge_owner_to_timeline_media']['count'],
                ];

                $client = new Client(env('GEMINI_API_KEY'));

                $jsonEncode = json_encode($scrapedPayload);

                try {
                    $generateContent = $client->geminiPro()->generateContent(
                        new TextPart("Tolong roastin akun instagram ini: {$jsonEncode} dengan paragraf yang singkat")
                    );

                    $answer = $generateContent->text();

                    // Return user data
                    return response()->json($answer);
                } catch (\Exception $e) {
                    // Handle errors from Gemini API
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }

            // Handle various response errors from Instagram API
            $status = $response->status();
            if ($status == 404) {
                return response()->json(['error' => 'User not found on Instagram'], 404);
            } elseif ($status == 403) {
                return response()->json(['error' => 'Forbidden access to Instagram API'], 403);
            } else {
                return response()->json(['error' => 'Unable to fetch data from Instagram'], $status);
            }
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'An error occurred while fetching data from Instagram'], 500);
        }
    }
}
