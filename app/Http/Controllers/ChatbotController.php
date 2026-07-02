<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatbotController extends Controller
{
    // FAQ/knowledge base — pore etake DB theke o load korte paro
    private function systemPrompt(): string
    {
        return <<<PROMPT
You are DonateBazaar's helpful assistant. You help users with:
- How donations work on this platform
- Campaign creation and browsing
- Payment methods supported
- Refund policy
- General fundraising guidance

Keep answers short, friendly, and in the user's language (Bangla/English mix is fine).
If you don't know something specific about a user's account or a specific campaign,
say you don't have access to that yet and suggest contacting support.
PROMPT;
    }

    public function chat(Request $request)
    {

        //  \Log::info('Chatbot hit!', ['message' => $request->input('message')]);

        \Log::info('Chatbot hit!', ['message' => $request->input('message')]);
    \Log::info('Key length: ' . strlen(config('services.anthropic.key')));
    \Log::info('Key starts with: ' . substr(config('services.anthropic.key'), 0, 10));


        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');

        // conversation history — session e রাখা, per-user context এর জন্য
        $history = session('chat_history', []);
        $history[] = ['role' => 'user', 'content' => $userMessage];

        return new StreamedResponse(function () use ($history) {
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->withOptions(['stream' => true])
              ->post('https://api.anthropic.com/v1/messages', [
                  'model' => 'claude-sonnet-4-6',
                  'max_tokens' => 500,
                  'system' => $this->systemPrompt(),
                  'messages' => $history,
                  'stream' => true,
              ]);
             
            

              \Log::info('Anthropic HTTP status: ' . $response->status()); // 




            $fullReply = '';
            $body = $response->toPsrResponse()->getBody();

            while (!$body->eof()) {
                $line = $this->readLine($body);
                if (str_starts_with($line, 'data: ')) {
                    $json = json_decode(substr($line, 6), true);
                    if (isset($json['type']) && $json['type'] === 'content_block_delta') {
                        $chunk = $json['delta']['text'] ?? '';
                        $fullReply .= $chunk;
                        echo "data: " . json_encode(['text' => $chunk]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                }
            }

            // history save kore rakho next message er context er jonno
            $history[] = ['role' => 'assistant', 'content' => $fullReply];
            session(['chat_history' => array_slice($history, -10)]); // last 10 msg rakhbo

            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function readLine($stream): string
    {
        $line = '';
        while (!$stream->eof()) {
            $char = $stream->read(1);
            if ($char === "\n") break;
            $line .= $char;
        }
        return trim($line);
    }
}