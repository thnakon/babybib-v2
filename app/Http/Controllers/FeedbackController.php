<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('feedback/index', [
            'feedbacks' => $feedbacks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bug,feature,improvement',
        ]);

        Auth::user()->feedbacks()->create($validated);

        return back()->with('success', 'Feedback submitted successfully.');
    }
}
