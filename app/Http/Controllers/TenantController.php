<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\TenantRequest;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Lang;

use App\Repositories\ResidenceRepositoryInterface;
use App\Repositories\TenantRepositoryInterface;
use App\Repositories\ContractRepositoryInterface;
use Socialite;

class TenantController extends Controller
{

    protected $tenantRepository;
    protected $contractRepository;
    protected $residenceRepository;

    protected $residence;

    public function __construct(ResidenceRepositoryInterface $residenceRepositoryInterface, TenantRepositoryInterface $tenantRepositoryInterface, ContractRepositoryInterface $contractRepositoryInterface, Route $route)
    {
        if (\Config::get('app.env') == 'production') {
            $this->middleware('auth');
        }

        $this->residenceRepository = $residenceRepositoryInterface;
        $this->tenantRepository = $tenantRepositoryInterface;
        $this->contractRepository = $contractRepositoryInterface;

        // passage du controleur et de l'action dans la vue
        $action = $route->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);

        $residenceId = \Route::current()->getParameter('residence');
        $residence = $this->residenceRepository->get($residenceId);
        $this->residence = $residence;

        view()->share(compact('controller', 'action', 'residence'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int $residenceId
     * @return \Illuminate\Http\Response
     */
    public function index($residenceId)
    {
        $tenants = $this->tenantRepository->index($residenceId);
        return view('tenant.listing', ['tenants' => $tenants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int $residenceId
     * @return \Illuminate\Http\Response
     */
    public function create($residenceId)
    {
        return view('tenant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TenantRequest  $request
     * @param  int $residenceId
     * @return \Illuminate\Http\Response
     */
    public function store(TenantRequest $request, $residenceId)
    {
        $tenant = $this->tenantRepository->store($request->all());

        return redirect('residence/' . $residenceId . '/contract/create/')->withOk(Lang::get('global.tenant.action_prefix') . ' ' . $tenant->name . ' ' . Lang::get('global.lbl.add_suffix'));
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
        $tenant = $this->tenantRepository->getById($id);
        $contract = $this->contractRepository->getActiveByTenant($residenceId, $tenant);
        $contracts = $this->contractRepository->indexByTenantByStartDate($tenant);

        return view('tenant.show', ['tenant' => $tenant, 'contracts' => $contracts, 'contract' => $contract]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($residenceId, $id)
    {
        $tenant = $this->tenantRepository->getById($id);
        return view('tenant.edit', ['tenant' => $tenant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TenantRequest  $request
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TenantRequest $request, $residenceId, $id)
    {
        $this->tenantRepository->update($id, $request->all());

        return redirect('residence/' . $residenceId . '/tenant/')->withOk(Lang::get('global.tenant.action_prefix') . ' ' . $request->input('firstname') . ' ' . $request->input('lastname') . ' ' . Lang::get('global.lbl.edit_suffix'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($residenceId, $id)
    {
        $this->tenantRepository->destroy($id);
        return redirect()->back()->withOk(Lang::get('global.tenant.action_prefix') . ' ' . Lang::get('global.lbl.delete_suffix'));
    }
}
