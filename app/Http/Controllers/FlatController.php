<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\FlatRequest;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Lang;

use App\Repositories\ResidenceRepositoryInterface;
use App\Repositories\FlatRepositoryInterface;
use App\Repositories\ContractRepositoryInterface;
use App\Repositories\BlockRepositoryInterface;
use App\Repositories\ViewRepositoryInterface;
use Socialite;

class FlatController extends Controller
{

    protected $flatRepository;
    protected $contractRepository;
    protected $residenceRepository;
    protected $blockRepository;
    protected $viewRepository;

    protected $residence;

    public function __construct(ResidenceRepositoryInterface $residenceRepositoryInterface, FlatRepositoryInterface $flatRepositoryInterface, ContractRepositoryInterface $contractRepositoryInterface, BlockRepositoryInterface $blockRepositoryInterface, ViewRepositoryInterface $viewRepositoryInterface, Route $route)
    {
        //$this->middleware('auth');
        $this->residenceRepository = $residenceRepositoryInterface;
        $this->flatRepository = $flatRepositoryInterface;
        $this->contractRepository = $contractRepositoryInterface;
        $this->blockRepository = $blockRepositoryInterface;
        $this->viewRepository = $viewRepositoryInterface;

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
     * @param  int  $residenceId
     * @return \Illuminate\Http\Response
     */
    public function index($residenceId)
    {
        $flats = $this->flatRepository->index($residenceId);

        foreach($flats as $flat) {

            $flat->setContract($this->contractRepository->getActiveByFlat($residenceId, $flat));
            $flat->setNextContract($this->contractRepository->getNextByFlat($residenceId, $flat));
        }

        return view('flat.listing', ['flats' => $flats]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $residenceId
     * @return \Illuminate\Http\Response
     */
    public function create($residenceId)
    {
        $blocks = $this->blockRepository->indexForNewFlat($residenceId);
        $views = $this->viewRepository->indexForNewFlat($residenceId);
        return view('flat.create', ['blocks' => $blocks, 'views' => $views]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FlatRequest  $request
     * @param  int  $residenceId
     * @return \Illuminate\Http\Response
     */
    public function store(FlatRequest $request, $residenceId)
    {
        $flat = $this->flatRepository->store($request->all());

        return redirect('residence/' . $residenceId . '/flat/')->withOk(Lang::get('global.flat.action_prefix') . ' ' . $flat->name . ' ' . Lang::get('global.lbl.add_suffix'));
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
        $flat = $this->flatRepository->getById($id);
        $contract = $this->contractRepository->getActiveByFlat($residenceId, $flat);
        $contracts = $this->contractRepository->indexByFlatByStartDate($flat);

        return view('flat.show', ['flat' => $flat, 'contracts' => $contracts, 'contract' => $contract]);
    }

    /**
     *
     * Show the form for editing the specified resource.
     *
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($residenceId, $id)
    {
        $flat = $this->flatRepository->getById($id);
        $blocks = $this->blockRepository->indexForNewFlat($residenceId);
        $views = $this->viewRepository->indexForNewFlat($residenceId);

        return view('flat.edit', ['flat' => $flat, 'blocks' => $blocks, 'views' => $views]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FlatRequest $request
     * @param  int  $residenceId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FlatRequest $request, $residenceId, $id)
    {
        $this->flatRepository->update($id, $request->all());

        return redirect('residence/' . $residenceId . '/flat/')->withOk(Lang::get('global.flat.action_prefix') . ' ' . $request->input('name') . ' ' . Lang::get('global.lbl.edit_suffix'));
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
        $this->flatRepository->destroy($id);
        return redirect()->back()->withOk(Lang::get('global.flat.action_prefix') . ' ' . Lang::get('global.lbl.delete_suffix'));
    }
}
