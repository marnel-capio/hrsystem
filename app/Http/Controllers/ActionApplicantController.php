<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ActionApplicantController extends Controller
{
    public function create()
    {
        return Inertia::render('action/applicants/Register');
    }

    public function index()
    {
        return Inertia::render('action/applicants/Index');
    }
}
