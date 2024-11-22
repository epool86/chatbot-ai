<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Document;
use App\Models\User;
use App\Models\Department;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $total_users = User::count();
        $total_active_users = User::where('status', 1)->count();
        $total_topics = Topic::count();
        $total_documents = Document::count();

        return view('dashboard', compact('total_users','total_active_users','total_topics','total_documents'));

    }

    public function apiChart()
    {

        $stats = Topic::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', Carbon::now()->subDays(4))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();

        $dates = [];
        $counts = [];

        for ($i = 5; $i > 0; $i--){
            $date = Carbon::now()->subDays($i - 1)->format('Y-m-d');
            $count = $stats->firstWhere('date', $date)?->count ?? 0;
            $dates[] = $date;
            $counts[] = $count;
        }

        $data = [
            'labels' => $dates,
            'counts' => $counts,
            'status' => 'success',
            'error' => null,
        ];

        return response()->json($data);

    }
}
