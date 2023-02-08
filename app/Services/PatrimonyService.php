<?php

namespace App\Services;

use App\Models\Patrimony;
use Illuminate\Support\Collection;

class PatrimonyService
{
    private Patrimony $modelInstance;

    public function __construct(Patrimony $patrimony)
    {
        $this->modelInstance = $patrimony;
    }

    public function find(int $id)
    {
        $patrimony = $this->modelInstance->find($id);

        return $patrimony;
    }

    public function get(?string $description)
    {
        $model = $this->modelInstance;

        if ($description) {
            $model = $model->where('descricao', $description);
        }

        return $model->get();
    }

    public function create(array $data): Patrimony
    {
        return $this->modelInstance->create($data);
    }

    public function update(Patrimony $patrimony, array $data): bool
    {
        return $patrimony->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->modelInstance
            ->where('id', $id)
            ->delete();
    }

    public function getByDate(int $year, int $month): Collection
    {
        return $this->modelInstance
            ->whereYear('data', $year)
            ->whereMonth('data', $month)
            ->get();
    }

    public function getWithFilters(array $fields, int $year = 0, int $month = 0, string $description = '', int $id = 0)
    {
        $model = $this->modelInstance->select($fields);

        if ($year !== 0) {
            $model = $model->whereYear('data', $year);
        }

        if ($month !== 0) {
            $model = $model->whereMonth('data', $month);
        }

        if (!empty($description)) {
            $model = $model->whereDescricao($description);
        }

        if ($id !== 0) {
            $model = $model->where('id', '<>', $id);
        }

        return $model->get();
    }
}
