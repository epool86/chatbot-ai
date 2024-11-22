<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Topic;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index($topic_id)
    {

        $topic = Topic::find($topic_id);
        $chats = Chat::where('topic_id', $topic_id)
                    ->where('user_id', auth()->user()->id)
                    ->get();

        return view('chat_index', compact('topic','chats'));

    }

    public function store(Request $request, $topic_id)
    {

        $topic = Topic::find($topic_id);

        $this->validate($request, [
            'message' => 'required',
        ]);

        $context = '';
        $documents = $topic->documents;
        foreach($documents as $document){
            $context .= $document->content . "\n\n";
        }

        $previous_chats = Chat::where('topic_id', $topic_id)
                                ->where('user_id', auth()->user()->id)
                                ->orderBy('created_at', 'ASC')
                                ->take(10)
                                ->get();

        //our configurations/setup
        $messages = [
            [
                'role' => 'system',
                'content' => "Please only answer based on the following context (If question is not relevance do not entertain!!!, do not answer any unrelated question): 
                    ------------------------------------------
                    ".$context."
                    ------------------------------------------

                    When asked about company information or company details, please structure your response using JSON format within <<<JSON>>> markers.
                    Example format:
                    <<<JSON>>>
                    {
                        \"company_name\": \"extracted company name\",
                        \"company_phone\": \"extracted phone no\",
                        \"campany_address\": \"address information\"
                    }
                    <<<JSON>>>
                    ",
            ]
        ];

        //old chat messages
        foreach($previous_chats as $chat){
            $messages[] = [
                'role' => 'user',
                'content' => $chat->user_message,
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => $chat->assistant_message
            ];
        }

        //new chat message
        $messages[] = [
            'role' => 'user',
            'content' => $request['message'],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('TOGETHER_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.together.xyz/v1/chat/completions', [
            'model' => 'meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo',
            'max_tokens' => 1024,
            'messages' => $messages,
        ]);

        if($response->successful()){

            $assistant_message = $response->json()['choices'][0]['message']['content'];

            if (preg_match('/<<<JSON>>>\s*({[\s\S]*?})\s*<<<JSON>>>/i', $assistant_message, $matches)) {
                try {

                    $structured_data = json_decode($matches[1], true);
                    
                    if ($structured_data && json_last_error() === JSON_ERROR_NONE) {
                        
                        //$chat->structured_data = json_encode($structured_data);
                        dd($structured_data);
                        //$company = new Company;
                        //$company->name = $structured_data->company_name;
                        //$company->save();

                    }

                } catch (\Exception $e) {
                    // Handle JSON parsing errors
                    \Log::error('JSON parsing error: ' . $e->getMessage());
                }
            }


        } else {
            $assistant_message = "Error: ".$response->body();
        }

        $chat = new Chat;
        $chat->user_id = auth()->user()->id;
        $chat->topic_id = $topic_id;
        $chat->user_message = $request['message'];
        $chat->assistant_message = $assistant_message;
        $chat->save();

        return redirect()->back();

    }

}
