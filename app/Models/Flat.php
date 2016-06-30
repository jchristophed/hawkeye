<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Flat extends Model
{
    protected $table = 'flat';

    public $timestamps = false;
    private $contract;
    private $next_contract;

    // retourne le nom complet du logement
    public function getFullNameAttribute() {

        return Lang::choice('global.flat.title', 1) . ' ' . Lang::get('global.lbl.num') . $this->name;
    }

    public function getFloorNameAttribute() {

        return $this->floor == 0 ? Lang::get('global.flat.ground') : $this->floor . Lang::choice('global.flat.n_floor', $this->floor);
    }

    public function getFullPriceAttribute() {

        return !empty($this->price) ? $this->price . ' ' . Lang::get('global.lbl.euro') : '';
    }

    public function getFullAreaAttribute() {

        return !empty($this->area) ? $this->area . ' ' . Lang::get('global.lbl.m2') : '';
    }

    public function contracts() {

        return $this->hasMany('App\Models\Contract');
    }

    public function getContractAttribute() {
        return $this->contract;
    }

    public function setContract($value) {
        $this->contract = $value;
    }

    public function getNextContractAttribute() {
        return $this->next_contract;
    }

    public function setNextContract($value) {
        $this->next_contract = $value;
    }

}
