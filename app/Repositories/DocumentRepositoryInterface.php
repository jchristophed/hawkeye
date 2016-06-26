<?php

namespace App\Repositories;

interface DocumentRepositoryInterface
{
    public function index($class);
    public function getById($id);
}