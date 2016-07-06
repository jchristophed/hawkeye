<?php

namespace App\Repositories;

interface FlatRepositoryInterface
{
    public function index($residenceId);
    public function indexForNewContract($residenceId);
    public function indexFree($residenceId);
    public function indexFreeNotRelet($residenceId);
    public function indexWarningNotRelet($residenceId);
    public function indexFreeRelet($residenceId);
    public function getNbFlats($residenceId);
    public function getNbFreeFlats($residenceId);
    public function getNbFreeFlatsNotRelet($residenceId);
    public function getNbFreeFlatsRelet($residenceId);
    public function getNbOccupiedFlats($residenceId);
    public function getNbWarningFlatsNotRelet($residenceId);

    public function getById($id);
    public function store(Array $inputs);
    public function update($id, Array $inputs);
    public function destroy($id);
}