<?php

namespace App\Http\Controllers;

use App\Models\TodoItem;
use App\Http\Requests\TodoModel\CreateRequest;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        TodoItem::create([
            'title' => strtoupper($request->title),
            'description' => $request->description,
            'time_started' => now(),
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'user_id' => auth()->user()->id
        ]);
    }

    public function update(CreateRequest $request)
    {
        TodoItem::where('id', $request->task_id)
        ->where('user_id', auth()->user()->id)
        ->update([
            'title' => strtoupper($request->title),
            'description' => $request->description,
            'time_started' => now(),
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'user_id' => auth()->user()->id
        ]);
        return back()->with('status', 'Successfully Updated');
    }

    public function progress($id, $outcome = null)
    {
        $outcome = ($outcome == 'complete') ? 'CP' : 'INC';
        TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->update(['status' => $outcome]);
        return back()->with('status', 'Successfully Updated');
    }

    public function showDetail($id)
    {
        return TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->delete();
        return back()->with('status', 'Successfully Deleted');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function show(Request $request)
    {
        $tasks = TodoItem::where('user_id', auth()->user()->id)
            ->when($request->category != 'all', function($query) use ($request) {
                switch ($request->category) {
                    case 'completed':
                        $query->where('status', 'CP');
                        break;
                    case 'incompleted':
                        $query->where('status', 'INC');
                        break;
                    case 'overdue':
                        $query->whereDate('due_date', now());
                        break;
                    default:
                        $query->where('priority', $request->category);
                        break;
                }
            })
            ->get();

        return datatables()->of($tasks)
            ->addColumn('due_date', function ($project) {
                return date_format(date_create($project->due_date),"Y-m-d");
            })
            ->addColumn('priority', function ($project) {
                return $this->priorityColumn($project->priority);
            })
            ->addColumn('status', function ($project) {
                return $this->statusAndDueDateColumn($project);
            })
            ->addColumn('actions', function ($project) {
                return $this->actionColumn($project->id);
            })
            ->rawColumns(['actions', 'priority']) 
            ->make(true);
    }

    private function statusAndDueDateColumn($project)
    {
        if ($project->due_date < now()) {
            return 'Overdue';
        } else {
            switch ($project->status) {
                case 'PD':
                    return 'Pending';
                    break;
                case 'CP':
                    return 'Completed';
                    break;
                case 'INC':
                    return 'Incomplete';
                    break;
            }
        }
    }


    private function priorityColumn($priority = '')
    {
        $badgeColor = '';
        switch($priority) {
            case 'low':
                $badgeColor = 'badge-success'; // Green for low
                break;
            case 'medium':
                $badgeColor = 'badge-warning'; // Yellow for medium
                break;
            case 'high':
                $badgeColor = 'badge-danger'; // Red for high
                break;
        }

        // Return priority with a badge
        return "<span class='badge $badgeColor text-capitalize'>{$priority}</span>";
    }

    private function actionColumn($id)
    {
        return "
            <a href='home/tasks/progress/{$id}/complete' class='btn btn-hover-primary' title='complete'>
                <i class='fas fa-check text-success'></i>
            </a>
            <a href='home/tasks/progress/{$id}/incomplete' class='btn btn-hover-primary' title='Incomplete'>
                <i class='fas fa-times text-danger'></i>
            </a> 
            <a href='javascript:void(0)' class='btn btn-hover-warning edit-btn' data-toggle='modal' data-target='#update_tasks' data-id='{$id}' title='Edit'>
                <i class='fas fa-edit text-warning'></i>
            </a>

            <a href='home/tasks/delete/{$id}' class='btn btn-hover-danger' title='remove'>
                <i class='fas fa-trash text-danger'></i>
            </a>
        ";
    }
}
