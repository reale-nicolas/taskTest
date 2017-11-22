<?php

namespace App\Http\Controllers;

use App\Services\TaskServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use function response;

class TaskController extends Controller
{
    protected $taskServices;
    protected $availableFieldsToSearchBy = ['duedate', 'completed', 'updated', 'created'];
    
    
    public function __construct(TaskServices $service) 
    {
        $this->taskServices = $service;
    }

    
    /**
     * Return a JSON file with all the tasks.
     * 
     * @param  Request  $request
     * 
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->all() && array_intersect(array_keys($request->all()), $this->availableFieldsToSearchBy)) 
        {
            $validator = Validator::make($request->all(), [
                'duedate'       => 'date_format:"Y-m-d"',
                'completed'     => 'boolean',
                'updated'       => 'date_format:"Y-m-d"',
                'created'       => 'date_format:"Y-m-d"',
                'page'          => 'integer'
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'result'    => 'ERROR',
                    'detail'    => 'Differents errors were found in the input data',
                    'fields'    => $validator->errors()
                ]);
            }
            
            $arrColumnsFilters = array_intersect(array_keys($request->all()), array_values($this->availableFieldsToSearchBy));
            $arrValuesFilters  = array_intersect_key($request->all(), array_flip($arrColumnsFilters));
            
            $pageNumber = (isset($request->page)?$request->page:0);
            
            $result = $this->taskServices->getBy($arrColumnsFilters, $arrValuesFilters, $pageNumber);
            
        } else {
            $result = $this->taskServices->all();
        }
        
        if (!$result->count()) {
            return response()->json([
                    'result'    => 'SUCCESS',
                    'detail'    => 'We couldn\'t find records with your criteria'
                ]);
        }
        
        return $result;
    }
    
    
    /**
     * Store a new task.
     *
     * @param  Request  $request
     * 
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
            'description'   => 'string',
            'duedate'       => 'required|date',
            'completed'     => 'boolean'
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
                'result'    => 'ERROR',
                'detail'    => 'Differents errors were found in the input data',
                'fields'    => $validator->errors()
            ]);
        }
        
        if($this->taskServices->insert(
                $request->title, 
                $request->description, 
                $request->duedate, 
                $request->completed)
        )
        {
            return response()->json([
                'result'    => 'SUCCESS',
                'detail'    => 'Task stored successfully'
            ]);
        }
        
        return response()->json([
                'result'    => 'ERROR',
                'detail'    => 'It was imposible to store the task, '
                                . 'please try again later.'
        ]);
        
    }
    
    
    /**
     * Update an existing task.
     *
     * @param  Request  $request
     * 
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'title'         => 'required|string',
            'description'   => 'string',
            'duedate'       => 'required|date',
            'completed'     => 'boolean'
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
                'result'    => 'ERROR',
                'detail'    => 'Differents errors were found in the input data',
                'fields'    => $validator->errors()
            ]);
        }
        
        try {
            $this->taskServices->update(
                $request->id,
                $request->title, 
                $request->description, 
                $request->duedate, 
                $request->completed);
        
            return response()->json([
                'result'    => 'SUCCESS',
                'detail'    => 'Task updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result'    => 'ERROR',
                'detail'    => $e->getMessage()
            ]);
        }
        
    }
    
    
    /**
     * Delete a task.
     * 
     * @param  Request  $request
     * 
     * @return Response
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'            => 'required'
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
                'result'    => 'ERROR',
                'detail'    => 'An error was founded in the input data',
                'fields'    => $validator->errors()
            ]);
        }
        
        try {
            $this->taskServices->delete($request->id);
            return response()->json([
                'result'    => 'SUCCESS',
                'detail'    => 'Task deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result'    => 'ERROR',
                'detail'    => $e->getMessage()
            ]);
        }
    }
    
}
