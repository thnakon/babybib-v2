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
            'tone' => 'nullable|string',
        ]);

        $tone = $request->tone ?? 'Academic/Professional';

        $prompt = "Paraphrase the following research text. 
                  Tone: {$tone}. 
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

    public function chat()
    {
        return Inertia::render('ai/chat');
    }

    public function chatAction(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
        ]);

        $user = Auth::user();
        $references = Reference::where('user_id', $user->id)->get();

        // Create context from references
        $context = "The user has the following research references in their library:\n";
        foreach ($references as $index => $ref) {
            $authors = is_array($ref->authors) ? implode(', ', $ref->authors) : $ref->authors;
            $context .= ($index + 1) . ". Title: {$ref->title}, Authors: {$authors}, Year: {$ref->year}, Type: {$ref->type}, Abstract: " . mb_substr($ref->abstract ?? 'No abstract', 0, 100) . "...\n";
        }

        $systemInstruction = "
            You are 'AI Agent', a premium academic research assistant. 
            Your goal is to help researchers manage their library, synthesize information, and improve their writing.
            
            CONTEXT:
            {$context}
            
            GUIDELINES:
            1. Use the provided context to answer questions about the user's specific research data.
            2. Be highly professional, accurate, and helpful.
            3. If the user asks for a summary of references on a topic, scan the provided context and highlight matching items.
            4. If the user asks for help with writing, provide academic-grade suggestions.
            5. Respond in the same language the user uses (Thai or English).
        ";

        $prompt = $request->message;

        // Basic history integration if provided
        if ($request->history) {
            $historyText = "PREVIOUS CONVERSATION:\n";
            foreach ($request->history as $msg) {
                $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
                $historyText .= "{$role}: {$msg['content']}\n";
            }
            $prompt = $historyText . "\nNEW MESSAGE: " . $prompt;
        }

        $result = $this->gemini->generate($prompt, $systemInstruction);

        return response()->json(['result' => $result]);
    }
}
