<?php

namespace App\Http\Controllers;

use App\Models\Patrimony;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class PatrimonyController
{
    protected Patrimony $modelInstance;
    protected string $modelName;
    protected array $createRules;
    protected array $updateRules;

    public function __construct(Patrimony $modelInstance, string $modelName)
    {
        $this->modelInstance = $modelInstance;
        $this->modelName = $modelName;
    }

    public function get(Request $request): JsonResponse
    {
        $model = $this->modelInstance;
        $description = $request->input('descricao');

        if ($description) {
            $model = $model->where('descricao', $description);
        }

        return response()->json([
            'error' => false,
            'data' => $model->get()
        ]);
    }

    public function find(int $id): JsonResponse
    {
        $patrimony = $this->modelInstance->find($id);

        if ($patrimony == null) {
            throw new NotFoundHttpException($this->modelName . ' não encontrada');
        }

        return response()->json([
            'error' => false,
            'data' => $patrimony
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $params = $request->validate($this->createRules);

        $this->checkDuplicated($params['descricao'], $params['data']);

        $data = $this->modelInstance->create($this->modelInstance->formatFields($params));

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' criada com sucesso.',
            'data' => $data
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $params = $request->validate($this->updateRules);

        $patrimony = $this->modelInstance->find($id);

        if ($patrimony == null) {
            throw new NotFoundHttpException($this->modelName . ' não encontrada');
        }

        $this->checkDuplicated($params['descricao'], $params['data'], $id);

        $patrimony->update($this->modelInstance->formatFields($params));

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' atualizada com sucesso.',
            'data' => $patrimony
        ]);
    }

    public function delete(int $id)
    {
        $error = !$this->modelInstance
            ->where('id', $id)
            ->delete();

        if ($error) {
            throw new NotFoundHttpException($this->modelName . ' não encontrada');
        }

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' excluída com sucesso.'
        ]);
    }

    protected function checkDuplicated(string $description, string $date, int $id = null): bool
    {
        $month = date('m', strtotime($date));

        $duplicated = $this->modelInstance
            ->select('id')
            ->where('descricao', $description)
            ->whereMonth('data', $month)
            ->when($id, fn($q) => $q->where('id', '<>', $id))
            ->exists();

        if ($duplicated) {
            throw new ConflictHttpException($this->modelName . ' já criada no mês ' . $month);
        }

        return false;
    }
}
