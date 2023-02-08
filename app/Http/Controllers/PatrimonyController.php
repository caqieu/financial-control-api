<?php

namespace App\Http\Controllers;

use App\Models\Patrimony;
use App\Services\PatrimonyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class PatrimonyController
{
    protected PatrimonyService $serviceInstance;
    protected string $modelName;
    protected array $createRules;
    protected array $updateRules;

    public function __construct(PatrimonyService $service, string $modelName)
    {
        $this->serviceInstance = $service;
        $this->modelName = $modelName;
    }

    public function get(Request $request): JsonResponse
    {
        $description = $request->input('descricao');

        return response()->json([
            'error' => false,
            'data' => $this->serviceInstance->get($description)
        ]);
    }

    public function getByDate(int $year, int $month)
    {
        return response()->json([
            'error' => false,
            'data' => $this->serviceInstance->getByDate($year, $month)
        ]);
    }

    public function find(int $id): JsonResponse
    {
        $patrimony = $this->serviceInstance->find($id);

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

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' criada com sucesso.',
            'data' => $this->serviceInstance->create($params)
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $params = $request->validate($this->updateRules, $request->all());

        $patrimony = $this->serviceInstance->find($id);

        $this->checkDuplicated(
            $params['descricao'] ?? $patrimony->descricao,
            $params['data'] ?? $patrimony->data,
            $id
        );

        $this->serviceInstance->update($patrimony, $params);

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' atualizada com sucesso.',
        ]);
    }

    protected function checkDuplicated(string $description, string $date, int $id = 0)
    {
        $month = date('m', strtotime($date));

        $duplicated = $this->serviceInstance->getWithFilters(['id'], 0, $month, $description, $id);

        if (!$duplicated->isEmpty()) {
            throw new ConflictHttpException($this->modelName . ' já criada no mês ' . $month);
        }

        return false;
    }

    public function delete(int $id)
    {
        if (!$this->serviceInstance->delete($id)) {
            throw new NotFoundHttpException($this->modelName . ' não encontrada');
        }

        return response()->json([
            'error' => false,
            'message' => $this->modelName . ' excluída com sucesso.'
        ]);
    }
}
