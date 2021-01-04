<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\IssueItem;
use App\Models\Item;
use App\Models\Publisher;
use App\Models\Rack;
use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'integer' => ':Attribute must be a number.',
        'min' => ':Attribute must be at least :min.',
        'max' => ':Attribute may not be more than :max characters.',
        'profile_url.max' => ':Attribute size may not be more than :max kb.',
        'exists' => 'Not found.',
        'sn.required' => 'Please input Serial Number',
        'gender.required' => 'Please select "Male" or "Female".',
        'disabled.required' => 'Please select "Yes" or "No".',
        'role_id.required' => 'Please select Role.',
    ];

    public function dashboard() {
        $counts['books'] = Item::where('type', 'book')->where('disabled', '0')->count();
        $counts['ebooks'] = Item::where('type', 'e-book')->where('disabled', '0')->count();
        $counts['lostBooks'] = Item::where('type', 'book')->where('disabled', '0')->sum('qty_lost');
        $counts['members'] = User::whereNotIn('role_id', [1, 2])->where('disabled', '0')->count();
        $counts['roles'] = Role::count();
        $counts['borrowed'] = IssueItem::where('status', 'BORROW')->count();
        $counts['returned'] = IssueItem::where('status', 'RETURN')->count();
        $counts['lost'] = IssueItem::where('status', 'LOST')->count();
        $counts['categories'] = Category::count();
        $counts['authors'] = Author::count();
        $counts['publishers'] = Publisher::count();
        $counts['racks'] = Rack::count();

        $issues = IssueItem::whereDate('updated_at', today()->toDateString())->get();

        return view('admin.dashboard', compact('counts', 'issues'));
    }

    public function profile() {
        $user = User::findOrFail(auth()->user()->id);

        return view('admin.profile', compact('user'));
    }

    public function updateProfile() {
        $user = User::findOrFail(auth()->user()->id);

        request()->validate([
            'sn' => "required|string|max:25|unique:users,sn,{$user->sn},sn",
            'name' => 'required|string|max:45',
            'username' => "required|string|max:25|alpha_dash|unique:users,username,{$user->username},username",
            'email' => "required|string|max:50|email:filter|unique:users,email,{$user->email},email",
            'phone_number' => "required|string|max:25|unique:users,phone_number,{$user->phone_number},phone_number",
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gender' => 'required|in:M,F',
        ], $this->customMessages);

        $user->sn = strip_tags(request()->post('sn'));
        $user->name = strip_tags(request()->post('name'));
        $user->username = request()->post('username');
        $user->email = request()->post('email');
        $user->phone_number = request()->post('phone_number');
        $user->address = strip_tags(request()->post('address'));
        $user->dob = request()->post('dob');

        if(request()->hasFile('profile_url')) {
            if($user->profile_url <> 'default.png' || $user->profile_url <> 'admin.jpg') {
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

        $user->gender = request()->post('gender');
        $user->save();

        return redirect()->route('admin.dashboard');
    }

    public function changePassword() {
        $user = User::findOrFail(auth()->user()->id);

        return view('admin.changePassword', compact('user'));
    }

    public function updatePassword() {
        $user = User::findOrFail(auth()->user()->id);

        request()->validate([
            'current_password' => 'required|string',
            'password' => "required|string|confirmed",
        ], $this->customMessages);

        if(Hash::check(request()->post('current_password'), $user->password)) {
            $user->password = bcrypt(request()->post('password'));
            $user->password_changed_at = now();
            $user->save();

            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Your entered password is wrong, try again!']);
        }
    }

    public function feedback() {
        if(request()->ajax()) {
            return datatables()->of(DB::table('feedbacks')->get())->addIndexColumn()->make(true);
        }

        return view('admin.feedback');
    }
}
