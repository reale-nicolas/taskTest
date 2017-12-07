<?php
namespace App\Services;

use App\Interfaces\RepositoryInterface;
use Redis;

/**
 * Description of TaskServices
 *
 * @author nicolas
 */
class TaskServices 
{
    protected $task;
    
    
    public function __construct(RepositoryInterface $task) 
    {
        $this->task = $task;
    }
    
    
    /**
     * 
     * @param type $pageNumber
     * @return type
     */
    public function  all($pageNumber = 0)
    {
        return $this->task->all($pageNumber);
    }
    
   
    /**
     * 
     * @param type $id
     * @return type
     */
    public function get($id)
    {
        return $this->task->find($id);
    }
    
    
    /**
     * 
     * @param type $column
     * @param type $value
     * @param type $pageNumber
     * @return type
     */
    public function getBy($column, $value, $pageNumber = 0)
    {
        return $this->task->findBy($column, $value, $pageNumber);
    }
    
    
    /**
     * 
     * @param type $title
     * @param type $description
     * @param type $duedate
     * @param type $completed
     * @return type
     */
    public function insert($title, $description, $duedate, $completed)
    {
        return $this->task->create(
            array (
                "title"         => $title, 
                "description"   => $description, 
                "duedate"       => $duedate, 
                "completed"     => $completed
            )
        );
    }

    
    /**
     * 
     * @param type $id
     * @param type $title
     * @param type $description
     * @param type $duedate
     * @param type $completed
     * @return type
     */
    public function update($id, $title, $description, $duedate, $completed)
    {
        return $this->task->update(
            array(
                "title"         => $title, 
                "description"   => $description, 
                "duedate"       => $duedate, 
                "completed"     => $completed
            ),
            $id
        );
    }
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function delete($id)
    {
        return $this->task->delete($id);
    }
}

