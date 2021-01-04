<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
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
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Category::orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.categories.check')
            ->addColumn('action', 'admin.categories.action')
            ->rawColumns(['action', 'check'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.categories.index');
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
            'name' => 'required|string|unique:categories,name|max:50',
        ], $this->customMessages);

        $categories = Category::create([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::findOrFail($id);

        return response()->json($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $categories = Category::findOrFail($id);

        request()->validate([
            'name' => "required|string|unique:categories,name,{$categories->name},name|max:50",
        ], $this->customMessages);

        $categories->update([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories = Category::destroy($id);

        return response()->json($categories);
    }

    public function deleteAllSelected()
    {
        $categories = Category::destroy(request()->post('ids'));

        return response()->json($categories);
    }
}
