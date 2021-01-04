<?php

namespace App\Http\Controllers;

use App\Models\Role;

class RoleController extends Controller
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
            return datatables()->of(Role::whereNotIn('id', [1, 2])->orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.roles.check')
            ->addColumn('action', 'admin.roles.action')
            ->rawColumns(['action', 'check'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.roles.index');
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
            'name' => 'required|string|unique:roles,name|max:25',
        ], $this->customMessages);

        $roles = Role::create([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($roles);
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
        $roles = Role::findOrFail($id);

        return response()->json($roles);
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
        $roles = Role::findOrFail($id);

        request()->validate([
            'name' => "required|string|unique:roles,name,{$roles->name},name|max:25",
        ], $this->customMessages);

        $roles->update([
            'name' => strip_tags(request()->post('name')),
        ]);

        return response()->json($roles);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $roles = Role::destroy($id);

        return response()->json($roles);
    }

    public function deleteAllSelected()
    {
        $roles = Role::destroy(request()->post('ids'));

        return response()->json($roles);
    }
}
