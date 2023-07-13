<?php declare(strict_types=1);

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PageTest extends TestCase
{
    /**
     * @test
     */
    public function test_get_index(): void
    {
        $response = $this->getJson('/');
        $content = json_decode($response->content());

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
                'data' => [
                    'name',
                ],
                'meta' => [
                    'version',
                ],
            ]);
        $this->assertIsString($content->data->name);
        $this->assertIsNumeric($content->meta->version);
    }
}
