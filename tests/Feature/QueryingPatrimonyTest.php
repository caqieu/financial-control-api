<?php

namespace Tests\Feature;

use App\Models\Expenses;
use App\Models\Incomes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QueryingPatrimonyTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_expanses_is_querying_successfully()
    {
        Expenses::factory(5)->create();

        $expectedFields = ['descricao', 'valor', 'data', 'categoria', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('despesas', 5, $expectedFields, $notExpectedFields);
    }

    public function test_get_incomes_is_querying_successfully()
    {
        Incomes::factory(5)->create();

        $expectedFields = ['descricao', 'valor', 'data', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('receitas', 5, $expectedFields, $notExpectedFields);
    }

    public function test_get_by_description_is_querying_successfully()
    {
        Expenses::factory(3)->create(['descricao' => 'Compras']);
        Expenses::factory(5)->create(['descricao' => 'Farmácia']);

        $expectedFields = ['descricao', 'valor', 'data', 'categoria', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('despesas?descricao=Compras', 3, $expectedFields, $notExpectedFields);
    }

    public function test_get_by_date_is_querying_successfully()
    {
        $firstDate = '2022-08-04';
        $secondDate = '2022-10-04';

        Incomes::factory(5)->create(['data' => $firstDate]);
        Incomes::factory(3)->create(['data' => $secondDate]);

        $expectedFields = ['descricao', 'valor', 'data', 'id'];
        $notExpectedFields = ['created_at', 'deleted_at', 'updated_at'];

        $this->asserts('receitas/2022/10', 3, $expectedFields, $notExpectedFields);
    }

    public function test_find_is_querying_successfully()
    {
        $expense = Expenses::factory()->create();

        $response = $this->get('api/despesas/' . $expense->id);

        $response
            ->assertJson(function (AssertableJson $json) use ($expense) {
                $json
                    ->has('error')
                    ->has('data')
                    ->whereContains('data', $expense->toArray());
            });
    }

    public function test_summary_is_returning_correct_results()
    {
        $expensesData = [
            [
                'categoria' => 'saude',
                'valor' => 120,
                'data' => '2022-10-01'
            ],
            [
                'categoria' => 'alimentacao',
                'valor' => 200,
                'data' => '2022-10-02'
            ],
            [
                'categoria' => 'transporte',
                'valor' => 400,
                'data' => '2022-10-04'
            ],
            [
                'categoria' => 'educacao',
                'valor' => 50,
                'data' => '2022-10-05'
            ],
            [
                'categoria' => 'imprevistos',
                'valor' => 90,
                'data' => '2022-10-09'
            ],
        ];

        $incomesData = [
            [
                'valor' => 200,
                'data' => '2022-10-01'
            ],
            [
                'valor' => 50,
                'data' => '2022-10-02'
            ],
            [
                'valor' => 10,
                'data' => '2022-10-04'
            ],
            [
                'valor' => 30,
                'data' => '2022-10-05'
            ],
            [
                'valor' => 100,
                'data' => '2022-10-09'
            ],
        ];

        $totalExpenses = array_sum(array_column($expensesData, 'valor'));
        $totalIncomes = array_sum(array_column($incomesData, 'valor'));

        Expenses::factory()->createMany($expensesData);
        Incomes::factory()->createMany($incomesData);

        $response = $this->get('api/resumo/2022/10');

        $response
            ->assertSuccessful()
            ->assertJson([
                'error' => false,
                'data' => [
                    'receitas' => $totalIncomes,
                    'despesas' => $totalExpenses,
                    'saldo' => $totalIncomes - $totalExpenses,
                    'categorias' => [
                        'saude' => 120,
                        'alimentacao' => 200,
                        'transporte' => 400,
                        'educacao' => 50,
                        'imprevistos' => 90,
                    ]
                ]
            ]);
    }

    private function asserts(
        string $routeToQuery,
        int $numberOfResults,
        array $fieldsInResponse,
        array $fieldsThatAreNotInResponse
    ) {
        $response = $this->get('/api/' . $routeToQuery);

        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($fieldsInResponse, $fieldsThatAreNotInResponse, $numberOfResults) {
                    $json
                        ->has('error')
                        ->has(
                            'data',
                            $numberOfResults,
                            function ($json) use ($fieldsInResponse, $fieldsThatAreNotInResponse) {
                                $json
                                    ->hasAll($fieldsInResponse)
                                    ->missingAll($fieldsThatAreNotInResponse);
                            }
                        );
                }
            );
    }
}
