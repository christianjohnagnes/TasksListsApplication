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
        TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->update(['status' => $outcome, 'time_ended' => now()]);
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
                return date_format(date_create($project->due_date),"M. m, Y");
            })
            ->addColumn('priority', function ($project) {
                return $this->priorityColumn($project->priority);
            })
            ->addColumn('status_symbol', function ($project) {
                return $project->status;
            })
            ->addColumn('status', function ($project) {
                return $this->statusAndDueDateColumn($project);
            })
            ->addColumn('actions', function ($project) {
                return $this->actionColumn($project->id);
            })
            ->rawColumns(['actions', 'status', 'priority']) 
            ->make(true);
    }

    private function statusAndDueDateColumn($project)
    {
        if ($project->due_date < now()) {
            return 'Overdue';
        } else {
            switch ($project->status) {
                case 'PD':
                    return '<span class="font-italic" style="letter-spacing: 0.3px; color: #6c757d">Pending</span>';
                    break;
                case 'CP':
                    return '<span class="text-success" style="letter-spacing: 0.3px;">Completed</span>';
                    break;
                case 'INC':
                    return '<span class="text-danger" style="letter-spacing: 0.3px;">Incomplete</span>';
                    break;
            }
        }
    }


    private function priorityColumn($priority = '')
    {
        $dotColor = '';
        $badgeColor = '';
        switch($priority) {
            case 'low':
                $dotColor = 'label-success';
                $badgeColor = 'label label-pill label-inline label-light-success'; // Green for low
                break;
            case 'medium':
                $dotColor = 'label-warning';
                $badgeColor = 'label label-pill label-inline label-light-warning'; // Yellow for medium
                break;
            case 'high':
                $dotColor = 'label-danger';
                $badgeColor = 'label label-pill label-inline label-light-danger'; // Red for high
                break;
        }

        // Return priority with a badge
        return "<span class='$badgeColor fs-6 text-capitalize'><span class='label label-dot {$dotColor} mr-2'></span> {$priority}</span>";
    }

    private function actionColumn($id)
    {
        return "
            <a href='home/tasks/progress/{$id}/complete' class='btn btn-hover-light-success btn-circle btn-icon' title='Complete'>
                <i class='fas fa-check text-success'></i>
            </a>
            <a href='home/tasks/progress/{$id}/incomplete' class='btn btn-hover-light-danger btn-circle btn-icon' title='Incomplete'>
                <i class='fas fa-times text-danger'></i>
            </a> 
            <a href='javascript:void(0)' class='btn btn-hover-light-warning btn-circle btn-icon edit-btn' data-toggle='modal' data-target='#update_tasks' data-id='{$id}' title='Edit'>
                <i class='fas fa-edit text-warning'></i>
            </a>

            <a href='home/tasks/delete/{$id}' class='btn btn-hover-light-danger btn-circle btn-icon' title='remove'>
                <i class='fas fa-trash text-danger'></i>
            </a>
        ";
    }
}
