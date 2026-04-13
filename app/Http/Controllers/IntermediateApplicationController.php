<?php

namespace App\Http\Controllers;

use App\Models\IntermediateApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IntermediateApplicationController extends Controller
{
    /**
     * Display listing of intermediate applications.
     */
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $query = IntermediateApplication::with('intermediateApplicant');

        // Optional search filter
        if (! empty($filters['search'])) {
            $query->whereHas('intermediateApplicant', function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        $applications = $query
            ->orderBy('created_time', 'desc')
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'first_name' => $application->intermediateApplicant?->first_name ?? 'Unknown',
                    'last_name' => $application->intermediateApplicant?->last_name ?? '',
                    'applicant_name' => $application->fullApplicantName,
                    'project_name' => $application->projectName,
                    'position' => $application->position,
                    'application_stage' => $application->application_stage,
                    'remarks' => $application->remarks,
                ];
            });

        return Inertia::render('intermediate/applications/Index', [
            'applications' => $applications,
            'filters' => $filters,
            'userPermissions' => auth()->user()->permissions ?? 0,
            'errorsConfig' => [
                'field_required' => 'This field is required.',
                'file_too_large' => 'File size must not exceed 10MB.',
            ],
        ]);
    }
}
