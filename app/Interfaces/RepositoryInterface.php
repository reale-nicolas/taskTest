<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Interfaces;

interface RepositoryInterface 
{
 
    public function all(int $pageNumber = 0, int $recordsPerPage = 5);
 
    public function create(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);
 
    public function find($id);
 
    public function findBy(array $field, array $value, int $pageNumber = 0, int $recordsPerPage = 5);
}