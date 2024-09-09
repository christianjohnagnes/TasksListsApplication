@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-tasks me-2"></i> {{ __('Task List') }} <button href="javascript:void(0)" style="float: right" class="btn btn-outline-primary" data-toggle="modal" data-target="#create_tasks">Create Task</button>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('nav.table')
                </div>
            </div>
        </div>
    </div>
</div>
    @include('modal.create_tasks')
@endsection

@push('style')
<style>
    .completed-row {
        position: relative;
        background-color: white;
    }

    .completed-row::after {
        content: 'COMPLETED';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.6);
        color: var(--teal);
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        z-index: 10;
        text-align: center;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .completed-row td {
        opacity: 0.3;
    }

    .overdue-row {
        position: relative;
        background-color: #ffdddd;
    }

    .overdue-row::after {
        content: 'OVERDUE';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 0, 0, 0.6);
        color: var(--warning);
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        z-index: 10;
        text-align: center;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .overdue-row {
        position: relative;
        background-color: #ffdddd;
    }

    .overdue-row .btn {
        z-index: 1;
    }
</style>

@endpush

@push('script')
    <script>
        flatpickr("#due_date, #update_due_date", {
            dateFormat: "Y-m-d",
            allowInput: false,
            minuteIncrement: 1
        });

        $(document).ready(function (){
            projectDisplay("all");
            $('#myTab li').on('click', function (e) {
                console.log('test');
                e.preventDefault();
                $(this).find('.nav-link').tab('show');
                projectDisplay($(this).find(".nav-link.active").data('category'));
            });
        
        });


        $("#create_tasks_btn").on('click', function () {
            // Gather form input values
            let title = $('input[name="title"]').val();
            let description = $('textarea[name="description"]').val();
            let priority = $('select[name="priority"] option:selected').val();
            let due_date = $('input[name="due_date"]').val();

            // Validate that all required fields are filled
            if (title === '' || priority === '' || due_date === '') {
                alert("All required fields must be filled!");
                return;
            }

            // Send AJAX request to create the task
            $.ajax({
                url: '{{ url("home/tasks/create") }}',  // Update this to the actual route for task creation
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    title: title,
                    description: description,
                    priority: priority,
                    due_date: due_date
                },
                success: function (response) {
                    projectDisplay($('#myTab li .nav-link.active').data('category'));
                    $('#create_tasks').modal('hide');
                    $('#create_tasks_form')[0].reset();
                    toastr.success('Task created successfully!')
                },
                error: function (xhr) {
                    // Handle error
                    alert('Something went wrong. Please try again.');
                }
            });
        });


        $(document).on('click', '.edit-btn', function() {
            $.get({
                url: `home/tasks/show/update/${$(this).data('id')}`,
                success: function(task) {
                    // Populate the form with task data
                    $('#task_id').val(task.id);
                    $('#update_title').val(task.title);
                    $('#update_description').val(task.description);
                    $('#update_priority').val(task.priority);
                    $('#update_due_date').val(task.due_date);
                },
                error: function(xhr) {
                    alert('Failed to load task details.');
                }
            });
        });


        $('#update_tasks_btn').on('click', function () {
            let taskID = $('input[name="task_id"]').val();
            let title = $('input[name="update_title"]').val();
            let description = $('textarea[name="update_description"]').val();
            let priority = $('select[name="update_priority"] option:selected').val();
            let due_date = $('input[name="update_due_date"]').val();

            // Validate that all required fields are filled
            if (title === '' || priority === '' || due_date === '') {
                alert("All required fields must be filled!");
                return;
            }

            $.post({
                url: 'home/tasks/update',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    task_id: taskID,
                    title: title,
                    description: description,
                    priority: priority,
                    due_date: due_date
                },
                success: function (response) {
                    projectDisplay($('#myTab li .nav-link.active').data('category'));
                    $('#update_tasks').modal('hide');
                    $('#update_task_form')[0].reset();
                    toastr.success('Task updated successfully!');
                },
                error: function (xhr) {
                    // Handle error
                    alert('Something went wrong. Please try again.');
                }
            });
        });

        function projectDisplay(category = '') {
            // Initialize the DataTable
            $('#projectsTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, 
                paging: true,
                responsive: true,
                searching: true,
                ordering: false,
                lengthChange: false,
                pageLength: 10,
                ajax: {
                    url: 'home/tasks/show',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: function (d) {
                        d.category = category;
                    }
                },
                columns: [
                    { title: 'TITLE', data: 'title', name: 'title' },
                    { title: 'DESCRIPTION', data: 'description', name: 'description' },
                    { title: 'STATUS', data: 'status', name: 'status' },
                    { title: 'DUE DATE', data: 'due_date', name: 'due_date' },
                    { title: 'PRIORITY', data: 'priority', name: 'priority' },
                    { title: 'ACTION', data: 'actions', name: 'actions', width: "15%", orderable: false, searchable: false }
                ],
                language: {
                    paginate: {
                        next: '<i class="fa fa-chevron-right"></i>',
                        previous: '<i class="fa fa-chevron-left"></i>'
                    },
                    search: 'Search:',
                    zeroRecords: 'No projects found',
                    info: 'Showing _START_ to _END_ of _TOTAL_ projects',
                    infoEmpty: 'No projects available',
                    infoFiltered: '(filtered from _MAX_ total projects)'
                },
                dom: '<"top"f>rt<"bottom"ip><"clear">',
                drawCallback: function(settings) {
                    let api = this.api();
                    api.rows().every(function() {
                        let row = this.node();
                        let data = api.row(row).data();
                        if (data.status === 'Completed') {
                            $(row).addClass('completed-row');
                            $(row).find('.btn').hide(); // Hide action buttons
                        } else if (data.status == 'Overdue') {
                            $(row).addClass('overdue-row');
                        }
                    });
                }
            });
        }
    </script>
@endpush
