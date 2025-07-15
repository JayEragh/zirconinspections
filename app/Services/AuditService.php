<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an audit event.
     */
    public static function log($action, $description, $model = null, $oldValues = null, $newValues = null)
    {
        $request = request();
        
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Log a model creation.
     */
    public static function logCreate($model, $description = null)
    {
        $description = $description ?: 'Created ' . class_basename($model);
        
        return self::log('create', $description, $model, null, $model->toArray());
    }

    /**
     * Log a model update.
     */
    public static function logUpdate($model, $oldValues, $newValues, $description = null)
    {
        $description = $description ?: 'Updated ' . class_basename($model);
        
        return self::log('update', $description, $model, $oldValues, $newValues);
    }

    /**
     * Log a model deletion.
     */
    public static function logDelete($model, $description = null)
    {
        $description = $description ?: 'Deleted ' . class_basename($model);
        
        return self::log('delete', $description, $model, $model->toArray(), null);
    }

    /**
     * Log a login event.
     */
    public static function logLogin($user, $description = null)
    {
        $description = $description ?: 'User logged in';
        
        return self::log('login', $description, $user);
    }

    /**
     * Log a logout event.
     */
    public static function logLogout($user, $description = null)
    {
        $description = $description ?: 'User logged out';
        
        return self::log('logout', $description, $user);
    }

    /**
     * Log an export event.
     */
    public static function logExport($model, $exportType, $description = null)
    {
        $description = $description ?: "Exported {$exportType} for " . class_basename($model);
        
        return self::log('export', $description, $model);
    }

    /**
     * Log an approval event.
     */
    public static function logApproval($model, $description = null)
    {
        $description = $description ?: 'Approved ' . class_basename($model);
        
        return self::log('approve', $description, $model);
    }

    /**
     * Log a decline event.
     */
    public static function logDecline($model, $description = null)
    {
        $description = $description ?: 'Declined ' . class_basename($model);
        
        return self::log('decline', $description, $model);
    }
} 