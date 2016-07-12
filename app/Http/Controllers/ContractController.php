<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractRequest;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Lang;

use App\Repositories\ResidenceRepositoryInterface;
use App\Repositories\ContractRepositoryInterface;
use App\Repositories\FlatRepositoryInterface;
use App\Repositories\TenantRepositoryInterface;
use App\Repositories\DocumentRepositoryInterface;
use Socialite;

use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    protected $residenceRepository;
    protected $contractRepository;
    protected $flatRepository;
    protected $tenantRepository;
    protected $documentRepositoryInterface;
    protected $residence;

    public function __construct(    Request $request,
                                    ResidenceRepositoryInterface $residenceRepositoryInterface,
                                    ContractRepositoryInterface $contractRepositoryInterface,
                                    FlatRepositoryInterface $flatRepositoryInterface,
                                    TenantRepositoryInterface $tenantRepositoryInterface,
                                    DocumentRepositoryInterface $documentRepositoryInterface,
                                    Route $route)
    {

        if (\Config::get('app.env') == 'production') {
            $this->middleware('auth');
        }

        $this->residenceRepository = $residenceRepositoryInterface;
        $this->contractRepository = $contractRepositoryInterface;
        $this->flatRepository = $flatRepositoryInterface;
        $this->tenantRepository = $tenantRepositoryInterface;
        $this->documentRepositoryInterface = $documentRepositoryInterface;

        // passage du controleur et de l'action dans la vue
        $action = $route->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);

        $residenceId = $request->route()->getParameter('residence');
        $residence = $this->residenceRepository->get($residenceId);
        $this->residence = $residence;

        view()->share(compact('controller', 'action', 'residence'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($residenceId)
    {
        $contracts = $this->contractRepository->index($this->residence->id);
        return view('contract.index', ['contracts' => $contracts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($residenceId, Request $request)
    {
        $flats = $this->flatRepository->indexForNewContract($residenceId);
        $tenants = $this->tenantRepository->indexForNewContract($residenceId);
        $last_tenant = $this->tenantRepository->getLastForNewContract($residenceId);

        $arrTenants[\Lang::get('global.contract.last')] = $last_tenant;
        $arrTenants[\Lang::get('global.contract.all')] = $tenants;

        $documentsGeneral = $this->documentRepositoryInterface->index('general');
        $documentsStudent = $this->documentRepositoryInterface->index('student');
        $documentsTrainee = $this->documentRepositoryInterface->index('trainee');
        $documentsEmploye = $this->documentRepositoryInterface->index('employe');
        $documentsGuarantor = $this->documentRepositoryInterface->index('guarantor');
        $documentsLocapass = $this->documentRepositoryInterface->index('locapass');

        return view('contract.create', ['flats' => $flats, 'tenants' => $arrTenants, 'documents_general' => $documentsGeneral, 'documents_student' => $documentsStudent, 'documents_trainee' => $documentsTrainee, 'documents_employe' => $documentsEmploye, 'documents_guarantor' => $documentsGuarantor, 'documents_locapass' => $documentsLocapass]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ContractRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractRequest $request, $residenceId)
    {
        $contract = $this->contractRepository->store($request->all());

        return redirect()->route('residence.contract.index', $residenceId)->withOk(Lang::get('global.contract.action_prefix') . ' ' . Lang::get('global.lbl.add_suffix'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($residenceId, $id)
    {
        $contract = $this->contractRepository->getById($id);

        return view('contract.show', ['contract' => $contract]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($residenceId, $id)
    {
        $contract = $this->contractRepository->getById($id);
        $flats = $this->flatRepository->indexForNewContract($residenceId);
        $tenants = $this->tenantRepository->indexForNewContract($residenceId);

        // récupération des documents
        $documentsGeneral = $this->documentRepositoryInterface->index('general');
        $documentsStudent = $this->documentRepositoryInterface->index('student');
        $documentsTrainee = $this->documentRepositoryInterface->index('trainee');
        $documentsEmploye = $this->documentRepositoryInterface->index('employe');
        $documentsGuarantor = $this->documentRepositoryInterface->index('guarantor');
        $documentsLocapass = $this->documentRepositoryInterface->index('locapass');

        return view('contract.edit', ['contract' => $contract, 'flats' => $flats, 'tenants' => $tenants, 'documents_general' => $documentsGeneral, 'documents_student' => $documentsStudent, 'documents_trainee' => $documentsTrainee, 'documents_employe' => $documentsEmploye, 'documents_guarantor' => $documentsGuarantor, 'documents_locapass' => $documentsLocapass]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContractRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContractRequest $request, $residenceId, $id)
    {
        $this->contractRepository->update($id, $request->all());

        return redirect()->route('residence.contract.index', $residenceId)->withOk(Lang::get('global.contract.action_prefix') . ' ' . Lang::get('global.lbl.edit_suffix'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($residenceId, $id)
    {
        $this->contractRepository->destroy($id);
        return redirect()->back()->withOk(Lang::get('global.contract.action_prefix') . ' ' . Lang::get('global.lbl.delete_suffix'));
    }
}
