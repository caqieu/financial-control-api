<?php

namespace Tests\Feature;

use App\Models\Expenses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePatrimonyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_patrimony_is_successfully_deleted()
    {
        $expense = Expenses::factory()->create();

        $response = $this->delete('api/despesas/' . $expense->id);

        $response
            ->assertSuccessful()
            ->assertJson([
                'error' => false,
                'message' => 'Despesa excluída com sucesso.'
            ]);

        $this->assertSoftDeleted($expense);
    }

    public function test_request_return_not_found_on_trying_to_delete_nonexistent_patrimony()
    {
        Expenses::query()->delete();

        $response = $this->delete('api/despesas/' . 1);

        $response->assertNotFound();
    }
}
