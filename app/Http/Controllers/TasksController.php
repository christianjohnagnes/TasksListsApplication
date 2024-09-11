<?php

namespace App\Http\Controllers;

use App\Models\TodoItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\TodoModel\CreateRequest;
use App\Http\Requests\TodoModel\UpdateRequest;

class TasksController extends Controller
{
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

    public function update(UpdateRequest $request)
    {
        $todoItem = TodoItem::preventActionIfStatusInOrComplete($request->task_id);
        if ($todoItem->exists()) {
            return response()->json(['error' => 'You cannot do it again.'], 400);
        } else {
            TodoItem::where('id', $request->task_id)
            ->where('user_id', auth()->user()->id)
            ->update([
                'title' => strtoupper($request->update_title),
                'description' => $request->update_description,
                'time_started' => now(),
                'priority' => $request->update_priority,
                'due_date' => $request->update_due_date,
                'user_id' => auth()->user()->id
            ]);
            return back()->with('status', 'Successfully Updated');
        }
    }

    public function progress($id, $outcome = null)
    {
        $outcome = ($outcome == 'complete') ? 'CP' : 'INC';
        $todoItem = TodoItem::preventActionIfStatusInOrComplete($id);

        if ($todoItem->exists()) {
            return back()->with('error', "You cannot do it again.");
        } else {
            TodoItem::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->update([
                'status' => $outcome,
                'time_ended' => now()
            ]);
            return back()->with('status', 'Successfully Updated');
        }
    }

    public function destroy($id)
    {
        $todoItem = TodoItem::preventActionIfStatusInOrComplete($id);
        if ($todoItem->exists()) {
            return back()->with('error', "You cannot do it again.");
        } else {
            TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->delete();
            return back()->with('status', 'Successfully Deleted');
        }
    }

    public function showDetail($id)
    {
        return TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->first();
    }

    public function showDescription($id)
    {
        return TodoItem::where('id', $id)->where('user_id', auth()->user()->id)->select('id', 'title', 'description', 'due_date')->first();
    }

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
                        $query->whereDate('due_date', '<', now());
                        break;
                    default:
                        $query->where('priority', $request->category);
                        break;
                }
            })
            ->select(
                'id',
                'title',
                'description',
                'due_date',
                'priority',
                'status'
            )
            ->get();

        return datatables()->of($tasks)
            ->addcolumn('title', function ($project) {
                return "<b>{$project->title}</b>";
            })
            ->addcolumn('description', function ($project) {
                if (strlen($project->description) <= 40) {
                    $description = $project->description;
                    $btnSeemore = "";
                } else {
                    $description = substr($project->description, 0, 40) . "...";
                    $btnSeemore = "<a href='javascript:void(0)' class='link-dark' name='read_more_description' data-toggle='modal' data-target='#full_description' data-id='{$project->id}' title='Click to show full description'>See more</a>";
                }

                return "<div class='' style='white-space: normal;'>
                    <span class='responsive-truncated-text'>{$description}</span>{$btnSeemore}
                    </div>";
            })
            ->addColumn('due_date', function ($project) {
                return date_format(date_create($project->due_date),"M. d, Y");
            })
            ->addColumn('priority', function ($project) {
                return $this->priorityColumn($project->priority);
            })
            ->addColumn('status_symbol', function ($project) {
                return ($project->due_date < now() && $project->status == 'PD') ? 'OD' : $project->status;
            })
            ->addColumn('status', function ($project) {
                return $this->statusAndDueDateColumn($project);
            })
            ->addColumn('actions', function ($project) {
                return $this->actionColumn($project->id);
            })
            ->rawColumns(['title', 'description', 'actions', 'status', 'priority']) 
            ->make(true);
    }

    private function statusAndDueDateColumn($project)
    {
        if ($project->due_date < now()) {
            return '<span class="text-danger" style="letter-spacing: 0.3px;">Overdue</span>';
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
                <i class='fas fa-trash-can text-danger'></i>
            </a>
        ";
    }
}
