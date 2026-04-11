<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiFeatureTest extends TestCase
{
    /**
     * Test the AI paths endpoint.
     */
    public function test_ai_paths_endpoint(): void
    {
        $response = $this->getJson('/api/ai/paths');

        $response->assertStatus(200)
            ->assertJsonStructure([
            'success',
            'data' => [
                'dynamic_discounting',
                'geospatial_search'
            ]
        ]);
    }

    /**
     * Test the discount calculator endpoint.
     */
    public function test_discount_calculator_endpoint(): void
    {
        $payload = [
            'original_price' => 10000,
            'stock' => 20,
            'category' => 'bakery',
            'pickup_deadline' => '2026-04-11 20:00:00',
            'created_at' => '2026-04-11 12:00:00',
        ];

        $response = $this->postJson('/api/ai/discount-calculator', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
            'success',
            'data' => [
                'original_price',
                'dynamic_price',
                'discount_percentage',
                'breakdown'
            ]
        ]);
    }
}