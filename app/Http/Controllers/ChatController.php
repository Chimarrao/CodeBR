<?php

namespace App\Http\Controllers;
set_time_limit(600);
ini_set('max_execution_time', 600);

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public function chat2(Request $request)
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

        $config = [
            'messages' => [
                ['role' => 'user', 'content' => $request->input('prompt')],
            ],
            'model' => 'gpt-4o',
            'stream' => true,
        ];

        $stream = $client->chat()->createStreamed($config);

        return response()->stream(function () use ($stream) {
            foreach ($stream as $response) {
                $choice = Arr::first($response->choices);

                if (empty($choice->delta->content)) {
                    continue;
                }

                echo $choice->delta->content;
                ob_flush();
                flush();
            }
        }, 200, [
            'Cache-Control'     => 'no-cache, must-revalidate',
            'Content-Type'      => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}