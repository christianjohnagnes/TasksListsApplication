<?php

namespace App\Http\Controllers\Api;

use App\Models\TodoItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TasksStatusRequest;

class StatusController extends Controller
{

    /**
     * Update the specified resource in storage.
     */
    public function update(TasksStatusRequest $request): JsonResponse
    {
        $outcome = ($request->status === 'complete') ? 'CP' : 'INC';
        TodoItem::where('id', $request->task_id)
        ->where('user_id', auth()->user()->id)
        ->update([
            'status' => $outcome,
            'time_ended' => now(),
        ]);

        return response()->json(['message' => "Status of id: {$request->task_id} is successfully updated", 'status' => $outcome], 200);
    }

}
