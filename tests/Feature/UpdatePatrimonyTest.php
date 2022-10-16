<?php

namespace Tests\Feature;

use App\Models\Expenses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdatePatrimonyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_patrimony_is_updated_successfully()
    {
        $expense = Expenses::factory()
            ->create([
                'descricao' => 'Farmácia',
                'valor' => 200,
                'data' => '2022-09-05',
                'categoria' => 'saude'
            ]);

        $dataToUpdate = [
            'descricao' => 'Compras',
            'valor' => 120,
            'data' => '2022-09-05',
            'categoria' => 'alimentacao'
        ];

        $response = $this->putJson('api/despesas/' . $expense->id, $dataToUpdate);

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('error')
                    ->has('message');
            });

        $this->assertDatabaseHas(
            'despesas',
            [
                'id' => $expense->id,
                'categoria' => 'Alimentação'
            ] + $dataToUpdate
        );
    }
}
