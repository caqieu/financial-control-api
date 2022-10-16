<?php

namespace Tests\Feature;

use App\Models\Expenses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreatePatrimonyTest extends TestCase
{
    use RefreshDatabase;

    public function test_expenses_are_being_created_successfully()
    {
        $expensesData = [
            'descricao' => 'Gasolina',
            'valor' => 120,
            'data' => '2022-08-02',
            'categoria' => 'transporte',
        ];

        $expectedFields = ['descricao', 'valor', 'data', 'categoria', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('despesas', $expensesData, $expectedFields, $notExpectedFields);
    }

    public function test_incomes_are_being_created_successfully()
    {
        $incomesData = [
            'descricao' => 'Salário',
            'valor' => 1200,
            'data' => '2022-08-02'
        ];

        $expectedFields = ['descricao', 'valor', 'data', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('receitas', $incomesData, $expectedFields, $notExpectedFields);
    }

    public function test_already_created_patrimony_is_returning_error()
    {
        Expenses::factory()->create([
            'descricao' => 'Compras',
            'data' => '2022-10-04'
        ]);

        $expensesData = [
            'descricao' => 'Compras',
            'valor' => 120,
            'data' => '2022-10-05'
        ];

        $response = $this->postJson('api/despesas', $expensesData);

        $response
            ->assertStatus(409)
            ->assertSee(['error' => true]);
    }

    public function test_request_with_error_is_failing_validation()
    {
        $expansesData = [
            'valor' => 120,
            'data' => '2022-01-05'
        ];

        $response = $this->postJson('api/despesas', $expansesData);

        $response
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('error')
                    ->has('message')
                    ->has('data', function ($json) {
                        $json->hasAll(['descricao']);
                    });
            });
    }

    private function asserts(
        string $routeToQuery,
        array $dataToCreate,
        array $fieldsInResponse,
        array $fieldsThatAreNotInResponse
    ) {
        $response = $this->postJson('/api/' . $routeToQuery, $dataToCreate);

        $response
            ->assertCreated()
            ->assertJson(
                function (AssertableJson $json) use ($fieldsInResponse, $fieldsThatAreNotInResponse) {
                    $json
                        ->has('error')
                        ->has('message')
                        ->has(
                            'data',
                            function ($json) use ($fieldsInResponse, $fieldsThatAreNotInResponse) {
                                $json
                                    ->hasAll($fieldsInResponse)
                                    ->missingAll($fieldsThatAreNotInResponse);
                            }
                        );
                }
            );

        $id = $response['data']['id'];

        $this->assertDatabaseHas(
            $routeToQuery,
            ['id' => $id]
        );
    }
}
