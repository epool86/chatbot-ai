<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::all();
        return view('topic_index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $topic = new Topic;
        return view('topic_form', compact('topic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        $topic = new Topic;
        $topic->user_id = auth()->user()->id;
        $topic->name = $request['name'];
        $topic->description = $request['description'];
        $topic->status = $request['status'];
        $topic->save();

        return redirect()->route('topic.index')
                            ->with('message', 'Succesfully saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $topic = Topic::find($id);
        return view('topic_form', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        $topic = Topic::find($id);
        $topic->name = $request['name'];
        $topic->description = $request['description'];
        $topic->status = $request['status'];
        $topic->save();

        return redirect()->route('topic.index')
                            ->with('message', 'Succesfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);
        $topic->delete();

        return redirect()->route('topic.index')
                            ->with('message', 'Succesfully deleted!');
    }
}
