<?php

namespace App\Repositories;

use App\Models\Flat;
use App\Models\Tenant;

interface ContractRepositoryInterface
{
    public function index($residenceId);
    public function indexRunning($residenceId);
    public function indexIncomplete($residenceId);
    public function indexByFlatByStartDate(Flat $flat);
    public function indexByTenantByStartDate(Tenant $tenant);

    public function getNbContracts($residenceId);
    public function getNbIncompleteContracts($residenceId);
    public function getNbCompleteContracts($residenceId);

    public function getById($id);
    public function getActiveByFlat($residenceId, Flat $flat);
    public function getActiveByTenant($residenceId, Tenant $tenant);
    public function store(Array $inputs);
    public function update($id, Array $inputs);
    public function destroy($id);

}