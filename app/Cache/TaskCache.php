<?php

namespace App\Cache;

use App\Interfaces\RepositoryInterface;
use Redis;

/**
 * Description of TaskCache
 *
 * @author nicolas
 */
class TaskCache implements RepositoryInterface
{
    protected $repoDB;
    public static $OPERATION_ALL        = 'all';
    public static $OPERATION_FIND_BY    = 'find_by';
    public static $OPERATION_FIND       = 'find';


    public function __construct(RepositoryInterface $repo)
    {
        $this->repoDB = $repo;
    }
    
    /**
     * 
     * @param int $pageNumber
     * @param int $recordsPerPage
     * @return type
     */
    public function all(int $pageNumber = 0, int $recordsPerPage = 5) 
    {
        $redisKey = md5(serialize([$pageNumber, $recordsPerPage]));
        $task = Redis::get(TaskCache::$OPERATION_ALL.":".$redisKey);
        
        if ($task) {
            return unserialize($task);
        }
        
        $resultDB = $this->repoDB->all($pageNumber, $recordsPerPage);
        
        $redisValue = serialize($resultDB);
        Redis::set(TaskCache::$OPERATION_ALL.":".$redisKey, $redisValue);
        
        return $resultDB;
    }

    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function find($id) 
    {
        $redisKey = md5(serialize([$id]));
        $task = Redis::get(TaskCache::$OPERATION_FIND.":".$redisKey);
        
        if ($task) {
            return unserialize($task);
        }
        
        $resultDB = $this->repoDB->find($id);
        
        $redisValue = serialize($resultDB);
        Redis::set(TaskCache::$OPERATION_FIND.":".$redisKey, $redisValue);
        
        return $resultDB;
    }

    
    /**
     * 
     * @param array $column
     * @param array $value
     * @param int $pageNumber
     * @param int $recordsPerPage
     * @return type
     */

    public function findBy(array $column, array $value, int $pageNumber = 0, int $recordsPerPage = 5) 
    {
        $redisKey = md5(serialize([$column, $value, $pageNumber]));
        $task = Redis::get(TaskCache::$OPERATION_FIND_BY.":".$redisKey);
        
        if ($task) {
            return unserialize($task);
        }
        
        $resultDB = $this->repoDB->findBy($column, $value, $pageNumber);
        
        $redisValue = serialize($resultDB);
        Redis::set(TaskCache::$OPERATION_FIND_BY.":".$redisKey, $redisValue);
        
        return $resultDB;
    }
    
    
    /**
     * 
     * @param array $data
     * @return type
     */
    public function create(array $data) 
    {
        $resultDB = $this->repoDB->create($data);
        
        if ($resultDB) {
            $this->clearCache('CREATE');
        }
        
        return $resultDB;
    }
    

    /**
     * 
     * @param array $data
     * @param type $id
     * @return type
     */
    public function update(array $data, $id) 
    {
        $resultDB = $this->repoDB->update($data, $id);
        
        if ($resultDB) {
            $this->clearCache('UPDATE');
        }
        
        return $resultDB;
    }

    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function delete($id) 
    {
        $resultDB = $this->repoDB->delete($id);
        if ($resultDB) {
            $this->clearCache('DELETE');
        }
        
        return $resultDB;
    }
    
    
    /**
     * 
     * @param type $operation
     * @throws \Exception
     */
    protected function clearCache($operation)
    {
        $keys = array();
        
        if ($operation == 'UPDATE' || $operation == 'DELETE')
        {
            Redis::flushDB();
        } 
        elseif ($operation == 'CREATE') 
        {
            $keys = $this->getByPattern([
                TaskCache::$OPERATION_ALL.':*', 
                TaskCache::$OPERATION_FIND_BY.':*'
            ]);
            
            foreach ($keys as $key) 
            {
                Redis::del($key);
            }
        }
        else 
        {
            throw new \Exception("Error cache memory management.");
        }
        
    }
    
    
    /**
     * 
     * @param array $patterns
     * @return type
     */
    protected function getByPattern(array $patterns)
    {
        $arrKeys = array();
        foreach ($patterns as $pattern)
        {
            $cursor = 0;
            do {
                list($cursor, $keysUp) = Redis::scan($cursor, 'match', $pattern);
                $arrKeys = array_merge($arrKeys, $keysUp);
            } while ($cursor);
        }
        
        return $arrKeys;
    }

}
