<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Tasks</h1>
            <p>Manage personal to-do items and mark progress without leaving the Laravel workspace.</p>
        </div>
        <span class="pill"><?php echo e($taskCount); ?> Active Records</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3><?php echo e($canManageOrganization ? 'Create Or Assign Task' : 'Add Task'); ?></h3>
            <form method="POST" action="<?php echo e(route('tasks.store')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <?php if($canManageOrganization): ?>
                    <div class="field-full">
                        <label for="assigned_to">Assign To</label>
                        <select id="assigned_to" name="assigned_to">
                            <option value="">Myself</option>
                            <?php $__currentLoopData = $assignableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($member->id); ?>" <?php if(old('assigned_to') == $member->id): echo 'selected'; endif; ?>><?php echo e($member->fullname); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="field-full">
                    <label for="task">Task</label>
                    <input id="task" type="text" name="task" value="<?php echo e(old('task')); ?>" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Task</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Task Overview</h3>
            <ul class="clean-list">
                <li><span>Total tasks</span><strong><?php echo e($taskCount); ?></strong></li>
                <li><span>Pending default</span><strong>Yes</strong></li>
                <li><span>Status tracking</span><strong>Pending or completed</strong></li>
                <?php if($canManageOrganization): ?>
                    <li><span>Team assignment</span><strong>Premium enabled</strong></li>
                <?php endif; ?>
            </ul>
        </article>
    </section>

    <section class="table-card">
        <h3>My Tasks</h3>
        <?php if($tasks->isEmpty()): ?>
            <p class="helper-text">No tasks yet. Add one to get started.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($task->task); ?></td>
                                <td><?php echo e($task->assignee?->fullname ?? 'Unknown'); ?></td>
                                <td><?php echo e($task->assigner?->fullname ?? 'Self'); ?></td>
                                <td><span class="pill"><?php echo e(ucfirst($task->status)); ?></span></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" class="inline-actions">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <select name="status">
                                            <option value="pending" <?php if($task->status === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                                            <option value="completed" <?php if($task->status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                        </select>
                                        <button type="submit" class="button-dark button-small">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Tasks | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\tasks\index.blade.php ENDPATH**/ ?>