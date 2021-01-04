<?php

namespace App\Http\Controllers;

use App\Models\Publisher;

class PublisherController extends Controller
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
        'max' => ':Attribute may not be more than :max characters.',
        'email.required' => 'Please input email address.',
        'email.email' => ':Attribute is invalid format.',
        'unique' => 'This :attribute has already been taken.',
        'website.url' => 'URL Website is not valid.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Publisher::orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.publishers.check')
            ->addColumn('action', 'admin.publishers.action')
            ->rawColumns(['action', 'check'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.publishers.index');
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
            'email' => 'required|string|max:50|email:filter|unique:publishers,email',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:25|unique:publishers,phone_number',
            'website' => 'required|string|url|unique:publishers,website|max:50',
        ], $this->customMessages);

        $publishers = Publisher::create([
            'name' => strip_tags(request()->post('name')),
            'email' => request()->post('email'),
            'address' => strip_tags(request()->post('address')),
            'phone_number' => request()->post('phone_number'),
            'website' => request()->post('website'),
        ]);

        return response()->json($publishers);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publishers = Publisher::findOrFail($id);

        return response()->json($publishers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $publishers = Publisher::findOrFail($id);

        request()->validate([
            'name' => 'required|string|max:50',
            'email' => "required|string|max:50|email:filter|unique:publishers,email,{$publishers->email},email",
            'address' => 'required|string|max:255',
            'phone_number' => "required|string|max:25|unique:publishers,phone_number,{$publishers->phone_number},phone_number",
            'website' => "required|string|url|unique:publishers,website,{$publishers->website},website|max:50",
        ], $this->customMessages);

        $publishers->update([
            'name' => strip_tags(request()->post('name')),
            'email' => request()->post('email'),
            'address' => strip_tags(request()->post('address')),
            'phone_number' => request()->post('phone_number'),
            'website' => request()->post('website'),
        ]);

        return response()->json($publishers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publishers = Publisher::destroy($id);

        return response()->json($publishers);
    }

    public function deleteAllSelected()
    {
        $publishers = Publisher::destroy(request()->post('ids'));

        return response()->json($publishers);
    }
}
