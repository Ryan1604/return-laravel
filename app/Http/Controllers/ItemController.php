<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\IssueItem;
use App\Models\Item;
use App\Models\Publisher;
use App\Models\Rack;
use App\Rules\ISBN;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if(auth()->user()->isLibrarian()) {
                return $next($request);
            } else {
                abort(401);
            }
        })->except(['indexBook', 'indexEbook', 'indexLostBook', 'bookDetail', 'ebookDetail']);
    }

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'integer' => ':Attribute must be a number.',
        'min' => ':Attribute must be at least :min.',
        'max' => ':Attribute may not be more than :max characters.',
        'book_cover_url.max' => ':Attribute size may not be more than :max kb.',
        'exists' => 'Not found.',
        'category_id.required' => 'Please select Category.',
        'publisher_id.required' => 'Please select Publisher.',
        'rack_id.required' => 'Please select Rack.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBook()
    {
        if(request()->ajax()) {
            return datatables()->of(Item::with('category')
            ->with('rack.category')->where('type', 'book')
            ->orderBy('updated_at', 'DESC')->get())
            ->addColumn('check', 'admin.items.books.check')
            ->addColumn('category', 'admin.items.books.category')
            ->addColumn('rack', 'admin.items.books.rack')
            ->addColumn('action', 'admin.items.books.action')
            ->rawColumns(['check', 'category', 'rack', 'action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.items.books.index');
    }

    public function indexEbook()
    {
        if(request()->ajax()) {
            return datatables()->of(Item::with('category')
            ->where('type', 'e-book')->orderBy('updated_at', 'DESC')
            ->get())
            ->addColumn('check', 'admin.items.ebooks.check')
            ->addColumn('category', 'admin.items.ebooks.category')
            ->addColumn('action', 'admin.items.ebooks.action')
            ->rawColumns(['check', 'category', 'action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.items.ebooks.index');
    }

    public function indexLostBook()
    {
        if(request()->ajax()) {
            return datatables()->of(Item::with('publisher')
            ->where('type', 'book')->whereNotNull('qty_lost')
            ->get())
            ->addColumn('book', 'admin.items.lostBooks.book')
            ->addColumn('action', 'admin.items.lostBooks.action')
            ->rawColumns(['book', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        $books = Item::orderBy('title')->where('type', 'book')->get();
        return view('admin.items.lostBooks.index', compact('books'));
    }

    public function editLostBook($id)
    {
        $lostBook = Item::findOrFail($id);

        return response()->json($lostBook);
    }

    public function updateLostBook()
    {
        $item = Item::findOrFail(request()->post('book_id'));
        $borrowed = IssueItem::where('book_id', $item->id)->where('status', 'BORROW')->count();
        $max_count = $item->total_qty - $borrowed;

        request()->validate([
            'qty_lost' => "nullable|integer|gte:1|lte:{$max_count}",
        ], $this->customMessages);

        $item->update(request()->all());

        return response()->json($item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookCreate()
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $racks = Rack::orderBy('position')->get();

        return view('admin.items.books.create', compact('authors', 'categories', 'publishers', 'racks'));
    }

    public function ebookCreate()
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('admin.items.ebooks.create', compact('authors', 'categories', 'publishers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookStore()
    {
        request()->validate([
            'type' => 'required|in:book',
            'isbn' => ['required', 'string', 'unique:items,isbn', 'max:25', new ISBN],
            'code' => 'nullable|string|unique:items,code|max:25',
            'title' => 'required|string|max:45',
            'year' => 'nullable|digits:4|integer|gt:1900|lte:'.(date('Y')),
            'pages' => 'nullable|integer|gte:0',
            'ebook_available' => 'nullable|in:0,1',
            'edition' => 'nullable|integer|gte:0',
            'description' => 'nullable|string',
            'book_cover_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ebook_url' => 'required_if:ebook_available,1|file|mimes:pdf',
            'table_of_contents' => 'nullable|string',
            'total_qty' => 'required|integer|gte:0|lte:255',
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|integer|distinct|exists:authors,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
            'rack_id' => 'nullable|integer|exists:racks,id',
        ], $this->customMessages);

        $item = new Item();
        $item->type = request()->post('type');
        $item->isbn = request()->post('isbn');
        $item->code = request()->post('code');
        $item->title = strip_tags(request()->post('title'));
        $item->year = request()->post('year');
        $item->pages = request()->post('pages');
        $item->edition = request()->post('edition');

        if(request()->has('ebook_available')) {
            $item->ebook_available = request()->post('ebook_available');

            if(request()->post('ebook_available') == '1') {
                if(request()->hasFile('ebook_url')) {
                    $item->ebook_url = request()->post('title') . '.' . request()->file('ebook_url')->getClientOriginalExtension();

                    request()->ebook_url->move(public_path('pdfs'), $item->ebook_url);
                }
            }
        }

        $item->description = strip_tags(request()->post('description'));

        if(request()->hasFile('book_cover_url')) {
            $image = request()->file('book_cover_url');
            $imageName = request()->post('title') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/books');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->book_cover_url = $imageName;
        } else {
            $item->book_cover_url = 'default.jpg';
        }

        $item->table_of_contents = strip_tags(request()->post('table_of_contents'));
        $item->total_qty = request()->post('total_qty');
        $item->category_id = request()->post('category_id');
        $item->publisher_id = request()->post('publisher_id');
        $item->rack_id = request()->post('rack_id');
        $item->save();

        if(request()->has('author_id')) {
            $item->authors()->attach(request()->post('author_id'));
        }

        return redirect()->route('admin.items.books.index')->with('success', "Book was successfully added! <br> (title : {$item->title})");
    }

    public function ebookStore()
    {
        request()->validate([
            'type' => 'required|in:e-book',
            'isbn' => ['required', 'string', 'unique:items,isbn', 'max:25', new ISBN],
            'code' => 'nullable|string|unique:items,code|max:25',
            'title' => 'required|string|max:45',
            'year' => 'nullable|digits:4|integer|gt:1900|lte:'.(date('Y')),
            'pages' => 'nullable|integer|gte:0',
            'edition' => 'nullable|integer|gte:0',
            'description' => 'nullable|string',
            'book_cover_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ebook_url' => 'required|file|mimes:pdf',
            'table_of_contents' => 'nullable|string',
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|integer|distinct|exists:authors,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
        ], $this->customMessages);

        $item = new Item();
        $item->type = request()->post('type');
        $item->isbn = request()->post('isbn');
        $item->code = request()->post('code');
        $item->title = strip_tags(request()->post('title'));
        $item->year = request()->post('year');
        $item->pages = request()->post('pages');
        $item->edition = request()->post('edition');
        $item->description = strip_tags(request()->post('description'));

        if(request()->hasFile('ebook_url')) {
            $item->ebook_url = request()->post('title') . '.' . request()->file('ebook_url')->getClientOriginalExtension();

            request()->ebook_url->move(public_path('pdfs'), $item->ebook_url);
        }

        if(request()->hasFile('book_cover_url')) {
            $image = request()->file('book_cover_url');
            $imageName = request()->post('title') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/ebooks');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->book_cover_url = $imageName;
        } else {
            $item->book_cover_url = 'default.jpg';
        }

        $item->table_of_contents = strip_tags(request()->post('table_of_contents'));
        $item->category_id = request()->post('category_id');
        $item->publisher_id = request()->post('publisher_id');
        $item->save();

        if(request()->has('author_id')) {
            $item->authors()->attach(request()->post('author_id'));
        }

        return redirect()->route('admin.items.ebooks.index')->with('success', "E-Book was successfully added! <br> (title : {$item->title})");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookDetail($id)
    {
        $book = Item::with(['authors', 'category', 'publisher', 'rack.category'])->findOrFail($id);
        $borrowed = IssueItem::where('book_id', $id)->where('status', 'BORROW')->count();

        return response()->json(['data' => $book, 'borrowed' => $borrowed]);

    }

    public function ebookDetail($id)
    {
        $ebook = Item::with(['authors', 'category', 'publisher'])->findOrFail($id);

        return response()->json($ebook);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookEdit($id)
    {
        $book = Item::findOrFail($id);
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $racks = Rack::orderBy('position')->get();

        return view('admin.items.books.edit', compact('book', 'authors', 'categories', 'publishers', 'racks'));
    }

    public function ebookEdit($id)
    {
        $ebook = Item::findOrFail($id);
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('admin.items.ebooks.edit', compact('ebook', 'authors', 'categories', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookUpdate($id)
    {
        $item = Item::findOrFail($id);
        $borrowed = IssueItem::where('book_id', $id)->where('status', 'BORROW')->count();
        $min_count = abs($item->qty_lost - $borrowed);
        $max_count = $item->total_qty - $borrowed;

        request()->validate([
            'type' => 'required|in:book',
            'isbn' => ['required', 'string', "unique:items,isbn,{$item->isbn},isbn", 'max:25', new ISBN],
            'code' => "nullable|string|unique:items,code,{$item->code},code|max:25",
            'title' => 'required|string|max:45',
            'year' => 'nullable|digits:4|integer|gt:1900|lte:'.(date('Y')),
            'pages' => 'nullable|integer|gte:0',
            'edition' => 'nullable|integer|gte:0',
            'ebook_available' => 'nullable|in:0,1',
            'description' => 'nullable|string',
            'book_cover_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ebook_url' => 'nullable|file|mimes:pdf',
            'table_of_contents' => 'nullable|string',
            'total_qty' => "required|integer|gte:{$min_count}|lte:255",
            'qty_lost' => "nullable|integer|gte:0|lte:{$max_count}",
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|integer|distinct|exists:authors,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
            'rack_id' => 'nullable|integer|exists:racks,id',
            'disabled' => 'nullable|in:0,1'
        ], $this->customMessages);

        $item->type = request()->post('type');
        $item->isbn = request()->post('isbn');
        $item->code = request()->post('code');
        $item->title = strip_tags(request()->post('title'));
        $item->year = request()->post('year');
        $item->pages = request()->post('pages');
        $item->edition = request()->post('edition');

        if(request()->has('ebook_available')) {
            $item->ebook_available = request()->post('ebook_available');

            if(request()->post('ebook_available') == '1') {
                if(request()->hasFile('ebook_url')) {
                    $pdf = public_path() . '/pdfs/' . $item->ebook_url;
                    File::delete($pdf);

                    $item->ebook_url = request()->post('title') . '.' . request()->file('ebook_url')->getClientOriginalExtension();

                    request()->ebook_url->move(public_path('pdfs'), $item->ebook_url);
                }
            } else {
                $pdf = public_path() . '/pdfs/' . $item->ebook_url;
                File::delete($pdf);

                $item->ebook_url = NULL;
            }
        }

        $item->description = strip_tags(request()->post('description'));

        if(request()->hasFile('book_cover_url')) {
            if($item->book_cover_url <> 'default.jpg') {
                $fileName = public_path() . '/img/books/' . $item->book_cover_url;
                File::delete($fileName);
            }

            $image = request()->file('book_cover_url');
            $imageName = request()->post('title') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/books');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->book_cover_url = $imageName;
        }

        $item->table_of_contents = strip_tags(request()->post('table_of_contents'));
        $item->total_qty = request()->post('total_qty');
        $item->qty_lost = request()->post('qty_lost');
        $item->category_id = request()->post('category_id');
        $item->publisher_id = request()->post('publisher_id');
        $item->rack_id = request()->post('rack_id');

        if(request()->has('disabled')) {
            $item->disabled = request()->post('disabled');
        }

        $item->save();

        $item->authors()->detach();

        if(request()->has('author_id')) {
            $item->authors()->attach(request()->post('author_id'));
        }

        return redirect()->route('admin.items.books.index')->with('success', "Book was successfully updated! <br> (title : {$item->title})");
    }

    public function ebookUpdate($id)
    {
        $item = Item::findOrFail($id);

        request()->validate([
            'type' => 'required|in:e-book',
            'isbn' => ['required', 'string', "unique:items,isbn,{$item->isbn},isbn", 'max:25', new ISBN],
            'code' => "nullable|string|unique:items,code,{$item->code},code|max:25",
            'title' => 'required|string|max:45',
            'year' => 'nullable|digits:4|integer|gt:1900|lte:'.(date('Y')),
            'pages' => 'nullable|integer|gte:0',
            'edition' => 'nullable|integer|gte:0',
            'description' => 'nullable|string',
            'book_cover_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ebook_url' => 'nullable|file|mimes:pdf',
            'table_of_contents' => 'nullable|string',
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|integer|distinct|exists:authors,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
            'disabled' => 'nullable|in:0,1'
        ], $this->customMessages);

        $item->type = request()->post('type');
        $item->isbn = request()->post('isbn');
        $item->code = request()->post('code');
        $item->title = strip_tags(request()->post('title'));
        $item->year = request()->post('year');
        $item->pages = request()->post('pages');
        $item->edition = request()->post('edition');
        $item->description = strip_tags(request()->post('description'));

        if(request()->hasFile('ebook_url')) {
            $fileName = public_path() . '/pdfs/' . $item->ebook_url;
            File::delete($fileName);
            $item->ebook_url = request()->post('title') . '.' . request()->file('ebook_url')->getClientOriginalExtension();

            request()->ebook_url->move(public_path('pdfs'), $item->ebook_url);
        }

        if(request()->hasFile('book_cover_url')) {
            if($item->book_cover_url <> 'default.jpg') {
                $fileName = public_path() . '/img/ebooks/' . $item->book_cover_url;
                File::delete($fileName);
            }

            $image = request()->file('book_cover_url');
            $imageName = request()->post('title') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/ebooks');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(300, 480)->save($imagePath . '/' . $imageName);

            $item->book_cover_url = $imageName;
        }

        $item->table_of_contents = strip_tags(request()->post('table_of_contents'));
        $item->category_id = request()->post('category_id');
        $item->publisher_id = request()->post('publisher_id');

        if(request()->has('disabled')) {
            $item->disabled = request()->post('disabled');
        }

        $item->save();

        $item->authors()->detach();

        if(request()->has('author_id')) {
            $item->authors()->attach(request()->post('author_id'));
        }

        return redirect()->route('admin.items.ebooks.index')->with('success', "E-Book was successfully updated! <br> (title : {$item->title})");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if($item->book_cover_url <> 'default.jpg') {
            $fileName = public_path() . '/img/books/' . $item->book_cover_url;
            $fileName2 = public_path() . '/img/ebooks/' . $item->book_cover_url;
            File::delete($fileName, $fileName2);
        }
        $pdf = public_path() . '/pdfs/' . $item->ebook_url;
        File::delete($pdf);

        $itemDelete = $item->delete();

        return response()->json($itemDelete);
    }

    public function deleteAllSelected()
    {
        foreach(request()->post('ids') as $id)
        {
            $items = Item::findOrFail($id);
            if($items->book_cover_url <> 'default.jpg') {
                $fileName = public_path() . '/img/books/' . $items->book_cover_url;
                $fileName2 = public_path() . '/img/ebooks/' . $items->book_cover_url;
                File::delete($fileName, $fileName2);
            }
            $pdf = public_path() . '/pdfs/' . $items->ebook_url;
            File::delete($pdf);

            $itemsDelete = $items->delete();
        }

        return response()->json($itemsDelete);
    }
}
