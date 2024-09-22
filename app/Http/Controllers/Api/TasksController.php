<?php

namespace App\Http\Controllers\Api;

use App\Models\TodoItem;
use App\Http\Requests\TodoModel\CreateRequest;
use App\Http\Requests\TodoModel\UpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TasksController extends Controller
{
    CONST CATEGORIES = ['all', 'completed', 'incomplete', 'overdue', 'low', 'medium', 'high'];
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $todoItem = TodoItem::create([
            'title' => strtoupper($request->title),
            'description' => $request->description,
            'time_started' => now(),
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json(['message' => 'Todo item created successfully', 'item' => $todoItem], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $category = "all"): JsonResponse
    {

        if (!in_array($category, self::CATEGORIES)) {
            return response()->json(["error" => "Category is invalid. Category must be: [". implode(', ', self::CATEGORIES). "]"], 422);
        }

        $tasks = TodoItem::where('user_id', auth()->user()->id)
            ->when($category != 'all', function($query) use ($category) {
                switch ($category) {
                    case 'completed':
                        $query->where('status', 'CP');
                        break;
                    case 'incomplete':
                        $query->where('status', 'INC');
                        break;
                    case 'overdue':
                        $query->whereDate('due_date', '<', now());
                        break;
                    default:
                        $query->where('priority', $category);
                        break;
                }
            })
            ->select('id', 'title', 'description', 'due_date', 'priority', 'status')
            ->get();

        return response()->json($tasks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $task_id): JsonResponse
    {

        $validator = \Validator::make(['task_id' => $task_id], [
            'task_id' => ['required', new \App\Rules\EnsureTasksIDAlignToItsUserRule],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proceed with existing logic
        if (TodoItem::preventActionIfStatusInOrComplete($task_id)->exists()) {
            return response()->json(['error' => 'You cannot do it again.'], 400);
        }

        TodoItem::where('id', $request->task_id)
            ->where('user_id', auth()->user()->id)
            ->update([
                'title' => strtoupper($request->update_title),
                'description' => $request->update_description,
                'time_started' => now(),
                'priority' => $request->update_priority,
                'due_date' => $request->update_due_date,
                'user_id' => auth()->user()->id,
            ]);
        
        return response()->json(['message' => 'Successfully Updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $task_id): JsonResponse
    {
        $todoItem = TodoItem::preventActionIfStatusInOrComplete($task_id);
        if ($todoItem->exists()) {
            return response()->json(['error' => 'You cannot do it again.'], 400);
        } else {
            TodoItem::where('id', $task_id)->where('user_id', auth()->user()->id)->delete();
            return response()->json(['message' => 'Successfully Deleted'], 200);
        }
    }
}
