<?php
namespace App\Services;

use App\Interfaces\TaskInterface;
use Redis;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskServices
 *
 * @author nicolas
 */
class TaskServices 
{
    protected $task;
    
    
    public function __construct(TaskInterface $task) 
    {
        $this->task = $task;
    }
    
    
    public function  all()
    {
        return $this->task->all();
    }
    
   
    public function get($id)
    {
        return $this->task->get($id);
    }
    
    public function getBy($column, $value, $pageNumber = 0)
    {
        $redisKey = serialize([$column, $value, $pageNumber]);
        $task = Redis::get("tasks:".$redisKey);
        
        if ($task) {
            return unserialize($task);
        }
        
        $resultDB = $this->task->getWhere($column, $value, $pageNumber);
        
        $redisValue = serialize($resultDB);
        Redis::set("tasks:".$redisKey, $redisValue);
        
        return $resultDB;
    }
    
    
    public function insert($title, $description, $duedate, $completed)
    {
        return 
            $this->task->create(
                $title, 
                $description, 
                $duedate, 
                $completed
            );
    }

    
    public function update($id, $title, $description, $duedate, $completed)
    {
        return $this->task->update($id, $title, $description, $duedate, $completed);
    }
    
    
    public function delete($id)
    {
        return $this->task->delete($id);
    }
}

