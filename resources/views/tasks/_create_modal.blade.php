<!-- Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-sm border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="createTaskModalLabel">
            <i class="fas fa-plus-circle"></i> Create New Task
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="createTaskForm" method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="title">Task Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>
          <div class="alert alert-success d-none" id="taskSuccessMsg">
            <i class="fas fa-check-circle"></i> Task created successfully!
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Save Task
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
