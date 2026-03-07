<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Shop;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all invoices (simpler approach - if you need user-specific data, add a filter)
        
        // Total Invoices
        $totalInvoices = Invoice::count();

        // Today's Sales
        $todaySales = Invoice::whereDate('invoice_date', Carbon::today())
                            ->sum('grand_total');

        // Monthly Sales
        $monthlySales = Invoice::whereMonth('invoice_date', Carbon::now()->month)
                            ->whereYear('invoice_date', Carbon::now()->year)
                            ->sum('grand_total');
       
        return view('dashboard', [
            'totalInvoices' => $totalInvoices,
            'todaySales' => $todaySales,
            'monthlySales' => $monthlySales,
        ]);
    }
}