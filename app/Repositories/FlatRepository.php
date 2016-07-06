<?php

namespace App\Repositories;

use App\Models\Flat;

class FlatRepository implements FlatRepositoryInterface {

    protected $flat;
    protected $contractRepository;

    public function __construct(Flat $flat, ContractRepositoryInterface $contractRepositoryInterface) {

        $this->flat = $flat;
        $this->contractRepository = $contractRepositoryInterface;
    }

    // SCOPES
    // --------------------

    // retourne uniquement les logements d'une résidence
    private function scopeOnResidenceOnly($residenceId) {

        return $this->flat->where('flat.residence_id', $residenceId);
    }

    // LISTING
    // --------------------

    // retourne la liste des logements d'une résidence
    public function index($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->orderBy('block', 'asc')->orderBy('floor', 'asc')->orderBy('floor', 'asc')->orderBy('name', 'asc')->get();
    }

    // retourne la liste des logements pour la liste déroulante d'un nouveau contrat
    public function indexForNewContract($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->orderBy('name', 'asc')->get()->pluck('name', 'id');
    }

    // retourne la liste des logements inoccupés
    public function indexUnoccupied($residenceId) {

        return  $this->scopeOnResidenceOnly($residenceId)
                ->select('flat.id', 'flat.block', 'flat.floor', 'flat.name', 'flat.price', 'flat.area', 'flat.view')
                ->whereNotIn('flat.id', $this->contractRepository->indexRunningFlatId($residenceId))
                ->get();
    }

    // retourne la liste des logements inoccupés maintenant et a l'avenir
    public function indexUnoccupiedNowAndFuture($residenceId) {

        return  $this->scopeOnResidenceOnly($residenceId)
            ->select('flat.id', 'flat.block', 'flat.floor', 'flat.name', 'flat.price', 'flat.area', 'flat.view')
            ->whereNotIn('flat.id', $this->contractRepository->indexRunningAndFutureFlatId($residenceId))
            ->get();
    }

    // retourne la liste des logements avec préavis non reloués
    public function indexWarningNotRelet($residenceId) {

        return  $this->scopeOnResidenceOnly($residenceId)
            ->select('flat.id', 'flat.block', 'flat.floor', 'flat.name', 'flat.price', 'flat.area', 'flat.view')
            ->whereIn('flat.id', $this->contractRepository->indexRunningAndFutureFlatId($residenceId))
            ->whereNotIn('flat.id', $this->contractRepository->indexRunningWithoutWarningFlatId($residenceId))
            ->whereNotIn('flat.id', $this->contractRepository->indexFutureFlatId($residenceId))
            ->get();
    }

    // COMPTEURS
    // --------------------

    // retourne le nombre de logements
    public function getNbFlats($residenceId) {
        return $this->scopeOnResidenceOnly($residenceId)->count();
    }

    // retourne le nombre de logements inoccupés
    public function getNbFreeFlats($residenceId) {

        return $this->indexUnoccupied($residenceId)->count();
    }

    // retourne le nombre de logements inoccupés
    public function getNbFreeFlatsAndFuture($residenceId) {

        return $this->indexUnoccupiedNowAndFuture($residenceId)->count();
    }

    // retourne le nombre de logements occupés
    public function getNbOccupiedFlats($residenceId) {

        return $this->getNbFlats($residenceId) - $this->getNbFreeFlats($residenceId);
    }

    // retourne le nombre de logements avec préavis non reloués
    public function getNbWarningFlatsNotRelet($residenceId) {

        return $this->indexWarningNotRelet($residenceId)->count();
    }

    // UNIQUE
    // --------------------

    public function getById($id) {

        return $this->flat->findOrFail($id);
    }

    private function save(Flat $flat, Array $inputs)
    {
        $flat->block = $inputs['block'];
        $flat->floor = $inputs['floor'];
        $flat->name = $inputs['name'];
        $flat->price = $inputs['price'];
        $flat->area = $inputs['area'];
        $flat->view = $inputs['view'];
        $flat->residence_id = $inputs['residence_id'];

        $flat->save();
    }

    public function getPaginate($n)
    {
        return $this->user->paginate($n);
    }

    public function store(Array $inputs)
    {
        $flat = new $this->flat;

        $this->save($flat, $inputs);

        return $flat;
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