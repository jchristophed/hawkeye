<?php

namespace App\Repositories;

use App\Models\Tenant;

class TenantRepository implements TenantRepositoryInterface {

    protected $tenant;

    public function __construct(Tenant $tenant) {

        $this->tenant = $tenant;
    }

    public function index($residenceId) {

        return $this->tenant->where('residence_id', $residenceId)->orderBy('lastname', 'asc')->get();
    }

    // retourne la liste des locataires pour la liste déroulante d'un nouveau contrat
    public function indexForNewContract($residenceId) {

        return $this->tenant->where('residence_id', $residenceId)->orderBy('lastname', 'asc')->get()->pluck('full_name', 'id');
    }

    public function getNbByStatus($residenceId, $status) {

        return $this->tenant->where('residence_id', $residenceId)->where('status', $status)->count();
    }

    public function getNbByContract($residenceId, $contract) {

        return $this->tenant->where('residence_id', $residenceId)->where('contract', $contract)->count();
    }

    public function getById($id) {

        return $this->tenant->findOrFail($id);
    }

    private function save(Tenant $tenant, Array $inputs)
    {
        $tenant->firstname = $inputs['firstname'];
        $tenant->lastname = $inputs['lastname'];
        $tenant->birth_date = $inputs['birth_date'];
        $tenant->status = $inputs['status'];
        $tenant->contract = $inputs['contract'];
        $tenant->company = $inputs['company'];
        $tenant->residence_id = $inputs['residence_id'];

        $tenant->save();
    }

    public function getPaginate($n, $residenceId)
    {
        return $this->tenant->where('residence_id', $residenceId)->orderBy('lastname', 'asc')->paginate($n);
    }

    public function store(Array $inputs)
    {
        $tenant = new $this->tenant;

        $this->save($tenant, $inputs);

        return $tenant;
    }

    public function update($id, Array $inputs)
    {
        $this->save($this->getById($id), $inputs);
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
    }

}