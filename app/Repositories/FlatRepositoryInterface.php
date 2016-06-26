<?php

namespace App\Repositories;

interface FlatRepositoryInterface
{
    public function index($residenceId);
    public function getById($id);
    public function getPaginate($n);
    public function store(Array $inputs);
    public function update($id, Array $inputs);
    public function destroy($id);
}