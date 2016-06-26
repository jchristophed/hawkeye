<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository implements DocumentRepositoryInterface {

    protected $document;

    public function __construct(Document $document) {

        $this->document = $document;
    }

    public function index($class = null) {

        return ($class === null) ? $this->document->orderBy('class', 'asc')->get() : $this->document->where('class', '=', $class)->orderBy('class', 'asc')->get();
    }

    public function getById($id) {

        return $this->tenant->findOrFail($id);
    }

    // hydrate un contrat depuis un formulaire
    private function save(Contract $contract, Array $inputs)
    {
        $contract->start_date = $inputs['start_date'];
        $contract->end_date = $inputs['end_date'];
        $contract->price = $inputs['price'];
        $contract->application_fee = $inputs['application_fee'];
        $contract->deposit = $inputs['deposit'];
        $contract->mode_of_payment = $inputs['mode_of_payment'];
        $contract->flat_id = $inputs['flat'];
        $contract->tenant_id = $inputs['tenant'];

        $this->documentRepository->store($inputs['documents']);
        $contract->save();
    }

    // ajoute plusieurs documents
    private function saveMultiple(Document $document, Array $inputs)
    {
        foreach($inputs as $document) {
            $this->save($inputs['documents']);
            $document->save();
        }
    }

    public function store(Array $inputs)
    {
        $document = new $this->document;

        $this->saveMultiple($document, $inputs);

        return $document;
    }


}