<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if(auth()->user()->isLibrarian()) {
                return $next($request);
            } else {
                abort(401);
            }
        })->except(['index']);
    }

    protected $customMessages = [
        'name.required' => 'Please input the :attribute.',
        'max' => ':Attribute may not be more than :max characters.',
        'email.required' => 'Please input email address.',
        'email.email' => ':Attribute is invalid format.',
        'email.unique' => 'This :attribute has already been taken.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Author::orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.authors.check')
            ->addColumn('action', 'admin.authors.action')
            ->rawColumns(['action', 'check'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.authors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50|email:filter|unique:authors,email',
        ], $this->customMessages);

        $authors = Author::create([
            'name' => strip_tags(request()->post('name')),
            'email' => request()->post('email'),
        ]);

        return response()->json($authors);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authors = Author::findOrFail($id);

        return response()->json($authors);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $authors = Author::findOrFail($id);

        request()->validate([
            'name' => 'required|string|max:50',
            'email' => "required|string|max:50|email:filter|unique:authors,email,{$authors->email},email",
        ], $this->customMessages);

        $authors->update([
            'name' => strip_tags(request()->post('name')),
            'email' => request()->post('email'),
        ]);

        return response()->json($authors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authors = Author::destroy($id);

        return response()->json($authors);
    }

    public function deleteAllSelected()
    {
        $authors = Author::destroy(request()->post('ids'));

        return response()->json($authors);
    }
}
