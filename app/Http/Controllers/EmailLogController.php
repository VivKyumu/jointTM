<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;

class EmailLogController extends Controller
{
    public function index()
    {
        $emailLogs = EmailLog::with(['user', 'task'])->latest()->get();

        return view('admin.email-logs', compact('emailLogs'));
    }
}
