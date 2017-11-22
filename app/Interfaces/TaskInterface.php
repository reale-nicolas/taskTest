<?php
namespace App\Interfaces;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskInterface
 *
 * @author nicolas
 */
interface TaskInterface 
{
    public function create($title, $description, $duedate, $completed);
    
    public function all(array $related = null);
    
    public function get($id, array $related = null);
    
    public function update($id, $title, $description, $duedate, $completed) ;
    
    public function delete($id) ;
    
    public function getWhere($column, $value, $page);
}
