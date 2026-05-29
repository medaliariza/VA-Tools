<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Reports</h1>
            <p>Submit updates, attach supporting files, and keep a record of work that needs review.</p>
        </div>
        <span class="pill"><?php echo e($reportCount); ?> Reports Submitted</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create a Report</h3>
            <form method="POST" action="<?php echo e(route('reports.store')); ?>" enctype="multipart/form-data" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="<?php echo e(old('title')); ?>" placeholder="Weekly update, project summary, support request">
                </div>
                <div class="field-full">
                    <label for="content">Report Content</label>
                    <textarea id="content" name="content" rows="8" required><?php echo e(old('content')); ?></textarea>
                </div>
                <div class="field-full">
                    <label for="file">Attachment</label>
                    <input id="file" type="file" name="file">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Send Report</button>
                </div>
            </form>
        </article>

        <article class="card">
            <?php if($canManageOrganization): ?>
                <h3>Request Report From Employee</h3>
                <form method="POST" action="<?php echo e(route('reports.request')); ?>" class="form-grid">
                    <?php echo csrf_field(); ?>
                    <div class="field-full">
                        <label for="requested_for">Employee</label>
                        <select id="requested_for" name="requested_for" required>
                            <option value="">Select employee</option>
                            <?php $__currentLoopData = $requestableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($member->id); ?>" <?php if(old('requested_for') == $member->id): echo 'selected'; endif; ?>><?php echo e($member->fullname); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="field-full">
                        <label for="request_title">Request Title</label>
                        <input id="request_title" type="text" name="title" value="<?php echo e(old('title')); ?>" placeholder="Request a daily productivity report" required>
                    </div>
                    <div class="field-full">
                        <button type="submit" class="button-dark">Request Report</button>
                    </div>
                </form>
            <?php else: ?>
                <h3>Submission Guide</h3>
                <ul class="clean-list">
                    <li><span>Content required</span><strong>Yes</strong></li>
                    <li><span>Attachment optional</span><strong>Yes</strong></li>
                    <li><span>Status tracking</span><strong>Requested, submitted, reviewed</strong></li>
                </ul>
            <?php endif; ?>
        </article>
    </section>

    <section class="table-card">
        <h3>My Reports</h3>
        <?php if($reports->isEmpty()): ?>
            <p class="helper-text">No reports submitted yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Requested By</th>
                            <th>Requested For</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($report->title ?: 'General Report'); ?></td>
                                <td><?php echo e(\Illuminate\Support\Str::limit($report->content, 100)); ?></td>
                                <td><?php echo e($report->requester?->fullname ?: 'Self'); ?></td>
                                <td><?php echo e($report->requestedFor?->fullname ?: ($report->user?->fullname ?? 'Self')); ?></td>
                                <td>
                                    <?php if($report->file): ?>
                                        <a href="<?php echo e(route('reports.download', $report)); ?>">Open attachment</a>
                                    <?php else: ?>
                                        <span class="meta-text">No attachment</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="pill"><?php echo e(ucfirst($report->status)); ?></span></td>
                                <td>
                                    <?php if($report->status === 'requested' && $report->user_id === auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('reports.update', $report)); ?>" enctype="multipart/form-data" class="form-grid">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <div class="field-full">
                                                <textarea name="content" rows="4" placeholder="Submit the requested report here" required><?php echo e(old('content')); ?></textarea>
                                            </div>
                                            <div class="field-full">
                                                <input type="file" name="file">
                                            </div>
                                            <div class="field-full">
                                                <button type="submit" class="button-dark button-small">Submit</button>
                                            </div>
                                        </form>
                                    <?php elseif($report->requested_by === auth()->id() && in_array($report->status, ['submitted', 'reviewed'], true)): ?>
                                        <form method="POST" action="<?php echo e(route('reports.update', $report)); ?>" class="inline-actions">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <select name="status">
                                                <option value="submitted" <?php if($report->status === 'submitted'): echo 'selected'; endif; ?>>Submitted</option>
                                                <option value="reviewed" <?php if($report->status === 'reviewed'): echo 'selected'; endif; ?>>Reviewed</option>
                                            </select>
                                            <button type="submit" class="button-dark button-small">Save</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="meta-text">No action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Reports | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\reports\index.blade.php ENDPATH**/ ?>