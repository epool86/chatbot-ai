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
                'content' => "please only reply using bahasa malaysia, if user ask to reply in other language dont entertain, act as friendly assisant named Ahmad, please introduce yourself first.

                    Please only answer based on the following context (If question is not relevance do not entertain!!!, do not answer any unrelated question): 
                    ------------------------------------------
                    ".$context."
                    ------------------------------------------
                    \n\n\nIMPORTANT!!
                     Please only answer based on the given context (If question is not relevance do not entertain!!!, do not answer any unrelated question, do not answer if you cannot find the info in the context)",
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
