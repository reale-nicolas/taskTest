<?php
namespace App\Repositories;

use App\Interfaces\TaskInterface;
use App\Models\TaskModel;

/**
 * Description of TaskRepository
 *
 * @author nicolas
 */
class TaskRepository extends BaseRepository implements TaskInterface
{

    public function getModel() 
    {
        return new TaskModel;
    }
    
    
    public function all(array $related = null) 
    {
        return $this->getModel()->all();
    }
    
    
    public function create($title, $description, $duedate, $completed = false) 
    {
        $task               = $this->getModel();
        
        $task->title        = $title;
        $task->description  = $description;
        $task->duedate      = date('Y-m-d', strtotime($duedate));
        $task->completed    = $completed;
        $task->updated      = date('Y-m-d');
        $task->created      = date('Y-m-d');
        return $task->save();
    }
    

    public function get($id, array $related = null) 
    {
        $task = $this->getModel()->find($id);
        
        if (!$task) {
            throw new \Exception("The id task does not exist!");
        }
        
        return $task;
    }

    
    public function getWhere($column, $value, $pageNumber = 0, $recordsPerPage = 5) 
    {
        $task = $this->getModel();
        foreach ($column as $k=>$name) 
        {
            $task = $task->where($name, $value[$name]);
        }
        return $task->skip($pageNumber * $recordsPerPage)->take($recordsPerPage)->get();
    }
    
    
    public function update($id, $title, $description, $duedate, $completed) 
    {
        $task = $this->get($id);
        
        $task->title        = $title;
        $task->description  = $description;
        $task->duedate      = date('Y-m-d', strtotime($duedate));
        $task->completed    = $completed;
        $task->updated      = date('Y-m-d');
        
        return $task->save();
    }
    
    
    public function delete($id) 
    {
        $task =  $this->get($id);
        
        return $task->delete();
    }

}
