<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Folder;
use App\Models\Reference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or Reset Admin User
        $user = User::where('email', 'admin@example.com')->first();

        if ($user) {
            // Optional: Cleaning up old data for a fresh start with this seeder
            $user->references()->delete();
            $user->projects()->each(function ($project) {
                $project->folders()->delete();
                $project->delete();
            });
        } else {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // 2. Create Projects & Folders

        // Project 1: Thesis Research
        $thesisProject = Project::create([
            'user_id' => $user->id,
            'name' => 'Thesis Research',
            'description' => 'References for my master thesis on AI',
            'icon' => 'Book',
            'color' => '#3B82F6' // Blue
        ]);

        $introFolder = Folder::create([
            'user_id' => $user->id,
            'project_id' => $thesisProject->id,
            'name' => 'Introduction',
            'color' => '#EF4444' // Red
        ]);

        $litReviewFolder = Folder::create([
            'user_id' => $user->id,
            'project_id' => $thesisProject->id,
            'name' => 'Literature Review',
            'color' => '#10B981' // Green
        ]);

        // Project 2: History Assignment
        $historyProject = Project::create([
            'user_id' => $user->id,
            'name' => 'World War II History',
            'description' => 'Sources for the final history essay',
            'icon' => 'Clock',
            'color' => '#F59E0B' // Amber
        ]);

        // 3. Create References

        // Ref 1: Book (AI)
        $ref1 = Reference::create([
            'user_id' => $user->id,
            'type' => 'book',
            'title' => 'Artificial Intelligence: A Modern Approach',
            'authors' => ['Russell, Stuart', 'Norvig, Peter'],
            'year' => '2020',
            'publisher' => 'Pearson',
            'edition' => '4th',
            'isbn' => '978-0134610993',
            'sort_order' => 1
        ]);
        $thesisProject->references()->attach($ref1->id);
        $introFolder->references()->attach($ref1->id);

        // Ref 2: Journal (Deep Learning)
        $ref2 = Reference::create([
            'user_id' => $user->id,
            'type' => 'journal',
            'title' => 'Deep Learning',
            'authors' => ['LeCun, Yann', 'Bengio, Yoshua', 'Hinton, Geoffrey'],
            'year' => '2015',
            'journal_name' => 'Nature',
            'volume' => '521',
            'issue' => '7553',
            'pages' => '436-444',
            'doi' => '10.1038/nature14539',
            'sort_order' => 2
        ]);
        $thesisProject->references()->attach($ref2->id);
        $litReviewFolder->references()->attach($ref2->id);

        // Ref 3: Website (TechCrunch)
        $ref3 = Reference::create([
            'user_id' => $user->id,
            'type' => 'website',
            'title' => 'OpenAI releases GPT-4',
            'authors' => ['Coldewey, Devin'],
            'year' => '2023',
            'url' => 'https://techcrunch.com/2023/03/14/openai-releases-gpt-4-ai/',
            'publisher' => 'TechCrunch',
            'sort_order' => 3
        ]);
        $thesisProject->references()->attach($ref3->id);

        // Ref 4: Book (History)
        $ref4 = Reference::create([
            'user_id' => $user->id,
            'type' => 'book',
            'title' => 'The Second World War',
            'authors' => ['Beevor, Antony'],
            'year' => '2012',
            'publisher' => 'Little, Brown and Company',
            'isbn' => '978-0316023740',
            'sort_order' => 1
        ]);
        $historyProject->references()->attach($ref4->id);

        // Ref 5: Journal (History)
        $ref5 = Reference::create([
            'user_id' => $user->id,
            'type' => 'journal',
            'title' => 'The Origins of the Second World War in Asia and the Pacific',
            'authors' => ['Iriye, Akira'],
            'year' => '1987',
            'journal_name' => 'Journal of American History',
            'sort_order' => 2
        ]);
        $historyProject->references()->attach($ref5->id);

        // Ref 6: Conference
        $ref6 = Reference::create([
            'user_id' => $user->id,
            'type' => 'conference',
            'title' => 'Attention Is All You Need',
            'authors' => ['Vaswani, Ashish', 'Shazeer, Noam', 'Parmar, Niki', 'Uszkoreit, Jakob', 'Jones, Llion', 'Gomez, Aidan N.', 'Kaiser, Lukasz', 'Polosukhin, Illia'],
            'year' => '2017',
            'publisher' => 'NIPS',
            'sort_order' => 4
        ]);
        $thesisProject->references()->attach($ref6->id);
        $litReviewFolder->references()->attach($ref6->id);

        // Random unassigned reference
        Reference::create([
            'user_id' => $user->id,
            'type' => 'website',
            'title' => 'Laravel Documentation',
            'authors' => ['Otwell, Taylor'],
            'year' => '2023',
            'url' => 'https://laravel.com/docs',
            'publisher' => 'Laravel',
            'sort_order' => 0
        ]);

        // Bulk seed for testing storage limits (~450 more)
        for ($i = 1; $i <= 450; $i++) {
            $ref = Reference::create([
                'user_id' => $user->id,
                'type' => rand(0, 1) ? 'book' : 'journal',
                'title' => 'Advanced Research Study Vol. ' . $i,
                'authors' => ['Researcher ' . $i, 'Collaborator ' . $i],
                'year' => rand(2010, 2024),
                'publisher' => 'Academic Publishing House',
                'sort_order' => $i + 10
            ]);
            $thesisProject->references()->attach($ref->id);
        }
    }
}
