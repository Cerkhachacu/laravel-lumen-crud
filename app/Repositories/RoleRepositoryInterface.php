<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function all(): Collection;

    public function paginator($limit= 5);

    public function show($id);

    public function store($data);

    public function update($data, $id);

    public function destroy($id);
}
