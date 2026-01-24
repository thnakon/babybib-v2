<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\GeminiService;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;

class AiToolController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function paraphraser()
    {
        return Inertia::render('ai/paraphraser');
    }

    public function paraphraseAction(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:5000',
            'tone' => 'required|string',
        ]);

        $prompt = "Paraphrase the following research text. 
                  Tone: {$request->tone}. 
                  Requirements: Keep the core meaning, use academic vocabulary, and ensure the flow is natural for a peer-reviewed journal.
                  Text: {$request->text}";

        $systemInstruction = "You are an expert academic editor. Your goal is to improve the clarity, impact, and formality of research writing while strictly maintaining the original intent.";

        $result = $this->gemini->generate($prompt, $systemInstruction);

        return response()->json(['result' => $result]);
    }

    public function literatureMap()
    {
        $references = Reference::where('user_id', Auth::id())->get();
        return Inertia::render('ai/literature-map', [
            'references' => $references
        ]);
    }

    public function templates()
    {
        return Inertia::render('ai/templates');
    }
}
