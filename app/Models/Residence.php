<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    protected $table = 'residence';

    public $timestamps = false;

    private $nb_flats;
    private $nb_incomplete_contracts;
    private $nb_free_flats;
    private $nb_warning_flats_not_relet;

    public function getNbFlatsAttribute() {

        return $this->nb_flats;
    }

    public function getNbIncompleteContractsAttribute() {

        return $this->nb_incomplete_contracts;
    }

    public function getNbFreeFlatsAttribute() {

        return $this->nb_free_flats;
    }

    public function getNbWarningFlatsNotReletAttribute() {

        return $this->nb_warning_flats_not_relet;
    }

    public function setNbFlats($value) {

        $this->nb_flats = $value;
    }

    public function setNbIncompleteContracts($value) {

        $this->nb_incomplete_contracts = $value;
    }

    public function setNbFreeFlats($value) {

        $this->nb_free_flats = $value;
    }

    public function setNbWarningFlatsNotRelet($value) {

        $this->nb_warning_flats_not_relet = $value;
    }

}
