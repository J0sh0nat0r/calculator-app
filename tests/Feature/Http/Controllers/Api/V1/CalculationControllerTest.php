<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\CalculationController;
use App\Math\Ast\Binary\Mul;
use App\Math\Contracts\Calculator;
use App\Models\Calculation;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;

mutates(CalculationController::class);

test('index', function (): void {
    Calculation::factory(2)->create();

    /** @var Illuminate\Testing\TestResponse $response */
    $response = $this->get('/api/v1/calculations');

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 2)->has('links')->has('meta'));
});

describe('store', function (): void {
    it('validates expr', function (): void {
        /** @var Illuminate\Testing\TestResponse $response */
        $response = $this->postJson('/api/v1/calculations', [
            'expr' => '3 ? 36',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('expr');
    });

    it('returns calculated result', function (): void {
        $this->mock(
            Calculator::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('evaluate')
                ->withArgs(fn ($arg) => $arg instanceof Mul)
                ->andReturn('666')
        );

        /** @var Illuminate\Testing\TestResponse $response */
        $response = $this->postJson('/api/v1/calculations', [
            'expr' => '333 * 2',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('data', fn ($json) => $json
                    ->has('id')
                    ->where('expr.raw', '333 * 2')
                    ->where('result', '666')
                )
            );

        $data = $response->json('data');

    });

    it('persists calculations', function (): void {
        $data = $this->postJson('/api/v1/calculations', [
            'expr' => '610 + 377',
        ])->json('data');

        $this->assertDatabaseHas('calculations', [
            'id' => $data['id'],
            'expr' => $data['expr']['raw'],
            'result' => $data['result'],
        ]);
    });
});

test('show', function (): void {
    $calculation = Calculation::factory()->createOne();

    /** @var Illuminate\Testing\TestResponse $response */
    $response = $this->get("/api/v1/calculations/$calculation->id");

    $response
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json->has('data', fn (AssertableJson $json) => $json
                ->where('id', (string) $calculation->id)
                ->has('expr', fn (AssertableJson $json) => $json
                    ->has('ast')
                    ->where('raw', (string) $calculation->expr)
                )
                ->where('result', $calculation->result)
            )
        );
});

test('destroy', function (): void {
    $calculation = Calculation::factory()->createOne();

    /** @var Illuminate\Testing\TestResponse $response */
    $response = $this->delete("/api/v1/calculations/$calculation->id");

    $response->assertStatus(204);
    $this->assertDatabaseMissing($calculation);
});

it('disallows allow PUT', function (): void {
    $calculation = Calculation::factory()->createOne();

    /** @var Illuminate\Testing\TestResponse $response */
    $response = $this->put("/api/v1/calculations/$calculation->id", ['expr' => '1']);

    $response->assertMethodNotAllowed();
});

it('disallows allow PATCH', function (): void {
    $calculation = Calculation::factory()->createOne();

    /** @var Illuminate\Testing\TestResponse $response */
    $response = $this->patch("/api/v1/calculations/$calculation->id", ['expr' => '1']);

    $response->assertMethodNotAllowed();
});
