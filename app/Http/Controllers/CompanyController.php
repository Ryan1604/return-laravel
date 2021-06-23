<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends Controller
{

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
        if (request()->ajax()) {
            return datatables()->of(Company::orderBy('updated_at', 'DESC')->get())
                ->addColumn('action', 'admin.company.action')
                ->rawColumns(['action', 'check'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.company.index');
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
            'name' => 'required|string|unique:companies,name|max:50',
        ], $this->customMessages);

        $companies = Company::create([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($companies);
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
        $companies = Company::findOrFail($id);

        return response()->json($companies);
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
        $companies = Company::findOrFail($id);

        request()->validate([
            'name' => "required|string|unique:companies,name,{$companies->name},name|max:50",
        ], $this->customMessages);

        $companies->update([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($companies);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories = Company::destroy($id);

        return response()->json($categories);
    }
}
