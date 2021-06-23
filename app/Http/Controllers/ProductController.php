<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Company;
use App\Models\Item;
use App\Models\Product;
use App\Models\Publisher;
use App\Rules\ISBN;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'integer' => ':Attribute must be a number.',
        'min' => ':Attribute must be at least :min.',
        'max' => ':Attribute may not be more than :max characters.',
        'book_cover_url.max' => ':Attribute size may not be more than :max kb.',
        'exists' => 'Not found.',
        'category_id.required' => 'Please select Category.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Product::with('company')
                ->orderBy('updated_at', 'DESC')
                ->get())
                ->addColumn('company', 'admin.product.company')
                ->addColumn('photo', 'admin.product.photo')
                ->addColumn('action', 'admin.product.action')
                ->rawColumns(['company', 'photo', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.product.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();

        return view('admin.product.create', compact('companies'));
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
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'company_id' => 'nullable|integer|exists:companies,id',
        ], $this->customMessages);

        $item = new Product();
        $item->assy_number = strip_tags(request()->post('no_assy'));
        $item->name = strip_tags(request()->post('name'));
        $item->desc = strip_tags(request()->post('description'));

        if (request()->hasFile('photo')) {
            $image = request()->file('photo');
            $imageName = request()->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/products');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->photo = $imageName;
        } else {
            $item->photo = 'default.jpg';
        }

        $item->company_id = request()->post('companies_id');
        $item->save();

        return redirect()->route('admin.products.index')->with('success', "Data was successfully added! <br> (title : {$item->name})");
    }

    public function edit($id)
    {
        $products = Product::findOrFail($id);
        $companies = Company::orderBy('name')->get();

        return view('admin.product.edit', compact('products', 'companies'));
    }

    public function update($id)
    {
        $item = Product::findOrFail($id);

        request()->validate([
            'name' => 'required|string|max:45',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'company_id' => 'nullable|integer|exists:companies,id',
        ], $this->customMessages);

        $item->assy_number = strip_tags(request()->post('no_assy'));
        $item->name = strip_tags(request()->post('name'));
        $item->desc = strip_tags(request()->post('description'));

        if (request()->hasFile('photo')) {
            if ($item->photo <> 'default.jpg') {
                $fileName = public_path() . '/img/products/' . $item->photo;
                File::delete($fileName);
            }

            $image = request()->file('photo');
            $imageName = request()->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/products');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->photo = $imageName;
        }

        $item->company_id = request()->post('company_id');

        $item->save();


        return redirect()->route('admin.products.index')->with('success', "Data was successfully updated! <br> (title : {$item->name})");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Product::findOrFail($id);

        if ($item->book_cover_url <> 'default.jpg') {
            $fileName2 = public_path() . '/img/ebooks/' . $item->book_cover_url;
            File::delete($fileName2);
        }

        $itemDelete = $item->delete();

        return response()->json($itemDelete);
    }
}
