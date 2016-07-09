<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ResidenceRepositoryInterface;
use App\Repositories\FlatRepositoryInterface;
use App\Repositories\ContractRepositoryInterface;
use Socialite;

class ResidenceController extends Controller
{
    protected $residenceRepository;
    protected $flatRepository;
    protected $contractRepository;

    public function __construct(ResidenceRepositoryInterface $residenceRepositoryInterface, FlatRepositoryInterface $flatRepositoryInterface, ContractRepositoryInterface $contractRepositoryInterface)
    {
        $this->middleware('auth');
        $this->residenceRepository = $residenceRepositoryInterface;
        $this->flatRepository = $flatRepositoryInterface;
        $this->contractRepository = $contractRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $residences = $this->residenceRepository->index();

        foreach($residences as $residence) {

            $residence->setNbFlats($this->flatRepository->getNbFlats($residence->id));
            $residence->setNbIncompleteContracts($this->contractRepository->getNbIncompleteContracts($residence->id));
            $residence->setNbFreeFlats($this->flatRepository->getNbFreeFlatsNotRelet($residence->id));
            $residence->setNbWarningFlatsNotRelet($this->flatRepository->getNbWarningFlatsNotRelet($residence->id));
        }

        return view('residence.listing', ['residences' => $residences]);
    }
}
