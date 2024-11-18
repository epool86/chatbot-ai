<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

use App\Models\User;
use App\Models\Topic;
use App\Models\Document;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Topic $topic)
    {
        //$documents = Document::all();
        $documents = $topic->documents;
        return view('document_index', compact('documents', 'topic'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Topic $topic)
    {
        $document = new Document;
        return view('document_form', compact('document','topic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'file' => 'required|mimes:pdf,doc,docx,txt|max:10240',
            'status' => 'required|in:0,1',
        ]);

        $document = new Document;

        $document->topic_id = $topic->id;
        $document->user_id = auth()->user()->id;
        $document->name = $request['name'];
        $document->description = $request['description'];
        $document->status = $request['status'];

        if($request->hasFile('file')){

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'document_'.$topic->id.'_'.time().'.'.$extension;

            $directory = storage_path('app/public/upload/documents');
            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
            }

            //$location = $file->storeAs('upload/documents', $filename);
            $file->move($directory, $filename);

            if($extension == 'pdf'){

                $pdfParser = new Parser();
                $pdfObject = $pdfParser->parseFile($directory.'/'.$filename);
                $content = $pdfObject->getText();

            } elseif($extension == 'txt'){

                $content = file_get_contents($directory.'/'.$filename);

            } elseif($extension == 'docx') {

                $content = '';
                $phpWord = IOFactory::load($directory.'/'.$filename);

                foreach($phpWord->getSections() as $section){

                    foreach($section->getElements() as $element){

                        if(method_exists($element, 'getText')){

                            $text = $element->getText();
                            if(is_array($text)){
                                $content .= implode(' ', $text) . ' ';
                            } else {
                                $content .= $text . ' ';
                            }

                        } elseif(method_exists($element, 'getElements')){

                            foreach($element->getElements() as $childElement){

                                if(method_exists($element, 'getText')){

                                    $text = $element->getText();
                                    if(is_array($text)){
                                        $content .= implode(' ', $text) . ' ';
                                    } else {
                                        $content .= $text . ' ';
                                    }

                                }

                            }

                        }

                    }

                }


            } else { //if doc

                $content = '';

            }

            $document->content = $content;
            $document->file = 'storage/upload/documents/'.$filename;
            $document->type = $extension;

        }

        $document->save();

        return redirect()->route('document.index', $topic->id)
                        ->with('message', 'Succesfully upload!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic, string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic, string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic, string $id)
    {

        $document = Document::find($id);

        if($document->file){
            
            $filePath = public_path($document->file);

            if(file_exists($filePath)){
                unlink($filePath);
            }

        }

        $document->delete();

        return redirect()->route('document.index', $topic->id)
                        ->with('message', 'Succesfully deleted!');

    }
}
