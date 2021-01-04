<?php

namespace App\Http\Controllers;

use App\Rules\MaxBorrowItem;
use App\Models\Issue;
use App\Models\IssueItem;
use App\Models\Item;
use App\Models\Role;
use App\Models\IssueRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if(auth()->user()->isLibrarian()) {
                return $next($request);
            } else {
                abort(401);
            }
        })->except(['indexBorrow', 'indexReturn', 'fetchUser', 'fetchBook', 'penaltySetting', 'borrowSetting']);
    }

    protected $customMessages = [
        'user_id.required' => 'Please select User.',
        'book_id.required' => 'Please select Book.',
        'book_id.max' => 'You cannot borrow more than :max books.',
        'role_id.required' => 'Please select Role.',
        'date_borrow.required' => 'Please select Borrow Date.',
        'date_return.required' => 'Please select Return Date.',
        'integer' => ':Attribute must be a number.',
        'exists' => 'Not found.',
        'required' => 'Please input the :attribute.',
        'min' => ':Attribute must be at least :min.',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBorrow()
    {
        if(request()->ajax()) {
            if(request()->query('filter') == '') {
                $issue = Issue::with(['user', 'issueItems' => function($query) {
                    return $query->with('book');
                }])->withCount(['issueItems' => function($query) {
                    return $query;
                }])->having('issue_items_count', '>', '0')
                ->orderBy('created_at', 'DESC')->get();

                return datatables()->of($issue)
                ->addColumn('user', 'admin.issues.borrows.user')
                ->addColumn('book', 'admin.issues.borrows.book')
                ->addColumn('borrow', 'admin.issues.borrows.borrow')
                ->addColumn('due', 'admin.issues.borrows.due')
                ->addColumn('return', 'admin.issues.borrows.return')
                ->addColumn('penalty', function($query) {
                    $issues = $query;
                    $penalty = DB::table('penalty')->get();
                    return view('admin.issues.borrows.penalty', compact('penalty', 'issues'));
                })
                ->addColumn('status', 'admin.issues.borrows.status')
                ->addColumn('action', 'admin.issues.borrows.action')
                ->rawColumns(['user', 'book', 'borrow', 'due', 'return', 'penalty', 'status', 'action'])
                ->addIndexColumn()
                ->make(true);
            } else {
                $issue = Issue::with(['user', 'issueItems' => function($query) {
                    return $query->with('book')->where('borrow_date', request()->query('filter'));
                }])->withCount(['issueItems' => function($query) {
                    return $query->where('borrow_date', request()->query('filter'));
                }])->having('issue_items_count', '>', '0')
                ->orderBy('created_at', 'DESC')->get();

                return datatables()->of($issue)
                ->addColumn('user', 'admin.issues.borrows.user')
                ->addColumn('book', 'admin.issues.borrows.book')
                ->addColumn('borrow', 'admin.issues.borrows.borrow')
                ->addColumn('due', 'admin.issues.borrows.due')
                ->addColumn('return', 'admin.issues.borrows.return')
                ->addColumn('penalty', function($query) {
                    $issues = $query;
                    $penalty = DB::table('penalty')->get();
                    return view('admin.issues.borrows.penalty', compact('penalty', 'issues'));
                })
                ->addColumn('status', 'admin.issues.borrows.status')
                ->addColumn('action', 'admin.issues.borrows.action')
                ->rawColumns(['user', 'book', 'borrow', 'due', 'return', 'penalty', 'status', 'action'])
                ->addIndexColumn()
                ->make(true);
            }
        }

        $filter = IssueItem::distinct()->select('borrow_date')->get();

        return view('admin.issues.borrows.index', compact('filter'));
    }

    public function indexReturn()
    {
        if(request()->ajax()) {
            if(request()->query('filter') == '') {
                $issue = Issue::with(['user', 'issueItems' => function($query) {
                    return $query->with('book')->where('status', 'RETURN');
                }])->withCount(['issueItems' => function($query) {
                    return $query->where('status', 'RETURN');
                }])->having('issue_items_count', '>', '0')
                ->orderBy('updated_at', 'DESC')->get();

                return datatables()->of($issue)
                ->addColumn('user', 'admin.issues.returns.user')
                ->addColumn('book', 'admin.issues.returns.book')
                ->addColumn('borrow', 'admin.issues.returns.borrow')
                ->addColumn('return', 'admin.issues.returns.return')
                ->addColumn('status', 'admin.issues.returns.status')
                ->rawColumns(['user', 'book', 'borrow', 'return', 'status'])
                ->addIndexColumn()
                ->make(true);
            } else {
                $issue = Issue::with(['user', 'issueItems' => function($query) {
                    return $query->with('book')->where('status', 'RETURN')->where('return_date', request()->query('filter'));
                }])->withCount(['issueItems' => function($query) {
                    return $query->where('status', 'RETURN')->where('return_date', request()->query('filter'));
                }])->having('issue_items_count', '>', '0')
                ->orderBy('updated_at', 'DESC')->get();

                return datatables()->of($issue)
                ->addColumn('user', 'admin.issues.returns.user')
                ->addColumn('book', 'admin.issues.returns.book')
                ->addColumn('borrow', 'admin.issues.returns.borrow')
                ->addColumn('return', 'admin.issues.returns.return')
                ->addColumn('status', 'admin.issues.returns.status')
                ->rawColumns(['user', 'book', 'borrow', 'return', 'status'])
                ->addIndexColumn()
                ->make(true);
            }
        }

        $filter = IssueItem::distinct()->select('return_date')->where('status', 'RETURN')->get();

        return view('admin.issues.returns.index', compact('filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = today();
        $roles = Role::whereNotIn('id', [1, 2])->get();
        $users = new User();
        $items = Item::orderBy('title')->where('type', 'book')->where('disabled', '0')->get();

        return view('admin.issues.borrows.create', compact('users', 'items', 'roles', 'date'));
    }

    public function fetchUser()
    {
        $param = request()->query('user_id');
        $user = User::with(['role', 'faculty'])->findOrFail($param);

        return response()->json($user);
    }

    public function fetchBook()
    {
        $param = request()->query('book_id');
        $book = Item::with(['authors', 'category', 'publisher', 'rack.category'])->findOrFail($param);
        $borrowed = IssueItem::where('book_id', $param)->where('status', 'BORROW')->count();

        return response()->json(['data' => $book, 'borrowed' => $borrowed]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $userCheck = User::where('id', request()->post('user_id'))->whereNotIn('role_id', [1, 2])->where('disabled', '0')->exists();

        if($userCheck) {
            $userRole = User::find(request()->post('user_id'));
            if(!$userRole) {
                return redirect()->back()->withInput()->withErrors(['user_id' => 'User not found.']);
            }

            $userBorrowBookCheck = Issue::withCount(['issueItems' => function($query) {
                $query->where('status', 'BORROW');
            }])->where('user_id', request()->post('user_id'))->get();

            $totalBorrowedBookByUser = 0;
            foreach ($userBorrowBookCheck as $ubbc) {
                $totalBorrowedBookByUser += $ubbc->issue_items_count;
            }
            $rules = DB::table('issue_rules')->where('role_id', $userRole->role_id)->first();
            $maxAllowedToBorrowBook = $rules->max_borrow_item - $totalBorrowedBookByUser;

            request()->validate([
                'book_id' => ['required', 'array', new MaxBorrowItem($maxAllowedToBorrowBook, $totalBorrowedBookByUser)],
                'book_id.*' => 'required|integer|distinct|exists:items,id',
                'borrow_date' => 'required|date|before:due_date|after_or_equal:today',
                'due_date' => 'required|date|after:borrow_date|before_or_equal:' . Carbon::parse(request()->post('borrow_date'))->addDays($rules->max_borrow_day),
            ], $this->customMessages);
        } else {
            return redirect()->back()->withInput()->withErrors(['user_id' => 'User must be selected properly.']);
        }

        $book_array = [];

        foreach(request()->post('book_id') as $book_id) {
            $borrowedBook = IssueItem::where('book_id', $book_id)->where('status', 'BORROW')->count();
            $bookCheck = Item::find($book_id);
            if($bookCheck) {
                $qtyBookCheck = $bookCheck->total_qty - $bookCheck->qty_lost - $borrowedBook;

                if($qtyBookCheck > 0) {
                    array_push($book_array, $book_id);
                }
            }
        }

        $book_exclude = array_diff(request()->post('book_id'), $book_array);

        if(count($book_array) < count(request()->post('book_id'))) {
            $messages = '';
            foreach($book_exclude as $idx => $book_id) {
                $messages .= ++$idx . ". Book with title '" . Item::find($book_id)->title . "' is out of stock.<br>";
            }
            return redirect()->back()->withInput()->withErrors([
                'book_id' => $messages
            ]);
        }

        $borrow_array = [];

        foreach (request()->post('book_id') as $book_id) {
            $borrow_check = Issue::withCount(['issueItems' => function($query) use($book_id) {
                $query->where('book_id', $book_id)->where('status', 'BORROW');
            }])->where('user_id', request()->post('user_id'))->get();

            foreach($borrow_check as $bc) {
                if($bc->issue_items_count > 0) {
                    array_push($borrow_array, $book_id);
                }
            }
        }

        if(count($borrow_array) > 0) {
            $messages = '';
            foreach($borrow_array as $idx => $book_id) {
                $messages .= ++$idx . ". You cannot borrow the same book with title '" . Item::find($book_id)->title . "'.<br>";
            }
            return redirect()->back()->withInput()->withErrors([
                'book_id' => $messages
            ]);
        }

        $issue = new Issue();
        $issue->qty = count(request()->post('book_id'));
        $issue->user_id = request()->post('user_id');
        $issue->save();

        foreach(request()->post('book_id') as $book_id) {
            $issueItem = new IssueItem();
            $issueItem->issue_id = $issue->id;
            $issueItem->book_id = $book_id;
            $issueItem->borrow_date = request()->post('borrow_date');
            $issueItem->due_date = request()->post('due_date');
            $issueItem->status = 'BORROW';
            $issueItem->save();
        }

        return redirect()->route('admin.issues.borrows.index')->with('success', 'Issue was successfully added!');
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

    public function renew($id)
    {
        $issue = Issue::findOrFail(request()->post('issue_id'));
        $issueItem = IssueItem::where('issue_id', request()->post('issue_id'))->where('book_id', $id)->first();

        if($issueItem->status === 'BORROW') {
            if(Carbon::parse($issueItem->due_date)->diffInDays(today()->toDateString(), false) > 6) {
                return response()->json(['message' => 'Book cannot be renewed after late return book more than 7 days, pay for penalty first, return the book and borrow again']);
            } else {
                $issueItem->due_date = Carbon::parse($issueItem->due_date)->addWeek()->toDateString();
                $issueItem->save();

                return response()->json(['success' => "Book was successfully renewed \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
            }
        } else if($issueItem->status === 'RETURN') {
            return response()->json(['message' => "This book has already been returned. \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        } else {
            return response()->json(['message' => "This book has already lost, cannot be renewed \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        }
    }

    public function return($id)
    {
        $issue = Issue::findOrFail(request()->post('issue_id'));
        $issueItem = IssueItem::where('issue_id', request()->post('issue_id'))->where('book_id', $id)->first();

        if($issueItem->status === 'BORROW') {
            $issueItem->update([
                'return_date' => today()->toDateString(),
                'status' => 'RETURN'
            ]);

            return response()->json(['success' => "Book was successfully returned \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        } else if($issueItem->status === 'RETURN') {
            return response()->json(['message' => "This book has already been returned. \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        } else {
            return response()->json(['message' => "This book has already lost, cannot be returned \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        }

    }

    public function lost($id)
    {
        $issue = Issue::findOrFail(request()->post('issue_id'));
        $issueItem = IssueItem::where('issue_id', request()->post('issue_id'))->where('book_id', $id)->first();
        $item = Item::findOrFail($id);

        if($issueItem->status === 'BORROW') {
            if($item->qty_lost === NULL) {
                $item->update(['qty_lost' => '1']);
            } else {
                $item->update(['qty_lost' => $item->qty_lost + 1]);
            }

            IssueItem::where('issue_id', request()->post('issue_id'))->where('book_id', $id)->update(['status' => 'LOST']);

            return response()->json(['success' => "This book issue was added to lost book \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        } else if($issueItem->status === 'RETURN') {
            return response()->json(['message' => "This book has already been returned. \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        } else {
            return response()->json(['message' => "This book issue has already lost \n (user : " . $issue->user->name . ") \n (title : " . $issueItem->book->title . ")"]);
        }
    }

    public function penaltySetting()
    {
        $penalty = DB::table('penalty')->latest('id')->first();

        return view('admin.issues.penaltySetting', compact('penalty'));
    }

    public function penaltyUpdate()
    {
        request()->validate([
            'price' => 'required|integer|gte:0',
        ], $this->customMessages);

        if(today()->toDateString() === DB::table('penalty')->latest('id')->first()->date) {
            DB::table('penalty')->where('id', request()->post('id'))->update(['price' => request()->post('price')]);
        } else {
            DB::table('penalty')->insert([
                'price' => request()->post('price'),
                'date' => today()->toDateString()
            ]);
        }

        $penalty = DB::table('penalty')->latest('id')->first();

        return response()->json($penalty);
    }

    public function borrowSetting()
    {
        $roles = Role::whereNotIn('id', [1, 2])->get();
        $issueRules = IssueRule::get();

        return view('admin.issues.borrowSetting', compact('roles', 'issueRules'));
    }

    public function fetchRule()
    {
        $rules = IssueRule::where('role_id', request()->query('role_id'))->first();

        return response()->json($rules);
    }

    public function borrowUpdate()
    {
        request()->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'max_borrow_item' => 'required|integer|gte:0|lte:255',
            'max_borrow_day' => 'required|integer|gte:0|lte:255',
        ], $this->customMessages);

        IssueRule::updateOrCreate([
            'role_id' => request()->post('role_id')
        ], [
            'max_borrow_item' => request()->post('max_borrow_item'),
            'max_borrow_day' => request()->post('max_borrow_day'),
        ]);

        $roles = Role::whereNotIn('id', [1, 2])->get();
        $issueRules = IssueRule::get();
        $result = view('admin.issues.borrowSetting', compact('roles', 'issueRules'))->renderSections()['record'];

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
