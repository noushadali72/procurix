<?php

namespace App\Http\Controllers;

use App\Services\BalanceSheetService;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index(Request $request, BalanceSheetService $balanceSheetService)
    {
        $asOfDate = $request->input('as_of_date', now()->toDateString());

        $report = $balanceSheetService->generate($asOfDate);

        return view('balance_sheet.index', [
            'report' => $report,
            'asOfDate' => $asOfDate,
        ]);
    }
}
