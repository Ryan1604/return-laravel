<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Date;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function generatePDF()
    {
        $date =  date('d M Y');

        $data = Product::with('company')->get();
        $result = ['data' => $data, 'date' => $date];
        $pdf = PDF::loadView('pdf', $result);

        return $pdf->download('Laporan.pdf');
    }
}
