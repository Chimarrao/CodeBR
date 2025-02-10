<?php

namespace App\Http\Controllers;

set_time_limit(600);
ini_set('max_execution_time', 600);

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        if (!session()->has('chat_history')) {
            session(['chat_history' => []]);
        }

        $chatHistory = session('chat_history');
        $chatHistory[] = ['role' => 'user', 'content' => $request->input('prompt')];

        $client = OpenAI::factory()
            ->withApiKey(env('OPENAI_API_KEY'))
            ->withBaseUri('https://fresedgpt.space/v1')
            ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->make();

        $config = [
            'messages' => $chatHistory, 
            'model' => 'gpt-4o',
            'stream' => true,
        ];

        try {
            $stream = $client->chat()->createStreamed((array) $config);

            return response()->stream(function () use ($stream, &$chatHistory) {
                $fullResponse = '';

                foreach ($stream as $response) {
                    $choice = Arr::first($response->choices);

                    if (empty($choice->delta->content)) {
                        continue;
                    }

                    $fullResponse .= $choice->delta->content;
                    echo $choice->delta->content;
                    ob_flush();
                    flush();
                }

                $chatHistory[] = ['role' => 'assistant', 'content' => $fullResponse];
                session(['chat_history' => $chatHistory]);
            }, 200, [
                'Cache-Control'     => 'no-cache, must-revalidate',
                'Content-Type'      => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao processar a resposta: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao processar a resposta: ' . $e->getMessage(),
            ], 500);
        }
    }
}
