<?php

namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request, App $app)
    {
        $date = $request->query('date');
        $subscriptions = $app->subscriptions()
            ->select('status', DB::raw('count(*) as count'))
            ->whereDate('updated_at', $date)
            ->groupBy('status')
            ->get();

        return response()->json($subscriptions);
    }
}
