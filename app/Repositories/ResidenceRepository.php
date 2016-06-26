<?php

namespace App\Repositories;

use App\Models\Residence;

class ResidenceRepository implements ResidenceRepositoryInterface {

    protected $residence;

    public function __construct(Residence $residence) {

        $this->residence = $residence;
    }

    public function index() {

        return $this->residence->orderBy('name', 'asc')->get();
    }

    public function get($id) {

        return $this->residence->where('id', $id)->get()->first();
    }
}