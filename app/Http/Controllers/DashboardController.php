<?php

namespace App\Http\Controllers;

use App\Repositories\TenantRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Lang;

use App\Repositories\ResidenceRepositoryInterface;
use App\Repositories\FlatRepositoryInterface;
use App\Repositories\ContractRepositoryInterface;

class DashboardController extends Controller
{
    protected $flatRepository;
    protected $contractRepository;
    protected $residenceRepository;
    protected $tenantRepository;

    protected $residence;

    public function __construct(ResidenceRepositoryInterface $residenceRepositoryInterface, FlatRepositoryInterface $flatRepositoryInterface, ContractRepositoryInterface $contractRepositoryInterface, TenantRepositoryInterface $tenantRepositoryInterface)
    {
        $this->residenceRepository = $residenceRepositoryInterface;
        $this->flatRepository = $flatRepositoryInterface;
        $this->contractRepository = $contractRepositoryInterface;
        $this->tenantRepository = $tenantRepositoryInterface;

        $residenceId = \Route::current()->getParameter('residence');
        $this->residence = $this->residenceRepository->get($residenceId);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $residenceId
     * @return \Illuminate\Http\Response
     */
    public function index($residenceId)
    {
        $freeFlats = $this->flatRepository->indexUnoccupied($residenceId);
        $contractsWithRequiredDocuments = $this->contractRepository->indexIncomplete($residenceId);

        $nbFreeFlats = $this->flatRepository->getNbFreeFlats($residenceId);
        $nbOccupiedFlats = $this->flatRepository->getNbOccupiedFlats($residenceId);

        $nbContractsWithRequiredDocuments = $this->contractRepository->getNbIncompleteContracts($residenceId);
        $nbContractsWithoutRequiredDocuments = $this->contractRepository->getNbCompleteContracts($residenceId);

        $nbNewTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.new', 1));
        $nbOldTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.old', 1));
        $nbCancelTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.cancel', 1));

        return view('dashboard.listing', [  'flats' => $freeFlats,
                                            'contracts' => $contractsWithRequiredDocuments,
                                            'residence' => $this->residence,
                                            'nb_free_flats' => $nbFreeFlats,
                                            'nb_occupied_flats' => $nbOccupiedFlats,
                                            'nb_contracts_with_required_documents' => $nbContractsWithRequiredDocuments,
                                            'nb_contracts_without_required_documents' => $nbContractsWithoutRequiredDocuments,
                                            'nb_new_tenants' => $nbNewTenants,
                                            'nb_old_tenants' => $nbOldTenants,
                                            'nb_cancel_tenants' => $nbCancelTenants
                                        ]
        );
    }
}
