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
        if (\Config::get('app.env') == 'production') {
            $this->middleware('auth');
        }

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
        $nbOccupiedFlats = $this->flatRepository->getNbOccupiedFlats($residenceId);

        // logements vides et non reloués
        $freeFlatsNotRelet = $this->flatRepository->indexFreeNotRelet($residenceId);

        foreach ($freeFlatsNotRelet as $flat) {
            $flat->setContract($this->contractRepository->getActiveByFlat($residenceId, $flat));
        }

        // logements vides et reloués
        $freeFlatsRelet = $this->flatRepository->indexFreeRelet($residenceId);

        foreach ($freeFlatsRelet as $flat) {
            $flat->setContract($this->contractRepository->getActiveByFlat($residenceId, $flat));
            $flat->setNextContract($this->contractRepository->getNextByFlat($residenceId, $flat));
        }

        // logements avec préavis et non reloués
        $warningFlatsNotRelet = $this->flatRepository->indexWarningNotRelet($residenceId);

        foreach ($warningFlatsNotRelet as $flat) {

            $flat->setContract($this->contractRepository->getActiveByFlat($residenceId, $flat));
        }

        $nbCompleteContracts = $this->contractRepository->getNbCompleteContracts($residenceId);
        $incompleteContracts = $this->contractRepository->indexIncomplete($residenceId);
        $bookedContracts = $this->contractRepository->indexBooked($residenceId);

        $nbNewTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.new', 1));
        $nbOldTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.old', 1));
        $nbPassengerTenants = $this->tenantRepository->getNbByContract($residenceId, Lang::choice('global.tenant.passenger', 1));

        $tenantsTodayBirthday = $this->tenantRepository->indexTodayBirthday($residenceId);

        return view('dashboard.listing', [  'free_flats_not_relet' => $freeFlatsNotRelet,
                                            'free_flats_relet' => $freeFlatsRelet,
                                            'warning_flats_not_relet' => $warningFlatsNotRelet,
                                            'incomplete_contracts' => $incompleteContracts,
                                            'booked_contracts' => $bookedContracts,
                                            'tenants_birthday' => $tenantsTodayBirthday,
                                            'residence' => $this->residence,
                                            'nb_occupied_flats' => $nbOccupiedFlats,
                                            'nb_complete_contracts' => $nbCompleteContracts,
                                            'nb_new_tenants' => $nbNewTenants,
                                            'nb_old_tenants' => $nbOldTenants,
                                            'nb_passenger_tenants' => $nbPassengerTenants
                                        ]
        );
    }
}
