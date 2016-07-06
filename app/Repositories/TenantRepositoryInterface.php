<?php

namespace App\Repositories;

interface TenantRepositoryInterface
{
    public function index($residenceId);
    public function indexForNewContract($residenceId);
    public function indexTodayBirthday($residenceId);

    public function getNbByStatus($residenceId, $status);
    public function getNbByContract($residenceId, $contract);

    public function getById($id);

    public function store(Array $inputs);
    public function update($id, Array $inputs);
    public function destroy($id);
}