<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(): Response
    {
        $logs = AuditLog::with('user')
            ->latest('created_at')
            ->paginate(50);

        return Inertia::render('admin/audit-log/Index', [
            'logs' => $logs,
        ]);
    }
}