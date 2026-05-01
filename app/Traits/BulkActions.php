<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

trait BulkActions
{
    /**
     * Hard delete with foreign key dependency check
     */
    public function bulkDeleteGlobal(Request $request, string $model, string $routeName, string $modelName = 'item', array $dependencies = [])
    {
        try {
            // Get and sanitize IDs
            $ids = $request->input('ids', $request->query('ids', ''));
            if (is_string($ids)) $ids = array_filter(explode(',', $ids));
            $ids = array_map('intval', array_filter($ids));
            
            if (empty($ids)) {
                return redirect()->route($routeName)
                    ->with('error', "No {$modelName}s selected for deletion.");
            }

            Log::info("Bulk Delete Attempt", ['model' => $model, 'ids' => $ids]);

            // 🔍 Check dependencies BEFORE deleting
            if (!empty($dependencies)) {
                foreach ($dependencies as $relation => $config) {
                    $relatedModel = $config['model'];
                    $foreignColumn = $config['foreign_key'] ?? $modelName . '_id';
                    
                    // Check if any related records exist
                    $count = $relatedModel::whereIn($foreignColumn, $ids)->count();
                    
                    if ($count > 0) {
                        $message = $config['message'] ?? "Cannot delete: {$count} {$relation} record(s) are linked to these {$modelName}s.";
                        Log::warning("Bulk Delete Blocked - Dependencies Found", [
                            'relation' => $relation,
                            'count' => $count,
                            'ids' => $ids
                        ]);
                        
                        return redirect()->route($routeName)->with('error', $message);
                    }
                }
            }

            // ✅ Perform hard delete
            $deletedCount = $model::whereIn('id', $ids)->delete();

            Log::info("Bulk Delete Success", ['deleted' => $deletedCount]);

            return redirect()->route($routeName)
                ->with('success', "{$deletedCount} {$modelName}(s) deleted successfully.");

        } catch (QueryException $e) {
            Log::error("Bulk Delete QueryException", [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'error_code' => $e->errorInfo[1] ?? null
            ]);

            // Foreign key constraint (MySQL error 1451)
            if (($e->errorInfo[1] ?? null) == 1451) {
                return redirect()->route($routeName)
                    ->with('error', "Cannot delete: Records are linked to other data. Remove dependencies first.");
            }

            return redirect()->route($routeName)
                ->with('error', 'Database error: ' . ($e->errorInfo[2] ?? 'Could not delete records.'));
                
        } catch (\Exception $e) {
            Log::error("Bulk Delete Error", ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route($routeName)->with('error', 'Failed to delete. Please try again.');
        }
    }
}