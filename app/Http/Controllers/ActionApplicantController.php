<?php

namespace App\Http\Controllers;

use App\Models\ActionApplicant;
use Inertia\Inertia;

class ActionApplicantController extends Controller
{
    public function index()
    {
        $applicants = ActionApplicant::getAllActionApplicants();

        return Inertia::render('action/applicants/Index', [
            'applicants' => $applicants,
        ]);
    }
}