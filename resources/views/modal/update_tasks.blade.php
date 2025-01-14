<!-- Modal Update::Start -->
<div class="modal fade" id="update_tasks" tabindex="-1" role="dialog" aria-labelledby="updateTasksLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateTasksLabel">Update Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="modal-body">
        <form id="update_task_form">
          <input type="hidden" id="task_id" name="task_id">
          
          <div class="form-group row">
            <label for="update_title" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
              <input type="text" class="form-control text-uppercase" id="update_title" name="update_title" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="update_description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="update_description" name="update_description"></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label for="update_priority" class="col-sm-2 col-form-label">Priority</label>
            <div class="col-sm-10">
              <select class="form-control" id="update_priority" name="update_priority" required>
                <option disabled>Select Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="update_due_date" class="col-sm-2 col-form-label">Due Date</label>
            <div class="col-sm-10">
              <input type="date" id="update_due_date" class="form-control" name="update_due_date" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-primary" id="update_tasks_btn">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Update::End -->
