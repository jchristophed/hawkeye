<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TenantRepository implements TenantRepositoryInterface {

    protected $tenant;
    protected $contractRepository;

    public function __construct(Tenant $tenant, ContractRepositoryInterface $contractRepositoryInterface) {

        $this->tenant = $tenant;
        $this->contractRepository = $contractRepositoryInterface;
    }

    // SCOPES
    // --------------------

    // retourne uniquement les logements d'une résidence
    private function scopeOnResidenceOnly($residenceId) {

        return $this->tenant->where('tenant.residence_id', $residenceId);
    }

    // selectionne les locataires ayant un logement
    public function scopeHasFlat($residenceId) {

        return  $this   ->scopeOnResidenceOnly($residenceId)
                        ->select('tenant.id', 'tenant.firstname', 'tenant.lastname', 'tenant.birth_date', 'tenant.status', 'tenant.contract', 'tenant.company')
                        ->whereIn('tenant.id', $this->contractRepository->indexRunningTenantId($residenceId));
    }

    // LISTING
    // --------------------

    public function index($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)->orderBy('lastname', 'asc')->get();
    }

    // retourne la liste des locataires pour la liste déroulante d'un nouveau contrat
    public function indexForNewContract($residenceId) {

        return $this->tenant->where('residence_id', $residenceId)->orderBy('lastname', 'asc')->get()->pluck('full_name', 'id')->all();
    }

    // retourne tous les locataires dont c'est l'anniversaire aujourd'hui
    public function indexTodayBirthday($residenceId) {

        return $this->scopeOnResidenceOnly($residenceId)    ->where(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDay()->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDay()->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDays(2)->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDays(2)->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDays(3)->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDays(3)->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDays(4)->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDays(4)->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDays(5)->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDays(5)->day);
                                                            })
                                                            ->orWhere(function ($query2) {
                                                                $query2
                                                                    ->where(DB::raw('MONTH(birth_date)'), '=', Carbon::now()->addDays(6)->month)
                                                                    ->where(DB::raw('DAY(birth_date)'), '=', Carbon::now()->addDays(6)->day);
                                                            })
                                                            ->orderBy('birth_date', 'asc')->get();
    }

    // COMPTEURS
    // --------------------

    public function getNbByStatus($residenceId, $status) {

        return $this->tenant->where('residence_id', $residenceId)->where('status', $status)->count();
    }

    public function getNbByContract($residenceId, $contract) {

        return $this->scopeHasFlat($residenceId)->where('contract', $contract)->count();
    }

    // UNIQUE
    // --------------------

    public function getById($id) {

        return $this->tenant->findOrFail($id);
    }

    // retourne le dernier locataire créé pour la liste déroulante d'un nouveau contrat
    public function getLastForNewContract($residenceId) {

        return $this->tenant->where('residence_id', $residenceId)->orderBy('id', 'desc')->limit(1)->get()->pluck('full_name', 'id')->all();
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

    // GESTION
    // --------------------

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