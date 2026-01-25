<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to attach feedback to
        $users = User::all();

        if ($users->isEmpty()) {
            // fallback if no users exist
            $users = User::factory(3)->create();
        }

        $feedbacks = [
            [
                'title' => 'Dark mode flickers on page load',
                'description' => 'When I refresh the dashboard, the white background flashes for a split second before turning dark. It is quite jarring at night.',
                'type' => 'bug',
                'status' => 'in_progress',
                'days_ago' => 1,
            ],
            [
                'title' => 'Add ability to export citations to EndNote',
                'description' => 'I use EndNote for my university thesis. It would be great if we could direct export or at least have an EndNote XML format option.',
                'type' => 'feature',
                'status' => 'pending',
                'days_ago' => 2,
            ],
            [
                'title' => 'Mobile view navigation improvement',
                'description' => 'The sidebar on mobile is a bit hard to close once opened. Maybe add a swipe gesture or a larger close button?',
                'type' => 'improvement',
                'status' => 'pending',
                'days_ago' => 3,
            ],
            [
                'title' => 'Citation generator made a mistake with APA 7th',
                'description' => 'I noticed that for YouTube videos, the timestamp is missing in the generated citation. Please check the APA 7th rules for digital media.',
                'type' => 'bug',
                'status' => 'completed',
                'days_ago' => 5,
            ],
            [
                'title' => 'Project folders are not saving sort order',
                'description' => 'I tried to enabling custom sorting for my folders, but after I reload the page they go back to alphabetical order.',
                'type' => 'bug',
                'status' => 'in_progress',
                'days_ago' => 6,
            ],
            [
                'title' => 'Integration with Google Drive',
                'description' => 'Allow us to import PDF files directly from Google Drive into our project library. This would save so much time downloading and re-uploading.',
                'type' => 'feature',
                'status' => 'pending',
                'days_ago' => 7,
            ],
            [
                'title' => 'Make font size adjustable in the reader',
                'description' => 'The text in the PDF reader view is a bit small on my laptop screen. A zoom or font size toggle would be very helpful.',
                'type' => 'improvement',
                'status' => 'completed',
                'days_ago' => 10,
            ],
            [
                'title' => 'Login page password visibility toggle',
                'description' => 'Please add an eye icon to show/hide the password on the login screen. I keep mistyping my password.',
                'type' => 'improvement',
                'status' => 'completed',
                'days_ago' => 12,
            ],
            [
                'title' => '500 Error when uploading large files',
                'description' => 'Tried invalid uploading a 25MB PDF and got a server error. Is there a file size limit? If so, the error message should be clearer.',
                'type' => 'bug',
                'status' => 'closed',
                'days_ago' => 14,
            ],
            [
                'title' => 'Add more color options for project tags',
                'description' => 'The current palette is a bit limited. I need more pastel colors to organize my workflow better.',
                'type' => 'improvement',
                'status' => 'pending',
                'days_ago' => 15,
            ],
            [
                'title' => 'BibTeX export missing abstract field',
                'description' => 'When I export to BibTeX, the abstract field is empty even though I have it filled in the reference details.',
                'type' => 'bug',
                'status' => 'in_progress',
                'days_ago' => 18,
            ],
            [
                'title' => 'Collaborative editing for notes',
                'description' => 'My team wants to edit the same note at the same time. Real-time collaboration like Google Docs would be a game changer!',
                'type' => 'feature',
                'status' => 'pending',
                'days_ago' => 20,
            ],
            [
                'title' => 'Search bar allows special characters crashing',
                'description' => 'If I type specific emoji or special characters into the main search, the app crashes to a white screen.',
                'type' => 'bug',
                'status' => 'completed',
                'days_ago' => 25,
            ],
            [
                'title' => 'Dashboard widgets customization',
                'description' => 'I want to be able to hide the "Recent Activity" widget because I don\'t really use it. Let us drag and drop widgets.',
                'type' => 'feature',
                'status' => 'pending',
                'days_ago' => 28,
            ],
            [
                'title' => 'Incorrect date format in exported Word doc',
                'description' => 'The date format in the docx export is MM/DD/YYYY but my settings are set to DD/MM/YYYY.',
                'type' => 'bug',
                'status' => 'closed',
                'days_ago' => 30,
            ],
        ];

        foreach ($feedbacks as $feedback) {
            Feedback::create([
                'user_id' => $users->random()->id,
                'title' => $feedback['title'],
                'description' => $feedback['description'],
                'type' => $feedback['type'],
                'status' => $feedback['status'],
                'created_at' => Carbon::now()->subDays($feedback['days_ago']),
                'updated_at' => Carbon::now()->subDays($feedback['days_ago']),
            ]);
        }
    }
}
