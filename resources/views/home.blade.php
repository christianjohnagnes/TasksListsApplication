@extends('layouts.app')

@section('content')
<style type="text/css">
    th {
        color: #fff !important;
    }
</style>
<div class="container px-0">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-middle">
                            <span class="card-label font-weight-bolder text-dark">{{ __('Create tasks') }}</span>
                    </h3>
                </div>

                <div class="card-body">
                    <form id="create_tasks_form">
                      <div class="form-group">
                        <label for="title" class="font-weight-bolder">Title</label>
                          <input type="text" class="form-control text-uppercase" id="title" name="title" required>
                      </div>

                      <div class="form-group">
                        <label for="description" class="font-weight-bolder">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                      </div>

                      <div class="form-group">
                        <label for="priority" class="font-weight-bolder">Priority</label>
                        <select class="form-control" id="priority" name="priority" required>
                            <option selected disabled value="">Choose Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="due_date" class="font-weight-bolder">Due Date</label>
                        <input type="date" id="due_date" class="form-control" name="due_date" required>
                      </div>

                      <div class="pt-5">
                        <button type="button" class="btn btn-success w-100" id="create_tasks_btn">Save changes</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-middle">
                            <span class="card-label font-weight-bolder text-dark"><i class="fas fa-tasks me-2 text-primary"></i> {{ __('Task Lists') }}</span>
                    </h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @include('nav.table')
                </div>
            </div>
        </div>
    </div>
</div>
    @include('modal.update_tasks')
    @include('modal.full_description')
@endsection

