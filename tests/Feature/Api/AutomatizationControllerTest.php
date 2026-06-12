<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Services\AutomatizationProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomatizationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.n8n.header_name' => 'X-N8N-Token',
            'services.n8n.token' => 'secret-token',
        ]);
    }

    public function test_process_returns_401_without_valid_n8n_token(): void
    {
        $response = $this->postJson('/api/automatizations/process', [], [
            'X-N8N-Token' => 'wrong-token',
        ]);

        $response->assertUnauthorized();
    }

    public function test_process_returns_500_when_n8n_not_configured(): void
    {
        config(['services.n8n.token' => null]);

        $response = $this->postJson('/api/automatizations/process', [], [
            'X-N8N-Token' => 'secret-token',
        ]);

        $response->assertStatus(500);
    }

    public function test_process_returns_json_with_processed_count(): void
    {
        $this->mock(AutomatizationProcessor::class, function ($mock): void {
            $mock->shouldReceive('processDueAutomatizations')
                ->once()
                ->andReturn([
                    ['success' => true, 'automatization_id' => 1],
                ]);
        });

        $response = $this->postJson('/api/automatizations/process', [], [
            'X-N8N-Token' => 'secret-token',
        ]);

        $response->assertOk();
        $response->assertJson([
            'processed' => 1,
        ]);
        $response->assertJsonStructure(['processed', 'results']);
    }
}
