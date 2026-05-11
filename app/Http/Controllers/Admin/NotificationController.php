<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class NotificationController extends Controller
{
    /** Jumlah notif belum dibaca (untuk polling badge) */
    public function unreadCount()
    {
        return response()->json([
            'count' => ActivityLog::where('is_read', false)->count(),
        ]);
    }

    /** Ambil 15 notif terbaru lalu tandai semua sebagai sudah dibaca */
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get()
            ->map(fn($log) => [
                'id'          => $log->id,
                'description' => $log->description,
                'action'      => $log->action,
                'is_read'     => $log->is_read,
                'time'        => $log->created_at->diffForHumans(),
                'icon'        => match($log->action) {
                    'created' => 'ri-add-circle-line text-success',
                    'updated' => 'ri-edit-line text-warning',
                    'deleted' => 'ri-delete-bin-line text-danger',
                    default   => 'ri-information-line text-info',
                },
            ]);

        // Tandai semua sebagai sudah dibaca
        ActivityLog::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['data' => $logs]);
    }

    /** Hapus log aktivitas */
    public function destroy($id)
    {
        try {
            $log = ActivityLog::findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas'
            ], 500);
        }
    }
}