@push('script')
    <script>
        $(document).ready(function (){
            projectDisplay("all");
            $('#myTab li').on('click', function (e) {
                e.preventDefault();
                $(this).find('.nav-link').tab('show');
                projectDisplay($(this).find(".nav-link.active").data('category'));
            });
        });

        $(document).on('click', '.edit-btn', function() {
            $.get({
                url: `home/tasks/show/update/${$(this).data('id')}`,
                success: function(task) {
                    // Populate the form with task data
                    const date = new Date(task.due_date);
                    $('#task_id').val(task.id);
                    $('#update_title').val(task.title);
                    $('#update_description').val(task.description);
                    $('#update_priority').val(task.priority);
                    $('#update_due_date').val(date.toISOString().split('T')[0]);
                },
                error: function(xhr) {
                    alert('Failed to load task details.');
                }
            });
        });

        $(document).on('click', '[name="read_more_description"]', function() {
            $.get({
                url: `home/tasks/show/description/${$(this).data('id')}`,
                success: function(info) {
                    console.log(info.title);
                    $('#full_description #full_description_title').html(info.title);
                    $('#full_description #full_description_display').html(`<label class="text-danger">*</label> ` + info.description);
                },
                error: function(xhr) {
                    alert('Failed to load task details.');
                }
            });
        });

        $("#create_tasks_btn").on('click', function () {
            // Gather form input values
            let title = $('input[name="title"]').val();
            let description = $('textarea[name="description"]').val();
            let priority = $('select[name="priority"] option:selected').val();
            let due_date = $('input[name="due_date"]').val();

            // Send AJAX request to create the task
            $.ajax({
                url: '{{ url("home/tasks/create") }}',
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
                    $('#create_tasks_form')[0].reset();
                    formsuccessDisplay($('#create_tasks_form'));
                    toastr.success('Task created successfully!');
                },
                error: function (xhr) {
                    formsuccessDisplay($('#create_tasks_form'));
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let objects = xhr.responseJSON.errors;
                        formFailDisplay($('#create_tasks_form'), Object.keys(objects), Object.values(objects));
                    } else {
                        toastr.error('Something went wrong! Please try again.');
                    }
                }
            });
        });

        $('#update_tasks_btn').on('click', function () {
            let taskID = $('input[name="task_id"]').val();
            let title = $('input[name="update_title"]').val();
            let description = $('textarea[name="update_description"]').val();
            let priority = $('select[name="update_priority"] option:selected').val();
            let due_date = $('input[name="update_due_date"]').val();

            $.post({
                url: 'home/tasks/update',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    task_id: taskID,
                    update_title: title,
                    update_description: description,
                    update_priority: priority,
                    update_due_date: due_date
                },
                success: function (response) {
                    projectDisplay($('#myTab li .nav-link.active').data('category'));
                    $('#update_tasks').modal('hide');
                    $('#update_task_form')[0].reset();
                    formsuccessDisplay($('#update_tasks'));
                    toastr.success('Task updated successfully!');
                },
                error: function (xhr) {
                    formsuccessDisplay($('#update_tasks'));
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let objects = xhr.responseJSON.errors;
                        formFailDisplay($('#update_tasks'), Object.keys(objects), Object.values(objects));
                    } else {
                        toastr.error(xhr.responseJSON.error, `Error: ${xhr.statusText}`);
                    }
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
                lengthChange: false,
                responsive: {
                    breakpoints: [
                        { name: 'desktop', width: Infinity },
                        { name: 'tablet',  width: 1024 },
                        { name: 'phone',   width: 768 }
                    ]
                },
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
                    { title: 'TITLE',       data: 'title',          name: 'title',          width: "30%",   className: "all"  },
                    { title: 'DESCRIPTION', data: 'description',    name: 'description',    width: "20%",   className: "tablet"  },
                    { title: 'STATUS',      data: 'status',         name: 'status',         width: "10%",   className: "tablet"  },
                    { title: 'DUE DATE',    data: 'due_date',       name: 'due_date',       width: "15%",   className: "all"  },
                    { title: 'PRIORITY',    data: 'priority',       name: 'priority',       width: "15%",   className: "all"  },
                    { title: 'ACTION',      data: 'actions',        name: 'actions',        width: "20%",   className: "tablet",   orderable: false, searchable: false }
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
                drawCallback: function(settings) {
                    let api = this.api();
                    api.rows().every(function() {
                        let row = this.node();
                        let data = api.row(row).data();

                        switch(data.status_symbol) {
                            case 'CP':
                                $(row).addClass('completed-row');
                                $(row).find('.btn').hide();
                                break;

                            case 'INC':
                                $(row).addClass('incompleted-row');
                                $(row).find('.btn').hide();
                                break;
                        }
                    });
                },
                createdRow: function(row, data, dataIndex) {
                    // Make the first column's cell clickable
                    $(row).find('td:eq(0)').on('click', function() {
                        let api = $('#projectsTable').DataTable();
                        let rowData = api.row(row).data();
                        const statusSymbols = ['CP', 'INC'];

                        // Check if status_symbol is in the array
                        if (statusSymbols.includes(rowData.status_symbol)) {
                            setTimeout(() => {
                                let nextRow = $(row).next('tr');
                                // Hide li elements in the next row
                                nextRow.find('td ul li span a.btn').closest('li').remove();
                            }, 0.025);
                        }
                    });
                }
            });
        }

        function formsuccessDisplay(parentTags) {
            parentTags.find(`[name]`)
                .removeClass('is-invalid text-danger')
                .end()
                .find(`span.invalid-feedback`)
                .remove();
        }

        function formFailDisplay(parentTags, keyObjects, valueObjects) {
            let htmlErrorDisplay = '<ul>';
            keyObjects.forEach(function(value, key) {
                htmlErrorDisplay += `<li><b class="text-capitalize">${value}:</b> ${valueObjects[key]}</li>`;
                parentTags.find(`[name="${value}"]`)
                    .removeClass('is-invalid text-danger')
                    .end()
                    .find(`span.is-invalid-${value}`)
                    .remove().end().find(`[name="${value}"]`)
                    .addClass('is-invalid text-danger')
                    .after(`<span class="invalid-feedback is-invalid-${value}" role="alert">
                        <strong>* ${valueObjects[key]}</strong>
                    </span>`);
            });
            htmlErrorDisplay += '</ul>';
            toastr.error(htmlErrorDisplay, "<h6 class='font-weight-bold'>Error message</h6>", {
                "closeButton": true,
                "progressBar": true,
                "newestOnTop": true
            });
        }
    </script>
@endpush
