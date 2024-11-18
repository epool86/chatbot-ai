<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Document;
use App\Models\User;
use App\Models\Department;

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
}
