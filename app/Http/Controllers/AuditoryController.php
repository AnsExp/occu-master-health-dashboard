<?php

namespace App\Http\Controllers;

use App\Enums\ActionEnum;
use App\Enums\LevelEnum;
use App\Enums\PermissionEnum;
use App\Enums\TableEnum;
use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class AuditoryController extends Controller
{
    public function index(Request $request)
    {
        if (!PermissionEnum::can(PermissionEnum::VIEW_LOGS)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $levels = array_map(static fn(LevelEnum $level) => $level->code(), LevelEnum::cases());

        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'level' => ['nullable', 'in:' . implode(',', $levels)],
        ]);

        $query = AuditLog::with('user');

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('pages.auditory', [
            'data' => $data,
            'users' => $users,
            'levels' => $levels,
            'filters' => [
                'start_date' => $filters['start_date'] ?? '',
                'end_date' => $filters['end_date'] ?? '',
                'user_id' => (string) ($filters['user_id'] ?? ''),
                'level' => $filters['level'] ?? '',
            ],
        ]);
    }

    public function detail(AuditLog $log)
    {
        if (!PermissionEnum::can(PermissionEnum::VIEW_LOGS)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $log->load('user');

        return view('pages.auditory_detail', ['log' => $log]);
    }

    public static function info(TableEnum $table, ActionEnum $action, int $record_id, array $old_data = [], array $new_data = [])
    {
        self::create($table, $action, LevelEnum::INFO, $record_id, $old_data, $new_data);
    }

    public static function warning(TableEnum $table, ActionEnum $action, int $record_id, array $old_data = [], array $new_data = [])
    {
        self::create($table, $action, LevelEnum::WARNING, $record_id, $old_data, $new_data);
    }

    public static function error(TableEnum $table, ActionEnum $action, int $record_id, array $old_data = [], array $new_data = [])
    {
        self::create($table, $action, LevelEnum::ERROR, $record_id, $old_data, $new_data);
    }

    public static function critical(TableEnum $table, ActionEnum $action, int $record_id, array $old_data = [], array $new_data = [])
    {
        self::create($table, $action, LevelEnum::CRITICAL, $record_id, $old_data, $new_data);
    }

    private static function create(TableEnum $table, ActionEnum $action, LevelEnum $level, int $record_id, array $old_data = [], array $new_data = [])
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'table_name' => $table->code(),
            'action' => $action->code(),
            'record_id' => $record_id,
            'changes' => ['old' => $old_data, 'new' => $new_data],
            'level' => $level->code(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
