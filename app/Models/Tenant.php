<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

class Tenant extends Model
{
    protected $table = 'tenant';

    public $timestamps = false;

    // retourne le nom complet du locataire
    public function getFullNameAttribute() {

        return $this->lastname . ' ' . $this->firstname;
    }

    // retourne l'âge du locataire
    public function getAgeAttribute() {

        return Carbon::createFromFormat('Y-m-d', $this->birth_date)->diff(Carbon::now())->format('%y');
    }

    public function getFullAgeAttribute() {

        return $this->age > 0 ? $this->age . ' ' . Lang::choice('global.tenant.year', $this->age) : null;
    }

    public function contracts() {

        return $this->hasMany('App\Models\Contract');
    }
}
