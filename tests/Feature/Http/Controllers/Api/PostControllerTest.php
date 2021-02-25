<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        //$this ->withoutExceptionHandling();
        $response = $this->json('POST', '/api/posts', [
            'title' => 'El post de prueba'
        ]);

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
                ->assertJson(['title' => 'El post de prueba'])
                ->assertStatus(201); //OK, creado un recurso

                $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']);
    }
    public function test_validate_title()
    {
        $response = $this->json('POST', '/api/posts', [
            'title' => ''
        ]);

        //Estatus HTT 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors('title');

        }

    public function test_show()
    {
      $post = factory(Post::class)->create();
      $response = $this->json('GET', "/api/posts/$post->id"); //id = 1
        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])
        ->assertJson(['title' => $post->title])
        ->assertStatus(200); //OK, creado un recurso
    }



    public function test_404_show()
    {

      $response = $this->json('GET', '/api/posts/1000'); //id = 1
        $response->assertStatus(404); //OK, creado un recurso
    }

}
