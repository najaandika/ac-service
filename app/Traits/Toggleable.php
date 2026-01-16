<?php

namespace App\Traits;

use Illuminate\Http\Request;

/**
 * Trait for models that can be toggled active/inactive.
 * Use in controllers that manage toggleable resources.
 */
trait Toggleable
{
    /**
     * Toggle the active status of a model.
     *
     * @param Request $request
     * @param mixed $model The model instance with is_active attribute
     * @param string $resourceName Name for success message (e.g., 'Layanan', 'Promo')
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleToggleStatus(Request $request, $model, string $resourceName)
    {
        $model->update(['is_active' => !$model->is_active]);
        
        $status = $model->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $message = "{$resourceName} berhasil {$status}!";
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $model->is_active,
                'message' => $model->is_active ? "{$resourceName} diaktifkan!" : "{$resourceName} dinonaktifkan!"
            ]);
        }
        
        return back()->with('success', $message);
    }
}
