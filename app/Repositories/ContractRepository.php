<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Models\Flat;
use App\Models\Tenant;

class ContractRepository implements ContractRepositoryInterface {
    
    protected $contract;

    public function __construct(Contract $contract) {

        $this->contract = $contract;
    }

    // SCOPES
    // --------------------

    // retourne uniquement les contrats d'une résidence
    private function scopeOnResidenceOnly($residenceId) {

        return $this->contract  ->join('flat', 'contract.flat_id', '=', 'flat.id')
                                ->join('tenant', 'contract.tenant_id', '=', 'tenant.id')
                                ->where('flat.residence_id', $residenceId);
    }

    // retourne uniquement les contrats en cours d'une résidence
    private function scopeRunningOnResidenceOnly($residenceId) {

        return $this    ->scopeOnResidenceOnly($residenceId)
                        ->where('contract.status', '!=', 'Annulé')
                        ->where('contract.start_date', '<=', date('Y-m-d'))
                        ->where(function ($query2) {
                                  $query2
                                      ->where('contract.end_date', '>=', date('Y-m-d'))
                                      ->orWhere('contract.end_date', '=', '0000-00-00');
                        });
    }

    // LISTINGS
    // --------------------

    // retourne tous les contrats d'une résidence
    public function index($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')->get();
    }

    // retourne tous les contrats en cours d'une résidence
    public function indexRunning($residenceId) {

        return $this->scopeRunningOnResidenceOnly($residenceId)->get();
    }

    // retourne tous les id des appartements ayant un contrat en cours sans preavis
    public function indexRunningWithoutWarningFlatId($residenceId) {

        return $this    ->scopeOnResidenceOnly($residenceId)
                        ->where('start_date', '<=', date('Y-m-d'))
                        ->where('end_date', '=', '0000-00-00')
                        ->select('contract.flat_id')
                        ->get();
    }

    // retourne tous les id des appartements reloués
    public function indexReletFlatId($residenceId) {

        return $this    ->scopeOnResidenceOnly($residenceId)
                        ->where('start_date', '>', date('Y-m-d'))
                        ->select('contract.flat_id')
                        ->get();
    }

    // retourne tous les id des appartements occupés et reloués
    public function indexRunningReletFlatId($residenceId) {

        return $this    ->scopeOnResidenceOnly($residenceId)
                        ->where(function ($query2) {
                            $query2
                                ->where('end_date', '>=', date('Y-m-d'))
                                ->orWhere('end_date', '=', '0000-00-00');
                        })
                        ->select('contract.flat_id')
                        ->get();
    }

    // retourne tous les id des appartements ayant un contrat en cours
    public function indexRunningFlatId($residenceId) {

        return $this->scopeRunningOnResidenceOnly($residenceId)->select('contract.flat_id')->get();
    }

    // retourne tous les id des locataires ayant un contrat en cours
    public function indexRunningTenantId($residenceId) {

        return $this->scopeRunningOnResidenceOnly($residenceId)->select('contract.tenant_id')->get();
    }

    // retourne les contrats incomplets
    public function indexIncomplete($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')->has('documents')->orderBy('tenant.lastname', 'asc')->get();
    }

    // retourne les contrats pré-réservés
    public function indexBooked($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')->where('contract.status', '=', \Lang::get('global.contract.option'))->orderBy('tenant.lastname', 'asc')->get();
    }

    // retourne les contrats dont le dossier n'a pas été remis
    public function indexUndeliveredFolders($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')->where('contract.folder', '=', 0)->orderBy('tenant.lastname', 'asc')->get();
    }

    // retourne les contrats d'un logement par date de début décroissante
    public function indexByFlatByStartDate(Flat $flat) {

        return $this->contract->where('flat_id', '=', $flat->id)->orderBy('start_date', 'desc')->get();
    }

    // retourne les contrats d'un locataire par date de début décroissante
    public function indexByTenantByStartDate(Tenant $tenant) {

        return $this->contract->where('tenant_id', '=', $tenant->id)->orderBy('start_date', 'desc')->get();
    }

    // COMPTEURS
    // --------------------

    // retourne le nombre de contrats avec des documents manquants
    public function getNbContracts($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->count();
    }

    // retourne le nombre de contrats incomplets
    public function getNbIncompleteContracts($residenceId) {

        return $this->indexIncomplete($residenceId)->count();
    }

    // retourne le nombre de contrats avec des documents manquants
    public function getNbCompleteContracts($residenceId) {

        return $this->getNbContracts($residenceId) - $this->getNbIncompleteContracts($residenceId);
    }

    // UNIQUE
    // --------------------

    // retourne un contrat en fonction de son id
    public function getById($id) {

        return $this->contract->findOrFail($id);
    }

    // retourne le contrat actif d'un logement
    public function getActiveByFlat($residenceId, Flat $flat) {

        return $this    ->scopeRunningOnResidenceOnly($residenceId)
                        ->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')
                        ->where('flat_id', '=',  $flat->id)->first();
    }

    // retourne le contrat actif d'un locataire
    public function getActiveByTenant($residenceId, Tenant $tenant) {

        return $this->scopeRunningOnResidenceOnly($residenceId)->where('tenant_id', '=', $tenant->id)->first();
    }

    // retourne le prochain contrat actif d'un logement
    public function getNextByFlat($residenceId, Flat $flat) {

        return $this    ->scopeOnResidenceOnly($residenceId)
                        ->select('contract.id', 'contract.start_date', 'contract.end_date', 'contract.price', 'contract.application_fee', 'contract.deposit', 'contract.mode_of_payment', 'contract.status', 'contract.flat_id', 'contract.tenant_id')
                        ->where('start_date', '>', date('Y-m-d'))
                        ->where('flat_id', '=',  $flat->id)
                        ->orderBy('start_date', 'asc')
                        ->first();
    }

    // GESTION
    // --------------------

    // hydrate un contrat depuis un formulaire
    private function save(Contract $contract, Array $inputs)
    {
        $contract->start_date = $inputs['start_date'];
        $contract->end_date = $inputs['end_date'];
        $contract->price = $inputs['price'];
        $contract->application_fee = $inputs['application_fee'];
        $contract->deposit = $inputs['deposit'];
        $contract->mode_of_payment = $inputs['mode_of_payment'];
        $contract->status = $inputs['status'];
        $contract->folder = $inputs['folder'];
        $contract->flat_id = $inputs['flat'];
        $contract->tenant_id = $inputs['tenant'];

        $contract->save();

        if (isset($inputs['documents'])) {
            $contract->documents()->sync($inputs['documents']);
        } else {
            $contract->documents()->detach();
        }
    }

    public function store(Array $inputs)
    {
        $contract = new $this->contract;

        $this->save($contract, $inputs);

        return $contract;
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