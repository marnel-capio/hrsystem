<?php

namespace App\Http\Controllers;

use App\Models\IntermediateApplicant;
use Inertia\Inertia;

class IntermediateApplicantController extends Controller
{
    public function index()
    {
        $applicants = IntermediateApplicant::with('skills')
            ->orderBy('created_time', 'desc')
            ->get();

        return Inertia::render('intermediate/applicants/IntermediateApplicantList', [
            'applicants' => $applicants,
            'userPermissions' => auth()->user()->permissions,
        ]);
    }

}
