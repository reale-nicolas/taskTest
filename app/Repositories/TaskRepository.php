<?php
namespace App\Repositories;


/**
 * Description of TaskRepository
 *
 * @author nicolas
 */
class TaskRepository extends BaseRepository
{

    public function getModel() 
    {
        return 'App\Models\TaskModel';
    }
}
