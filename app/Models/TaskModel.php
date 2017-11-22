<?php

namespace App\Models;

class TaskModel extends BaseModel
{
    protected $connection = 'mongodb';
    protected $collection = 'tasks';
//    protected $dateFormat = 'Y-m-d';

}
