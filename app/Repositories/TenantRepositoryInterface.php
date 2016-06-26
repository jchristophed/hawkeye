<?php

namespace App\Repositories;

interface TenantRepositoryInterface
{
    public function index($residenceId);
    public function getById($id);
    public function getPaginate($n, $residenceId);
    public function store(Array $inputs);
    public function update($id, Array $inputs);
    public function destroy($id);
}