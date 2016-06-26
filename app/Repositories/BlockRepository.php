<?php

namespace App\Repositories;

use App\Models\Block;

class BlockRepository implements BlockRepositoryInterface {

    protected $block;

    public function __construct(Block $block) {

        $this->block = $block;
    }

    // retourne la liste des bâtiments d'une résidence
    public function index($residenceId) {

        return $this->block->where('residence_id', $residenceId)->orderBy('name', 'asc')->get();
    }

    // retourne la liste des bâtiments pour la liste déroulante d'un nouveau logement
    public function indexForNewFlat($residenceId) {

        return $this->block->where('residence_id', $residenceId)->orderBy('name', 'asc')->get()->pluck('name', 'name');
    }
}