<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuditLogController extends Controller
{
    /**
     * Display system audit logs.
     */
    public function index(Request $request): Response
    {
        $action = $request->input('action');

        $query = AuditLog::with('actor.profile');

        if ($action) {
            $query->where('action', 'like', "%{$action}%");
        }

        $logs = $query->latest('created_at')->paginate(15)->withQueryString();

        return Inertia::render('Admin/AuditLogs', [
            'logs' => $logs,
            'selectedAction' => $action,
        ]);
    }
}
