<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Gross;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrossDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->input('from') ? now()->parse($request->input('from'))->startOfDay() : now()->startOfMonth();
        $to = $request->input('to') ? now()->parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $grossQuery = Gross::query()->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        $expenseQuery = Expense::query()->whereBetween('date', [$from->toDateString(), $to->toDateString()]);

        $grossTotal = (float) $grossQuery->clone()->sum('amount');
        $expenseTotal = (float) $expenseQuery->clone()->sum('amount');
        $netTotal = $grossTotal - $expenseTotal;

        $grossByDate = $grossQuery
            ->clone()
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $expenseByDate = $expenseQuery
            ->clone()
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dates = [];
        $grossSeries = [];
        $expenseSeries = [];
        $netSeries = [];

        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $key = $cursor->toDateString();
            $dates[] = $key;
            $g = (float) ($grossByDate[$key] ?? 0);
            $e = (float) ($expenseByDate[$key] ?? 0);
            $grossSeries[] = $g;
            $expenseSeries[] = $e;
            $netSeries[] = $g - $e;
            $cursor->addDay();
        }

        return view('finance.gross-dashboard', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'grossTotal' => $grossTotal,
            'expenseTotal' => $expenseTotal,
            'netTotal' => $netTotal,
            'dates' => $dates,
            'grossSeries' => $grossSeries,
            'expenseSeries' => $expenseSeries,
            'netSeries' => $netSeries,
        ]);
    }
}
