<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $client = OpenAI::factory()
            ->withApiKey(env('OPENAI_API_KEY')) 
            ->withBaseUri('https://fresedgpt.space/v1') 
            ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->make();
        
        $response = $client->chat()->create([
            'messages' => [
                ['role' => 'user', 'content' => $request->input('prompt')],
            ],
            'model' => 'gpt-4o', 
            'stream' => false, 
        ]);

        return response()->json([
            'response' => $response['choices'][0]['message']['content'],
        ]);
    }
}