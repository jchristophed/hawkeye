<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Contract extends Model
{
    protected $table = 'contract';

    public $timestamps = true;

    public function flat() {

        return $this->belongsTo('App\Models\Flat');
    }

    public function tenant() {

        return $this->belongsTo('App\Models\Tenant');
    }

    public function documents() {

        return $this->belongsToMany('App\Models\Document');
    }

    public function getFullPriceAttribute() {

        return !empty($this->price) ? $this->price . ' ' . Lang::get('GLOBAL.lbl.euro') : '';
    }

    public function getFullApplicationFeeAttribute() {

        return !empty($this->application_fee) ? $this->application_fee . ' ' . Lang::get('global.lbl.euro') : '';
    }

    public function getFullDepositAttribute() {

        return !empty($this->deposit) ? $this->deposit . ' ' . Lang::get('global.lbl.euro') : '';
    }

    // retourne un tableau avec les id des documents manquants du contrat
    public function getDocumentsIdAttribute() {

        $arrDocuments = [];

        foreach($this->documents as $document) {

            $arrDocuments[] = $document->id;
        }

        return $arrDocuments;
    }

}
