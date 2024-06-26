<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todoitem;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        //$items = Auth::user()->items()->get();
        $items = Auth::user()->items;
        return view('todo.index', ['todoitems' => $items]);
    }

    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'text' => 'required|min:8',
            'files.*' => [File::image()->max(2*1024)]
        ]);
 
        if ($validator->fails()) {
            return redirect('dashboard')
                        ->withErrors($validator)
                        ->withInput();
        }
        $validated = $validator->validated();
        
        $item = Todoitem::create([
            'text' => $validated['text'],
            'status' => 0,
            'user_id' => Auth::user()->id,
            'filename' => ''
        ]);
        if ($request->file('files')) {
            foreach($request->file('files') as $file) {
                $item->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect('dashboard');

    }

    public function view(Request $request, $id)
    {
        $item = Todoitem::find($id);
        Gate::authorize('view', $item);
        $tags = Tag::all();
        $media = $item->getMedia('images');

        return view('todo.view', ['item' => $item, 'tags' => $tags, 'media' => $media]);
    }

    public function viewImage(Request $request, Media $media)
    {
        return $media->toInlineResponse($request);
    }

    public function deleteImage(Request $request, $id)
    {
        $item = Todoitem::find($id);
        Gate::authorize('view', $item);
        Storage::delete( $item->filename);
        $item->filename='';
        $item->save();
        return redirect()->route('todo.view', ['id' => $item->id]);

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => ['required'],
            'status' => ['boolean'],
            'tags' =>  ['array'],
            'file' => [File::image()->max(2*1024)]
        ]);
        $item = Todoitem::find($id);
        Gate::authorize('update-todo', $item);
        
        $item->text = $request->text;
        $item->status = $request->status ?? 0;
        $item->tags()->sync($request->tags);
        if ($request->file('file')) {
            Storage::delete( $item->filename);
            $path = $request->file('file')->store('todos');
            $item->filename = $path;
        }
        $item->save();

        return redirect('dashboard'); 
    }
}
