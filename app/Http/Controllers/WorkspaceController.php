<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    /**
     * Display the integrated workspace.
     */
    public function index(): Response
    {
        return Inertia::render('workspace/index');
    }
}
