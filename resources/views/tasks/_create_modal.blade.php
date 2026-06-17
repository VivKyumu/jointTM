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

                    <div class="mb-3">
                        <label for="complexity_id" class="form-label">Complexity</label>
                        <select name="complexity_id" id="complexity_id" class="form-select" required>
                            @foreach ($complexities as $complexity)
                                <option value="{{ $complexity->id }}">
                                    {{ $complexity->name }} ({{ $complexity->duration }} days)
                                </option>
                            @endforeach
                        </select>
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

<!-- Toast -->
<div class="toast position-fixed bottom-0 end-0 p-3" id="taskCreatedToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" style="z-index: 1055;">
    <div class="toast-header bg-success text-white">
        <i class="fas fa-check-circle me-2"></i>
        <strong class="me-auto">Success</strong>
        <small>Just now</small>
        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Task created successfully!
    </div>
</div>

<!-- JS Script -->
<script>
    $(document).ready(function () {
        $('#createTaskForm').on('submit', function (e) {
            e.preventDefault();

            let $form = $(this);
            let $button = $form.find('button[type="submit"]');
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $form.serialize(),
                success: function (response) {
                    const task = response.task;

                    const newRow = `
                        <tr>
                            <td>${task.title}</td>
                            <td><span class="badge badge-info">${task.status}</span></td>
                            <td>${task.due_at ?? '-'}</td>
                            <td>${task.user_name}</td>
                            <td>
                                <a href="/tasks/${task.id}/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/tasks/${task.id}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;

                    $('#taskTable tbody').prepend(newRow);
                    $('#createTaskModal').modal('hide');
                    $('#createTaskForm')[0].reset();
                    $('#taskCreatedToast').toast('show');
                },
                error: function (xhr) {
                    alert('Something went wrong. Please check your input.');
                },
                complete: function () {
                    $button.prop('disabled', false).html('<i class="fas fa-save"></i> Save Task');
                }
            });
        });
    });
</script>
