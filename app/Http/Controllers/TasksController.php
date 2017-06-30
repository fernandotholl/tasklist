<?php

/**
 * TaskController
 *
 * @author Fernando Tholl <contato@fernandotholl.net>
 */

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	/**
	 * index()
	 * List all tasks
	 *
	 * @return string
	 */
	public function index()
	{
		$tasks = Task::where('status', 1)
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
		$task = Task::find($id);

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
        
        $validator = $this->validate($request, [
	        'title' => 'required|max:255'
	    ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ], 400);
        }

	    $task = new Task();
        
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

        $task->fill($request->all());
        $task->save();

        return response()->json($task);
    }

    /**
	 * destroy()
	 * Removes the current task
	 *
	 * @param int $id 	 	
	 *
	 * @return json
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

}
