<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Issue;
use App\Models\IssueItem;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'email.required' => 'Please input email address.',
        'max' => ':Attribute may not be more than :max characters.',
        'email.email' => ':Attribute is invalid format.',
        'email.unique' => 'This :attribute has already been taken.',
    ];

    protected $issues, $issue_total;

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if(auth()->check()) {
                $this->issues = Issue::with('issueItems.book')->withCount(['issueItems'])
                ->where('user_id', '=', auth()->user()->id)->orderBy('created_at', 'DESC')->get();

                foreach($this->issues as $issues) {
                    $this->issue_total += $issues->issue_items_count;
                }

                view()->share(['issues' => $this->issues, 'total' => $this->issue_total]);
            } else {
                view()->share(['issues' => NULL, 'total' => NULL]);
            }

            return $next($request);
        });
    }

    public function index() {
        $latestBooks = Item::where('type', 'book')->where('disabled', '0')
                        ->orderBy('created_at', 'DESC')->get();
        $popularBooks = Item::withCount(['issueItems'])->where('type', 'book')
                        ->where('disabled', '0')->orderBy('issue_items_count', 'DESC')->get();

        return view('public.index', compact('latestBooks', 'popularBooks'));
    }

    public function aboutUs() {
        return view('public.aboutUs');
    }

    public function contact() {
        return view('public.contact');
    }

    public function search() {
        $books = DB::table('items as i')
                ->select([
                    'i.*', 'c.name as category_name',
                    'a.name as author_name', 'p.name as publisher_name',
                    'r.position as rack_position'
                ])->leftJoin('item_authors as ia', 'ia.item_id', 'i.id')
                ->leftJoin('authors as a', 'ia.author_id', 'a.id')
                ->leftJoin('categories as c', 'i.category_id', 'c.id')
                ->leftJoin('publishers as p', 'i.publisher_id', 'p.id')
                ->leftJoin('racks as r', 'i.rack_id', 'r.id')
                ->where(function($query) {
                    $query->where('i.type', 'book')->where('i.disabled', '0');
                })->where(function($query) {
                    $query->where('i.title', 'LIKE', '%' . request()->query('result') . '%')
                    ->orWhere('c.name', 'LIKE', '%' . request()->query('result') . '%')
                    ->orWhere('a.name', 'LIKE', '%' . request()->query('result') . '%')
                    ->orWhere('p.name', 'LIKE', '%' . request()->query('result') . '%')
                    ->orWhere('r.position', 'LIKE', '%' . request()->query('result') . '%');
                })->groupBy('i.id')->orderBy('i.created_at', 'DESC')->get();

        if(request()->query('result') == '') {
            return redirect()->route('public.index');
        }

        return view('public.search', compact('books'));
    }

    public function books() {
        $books = Item::with('category')->where('type', 'book')
                ->where('disabled', '0')->orderBy('created_at', 'DESC')
                ->get()->sortBy('category.name');
        $categories = Category::orderBy('name')->get();

        if(request()->ajax()) {
            $param = request()->query('category');

            if($param == '') {
                $books = Item::with('category')->where('type', 'book')->where('disabled', '0')
                        ->orderBy('created_at', 'DESC')->get()->sortBy('category.name');
            } else {
                $books = Item::with('category')->where('type', 'book')->where('disabled', '0')
                ->where('category_id', $param)->orderBy('created_at', 'DESC')->get()->sortBy('category.name');
            }

            $result = view('public.books', compact('books', 'categories'))->renderSections()['data'];

            return response()->json($result);
        }

        return view('public.books', compact('books', 'categories'));
    }

    public function bookDetail($id) {
        $book = Item::where('type', 'book')->where('id', $id)->where('disabled', '0')->firstOrFail();
        $borrowed = IssueItem::where('book_id', $id)->where('status', 'BORROW')->count();
        $relatedBooks = Item::where('type', 'book')->where('category_id', $book->category_id)
                        ->where('id', '<>', $id)->where('disabled', '0')->orderBy('created_at', 'DESC')->get();

        return view('public.bookDetail', compact('book', 'relatedBooks', 'borrowed'));
    }

    public function ebooks() {
        $ebooks = Item::with('category')->where('type', 'e-book')
                    ->where('disabled', '0')->orderBy('created_at', 'DESC')
                    ->get()->sortBy('category.name');
        $categories = Category::orderBy('name')->get();

        if(request()->ajax()) {
            $param = request()->query('category');

            if($param == '') {
                $ebooks = Item::with('category')->where('type', 'e-book')->where('disabled', '0')
                        ->orderBy('created_at', 'DESC')->get()->sortBy('category.name');
            } else {
                $ebooks = Item::with('category')->where('type', 'e-book')->where('disabled', '0')
                ->where('category_id', $param)->orderBy('created_at', 'DESC')->get()->sortBy('category.name');
            }

            $result = view('public.ebooks', compact('ebooks', 'categories'))->renderSections()['data'];

            return response()->json($result);
        }

        return view('public.ebooks', compact('ebooks', 'categories'));
    }

    public function ebookDetail($id) {
        $ebook = Item::where('type', 'e-book')->where('id', $id)->where('disabled', '0')->firstOrFail();
        $relatedEbooks = Item::where('type', 'e-book')->where('category_id', $ebook->category_id)
                        ->where('id', '<>', $id)->where('disabled', '0')->orderBy('created_at', 'DESC')->get();

        return view('public.ebookDetail', compact('ebook', 'relatedEbooks'));
    }

    public function ebookRead($id) {
        $ebook = Item::where('id', $id)->where('disabled', '0')->firstOrFail();

        return view('public.ebookRead', compact('ebook'));
    }

    public function history() {
        $penalty = DB::table('penalty')->get();

        return view('public.history', compact('penalty'));
    }

    public function sendFeedback() {
        request()->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50|email:filter|unique:feedbacks,email',
            'message' => 'required|string|max:255',
        ], $this->customMessages);

        DB::table('feedbacks')->insert([
            'name' => strip_tags(request()->post('name')),
            'email' => request()->post('email'),
            'message' => strip_tags(request()->post('message')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('public.index')->with('status', 'Your feedback has been sent, thank you!');
    }
}
