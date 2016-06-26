<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository implements ViewRepositoryInterface {

    protected $view;

    public function __construct(View $view) {

        $this->view = $view;
    }

    // retourne la liste des vues d'une résidence
    public function index($residenceId) {

        return $this->view->where('residence_id', $residenceId)->orderBy('name', 'asc')->get();
    }

    // retourne la liste des vues pour la liste déroulante d'un nouveau logement
    public function indexForNewFlat($residenceId) {

        return $this->view->where('residence_id', $residenceId)->orderBy('name', 'asc')->get()->pluck('name', 'name');
    }
}