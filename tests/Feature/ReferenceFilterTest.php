<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Folder;
use App\Models\Reference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferenceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_references_by_folder()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project'
        ]);

        $folder = Folder::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'name' => 'Test Folder'
        ]);

        // Ref in folder
        $refInFolder = Reference::create([
            'user_id' => $user->id,
            'title' => 'In Folder',
            'type' => 'book'
        ]);
        $project->references()->attach($refInFolder);
        $folder->references()->attach($refInFolder);

        // Ref in project only
        $refInProject = Reference::create([
            'user_id' => $user->id,
            'title' => 'In Project Only',
            'type' => 'book'
        ]);
        $project->references()->attach($refInProject);

        // Request with folder_id
        $response = $this->get('/references?project_id=' . $project->id . '&folder_id=' . $folder->id);

        $response->assertStatus(200);

        // Assert Inertia prop 'references' contains only refInFolder
        $references = $response->viewData('page')['props']['references'];

        $this->assertEquals(1, count($references));
        $this->assertEquals('In Folder', $references[0]['title']);
    }
}
