<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\Category;

class RackController extends Controller
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
        'category_id.required' => 'Please select rack name.',
        'category_id.exists' => 'Not found.',
        'position.required' => 'Please input the :attribute.',
        'position.unique' => 'This :attribute has already been taken.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Rack::with('category')
            ->orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.racks.check')
            ->addColumn('action', 'admin.racks.action')
            ->rawColumns(['action', 'check'])
            ->addIndexColumn()
            ->make(true);
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.racks.index', compact('categories'));
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
            'category_id' => 'required|integer|exists:categories,id',
            'position' => 'required|string|digits:3|unique:racks,position',
        ], $this->customMessages);

        $racks = Rack::create(request()->all());

        $racks = Rack::with('category')->findOrFail($racks->id);

        return response()->json($racks);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rack  $rack
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rack  $rack
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $racks = Rack::findOrFail($id);

        return response()->json($racks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rack  $rack
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $racks = Rack::findOrFail($id);

        request()->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'position' => "required|string|digits:3|unique:racks,position,{$racks->position},position",
        ], $this->customMessages);

        $racks->update(request()->all());

        $racks = Rack::with('category')->findOrFail($id);

        return response()->json($racks);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rack  $rack
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $racks = Rack::destroy($id);

        return response()->json($racks);
    }

    public function deleteAllSelected()
    {
        $racks = Rack::destroy(request()->post('ids'));

        return response()->json($racks);
    }
}
