<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\BulkActions;
use Illuminate\Http\Request;

class BulkController extends Controller
{
    use BulkActions;

    protected $resources = [
        'courses' => [
            'model' => \App\Models\Course::class,
            'route' => 'courses.index',
            'name' => 'course',
            'permission' => 'bulk-delete',
            // 🔗 Define dependencies that block deletion
            'dependencies' => [
                'AdmissionRequests' => [
                    'model' => \App\Models\AdmissionRequest::class,
                    'foreign_key' => 'course_id',
                    'message' => 'Cannot delete: Some courses have linked Admission Requests. Remove Admission Requests first.'
                ],
                'leads' => [
                    'model' => \App\Models\Lead::class,
                    'foreign_key' => 'course_id', 
                    'message' => 'Cannot delete: Some courses are assigned to leads. Reassign leads first.'
                ],
            ]
        ],
        'colleges' => [
            'model' => \App\Models\College::class,
            'route' => 'colleges.index',
            'name' => 'college',
            'permission' => 'bulk-delete',
            'dependencies' => [
                'AdmissionRequests' => [
                    'model' => \App\Models\AdmissionRequest::class,
                    'foreign_key' => 'college_id',
                    'message' => 'Cannot delete: Some colleges have linked Admission Requests. Remove AdmissionRequests first.'
                ],
            ]
        ],
        'leads' => [
            'model' => \App\Models\Lead::class,
            'route' => 'leads.index',
            'name' => 'lead',
            'permission' => 'bulk-delete',
            // No dependencies for leads (or add as needed)
            'dependencies' => []
        ],
    ];

    public function delete(Request $request, string $resource)
    {
        if (!isset($this->resources[$resource])) {
            return redirect()->back()->with('error', 'Invalid resource.');
        }

        $config = $this->resources[$resource];

        // Permission check
        if (isset($config['permission']) && !auth()->user()->can($config['permission'])) {
            abort(403, 'Unauthorized action.');
        }

        // Call trait method with dependencies
        return $this->bulkDeleteGlobal(
            $request,
            $config['model'],
            $config['route'],
            $config['name'],
            $config['dependencies'] ?? []
        );
    }
}