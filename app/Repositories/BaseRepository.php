<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

/**
 * Description of BaseRepository
 *
 * @author nicore2000
 */
abstract class BaseRepository implements RepositoryInterface
{    
    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    
    public function model(){
        return $this->model;
    }
    /**
     * @param App $app
     * 
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModel();
    }
    
    
    /**
     * Specify Model class name
     * 
     * @return mixed
     */
    abstract public function getModel();
    
    
    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel() 
    {
        $model = $this->app->make($this->getModel());

        if (!$model instanceof Model)
            throw new Exception("Class {$this->getModel()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model;
    }
    
    
    /**
     * 
     * @param int $pageNumber
     * @param int $recordsPerPage
     * @return type
     */
    public function all(int $pageNumber = 0, int $recordsPerPage = 5) {
        return $this->model->skip($pageNumber * $recordsPerPage)->take($recordsPerPage)->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id) {
        return $this->model->find($id);
    }

    /**
     * 
     * @param array $attribute
     * @param array $value
     * @param int $pageNumber
     * @param int $recordsPerPage
     * @return type
     */
    public function findBy(array $attribute, array $value, int $pageNumber = 0, int $recordsPerPage = 5) 
    {
        $repo = $this->model;
        
        foreach ($attribute as $name) 
        {
            $repo = $repo->where($name, $value[$name]);
        }
        
        return $repo->skip($pageNumber * $recordsPerPage)->take($recordsPerPage)->get();
    }
    
    
    /**
     * @param $data
     * @return mixed
     */
    public function create(array $data) 
    {
        $repo = $this->model;
        
        foreach ($data as $k => $v) {
            $repo->$k = $v;
        }
        
        $repo->updated      = date('Y-m-d');
        $repo->created      = date('Y-m-d');
        
        return $repo->save();
    }
    
    /**
     * 
     * @param type $data
     * @param type $id
     * @return type
     */
    public function update(array $data, $id) 
    {
        $repo = $this->find($id);
        
        foreach ($data as $k => $v) {
            $repo->$k = $v;
        }
        
        $repo->updated      = date('Y-m-d');
        
        return $repo->save();
    }
}
