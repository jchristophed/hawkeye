<?php

namespace App\Repositories;

interface ResidenceRepositoryInterface
{
    public function index();
    public function get($id);
}