<?php

/**
 * TaskController
 *
 * @author Fernando Tholl <contato@fernandotholl.net>
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Validator;
use App\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{

	/**
	 * index()
	 * List all tasks
	 *
	 * @return string
	 */
	public function index()
	{
		$tasks = Task::where('user_id', Auth::user()->id)
           ->orderBy('sequence', 'asc')
           ->orderBy('created_at', 'desc')
           ->get();

        return response()->json($tasks);
	}

	/**
	 * show()
	 * Method for displaying a task
	 *
	 * @param int 	$id 	 	
	 *
	 * @return json
	 */
	public function show($id)
	{
		$task = Task::where('user_id', Auth::user()->id)->firstOrFail();

        if(!$task) {
            return response()->json([
                'message'   => 'Show: Task not found',
            ], 404);
        }

        return response()->json($task);
	}

	/**
	 * store()
	 * Method to add a new task
	 *
	 * @param @Illuminate\Http\Request 	$request 	 	
	 *
	 * @return json
	 */
	public function store(Request $request)
    {
        
	    $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
	        	'message' => 'Validation errors',
	        	'errors' => $validator->errors()
	      	], 422);
        }

	    $task = new Task();
        
        $task->user_id = Auth::user()->id;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->save();

        return response()->json($task, 201);
    }

    /**
	 * update()
	 * Method for updating a task
	 *
	 * @param @Illuminate\Http\Request 	$request 	 	
	 * @param $id 	 	
	 *
	 * @return json
	 */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message'   => 'Update: Task not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
	        	'message' => 'Validation errors',
	        	'errors' => $validator->errors()
	      	], 422);
        }
       
        if(\Auth::user()->id != $task->user_id) {
            return response()->json([
                'message'   => 'You haven\'t permission to change',
            ], 401);
        }

        $task->title = preg_replace("/^#[0-9](.*)/im", '$1', $request->input('title'));
        $task->save();

        return response()->json($task);
    }

    /**
	 * destroy()
	 * Removes the current task
	 *
	 * @param int $id 	 	
	 *
	 * @return void
	 */
    public function destroy($id)
    {
        $task = Task::find($id);

        if(!$task) {
            return response()->json([
                'message'   => 'Task not found',
            ], 404);
        }

        $task->delete();
    }

    /**
	 * order()
	 * Method to sort tasks
	 *
	 * @param @Illuminate\Http\Request 	$request 	 	
	 *
	 * @return json
	 */
    public function order(Request $request)
    {

    	$inputOrders = $request->input('orders');
    	$orders = array();

    	if(!empty($inputOrders)){
    		unset($inputOrders[0]); // remove task title

    		foreach ($inputOrders as $ordem => $task_string) {
    			preg_match("/task_([0-9]+)/im", $task_string, $arr_values);
    			$orders[$arr_values[1]] = $ordem;
    		}

    		$task = new Task();
    		$task->order($orders);

    	}else{
    		return response()->json([
                'message'   => 'Order fault',
            ], 422);
    	}

    }

    public function complete(Request $request, $id)
    {

    	$status = $request->input('status');

    	$task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message'   => 'Update: Task not found',
            ], 404);
        }

 		$task->status = $status;
        $task->save();
 		
        return response()->json($task);

    }

}
