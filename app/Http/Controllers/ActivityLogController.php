<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs dengan filter
     */
    public function index(Request $request)
    {
        $this->authorize('view user activity log');

        $query = ActivityLog::with('user')
            ->whereNotIn('action', ['login', 'logout'])
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type') && $request->model_type !== 'all') {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50)->withQueryString();

        // Get filter options
        $users = User::whereNull('deleted_at')
            ->orderBy('name')
            ->get(['id', 'name']);

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modelTypes = ActivityLog::select('model_type')
            ->distinct()
            ->whereNotNull('model_type')
            ->orderBy('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type));

        return view('activity-logs.index', compact(
            'logs',
            'users',
            'actions',
            'modelTypes'
        ));
    }

    /**
     * Show detailed log
     */
    public function show($id)
    {
        $this->authorize('view user activity log');

        $log = ActivityLog::with('user')->findOrFail($id);

        return view('activity-logs.show', compact('log'));
    }

    /**
     * Delete old logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        $this->authorize('view user activity log');

        $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);

        $deletedCount = ActivityLog::where('created_at', '<', now()->subDays($request->days))
            ->delete();

        return redirect()->route('activity-logs.index')
            ->with('success', "Berhasil menghapus {$deletedCount} log lama (lebih dari {$request->days} hari).");
    }
}
