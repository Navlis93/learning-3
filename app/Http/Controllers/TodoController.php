<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todoitem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $items = Todoitem::where('user_id', Auth::user()->id)->get();
        return view('todo.index', ['todoitems' => $items]);
    }

    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'text' => 'required|min:8',
        ]);
 
        if ($validator->fails()) {
            return redirect('dashboard')
                        ->withErrors($validator)
                        ->withInput();
        }
        $validated = $validator->validated();
        Todoitem::create([
            'text' => $validated['text'],
            'status' => 0,
            'user_id' => Auth::user()->id
        ]);

        return redirect('dashboard');

    }

    public function view(Request $request, $id)
    {
        $item = Todoitem::find($id);
        // if ($request->user()->cannot('view', $item)) {
        //     abort(403);
        // }
        Gate::authorize('view', $item);
        return view('todo.view', ['item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => ['required'],
            'status' => ['boolean']
        ]);
        $item = Todoitem::find($id);
        Gate::authorize('update-todo', $item);
        // if (!Gate::allows('update-todo', $item)) {
        //     abort(403);
        // }
        $item->text = $request->text;
        $item->status = $request->status ?? 0;
        $item->save();

        return redirect('dashboard'); 
    }
}
