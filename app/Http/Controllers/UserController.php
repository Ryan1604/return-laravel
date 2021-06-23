<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\User;
use App\Models\Role;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'integer' => ':Attribute must be a number.',
        'min' => ':Attribute must be at least :min.',
        'max' => ':Attribute may not be more than :max characters.',
        'profile_url.max' => ':Attribute size may not be more than :max kb.',
        'exists' => 'Not found.',
        'disabled.required' => 'Please select "Yes" or "No".',
        'role_id.required' => 'Please select Role.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(User::with('role')->whereNotIn('id', [1])->orderBy('updated_at', 'DESC')->get())
                ->addColumn('status', 'admin.users.status')
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action', 'check', 'status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get()->except([1]);

        return view('admin.users.create', compact('roles'));
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
            'name' => 'required|string|max:45',
            'email' => 'required|string|max:50|email:filter|unique:users,email',
            'password' => 'required|string|confirmed',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role_id' => 'required|integer|exists:roles,id',
        ], $this->customMessages);

        $user = new User();
        $user->name = strip_tags(request()->post('name'));
        $user->email = request()->post('email');
        $user->password = bcrypt(request()->post('password'));

        if (request()->hasFile('profile_url')) {
            $image = request()->file('profile_url');
            $imageName = request()->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/users');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(400, 400)->save($imagePath . '/' . $imageName);

            $user->profile_url = $imageName;
        } else {
            $user->profile_url = 'default.png';
        }

        $user->role_id = request()->post('role_id');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "User was successfully added! <br> (name : {$user->name})");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['role'])->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get()->except([1]);

        return view('admin.users.edit', compact('user', 'roles'));
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
        $user = User::findOrFail($id);

        request()->validate([
            'name' => 'required|string|max:45',
            'email' => "required|string|max:50|email:filter|unique:users,email,{$user->email},email",
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role_id' => 'required|integer|exists:roles,id',
            'disabled' => 'nullable|in:0,1'
        ], $this->customMessages);

        $user->name = strip_tags(request()->post('name'));
        $user->email = request()->post('email');

        if (request()->hasFile('profile_url')) {
            if ($user->profile_url <> 'default.png') {
                $fileName = public_path() . '/img/users/' . $user->profile_url;
                File::delete($fileName);
            }

            $image = request()->file('profile_url');
            $imageName = request()->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/users');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(400, 400)->save($imagePath . '/' . $imageName);

            $user->profile_url = $imageName;
        }

        $user->role_id = request()->post('role_id');

        if (request()->has('disabled')) {
            $user->disabled = request()->post('disabled');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', "User was successfully updated! <br> (name : {$user->name})");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_url <> 'default.png') {
            $fileName = public_path() . '/img/users/' . $user->profile_url;
            File::delete($fileName);
        }

        $userDelete = $user->delete();

        return response()->json($userDelete);
    }

    public function changePassword($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function postChangePassword($id)
    {
        $user = User::findOrFail($id);

        request()->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ], $this->customMessages);

        if (Hash::check(request()->post('current_password'), $user->password)) {
            $user->password = bcrypt(request()->post('password'));
            $user->password_changed_at = now();
            $user->save();

            return response()->json($user);
        }
    }
}
