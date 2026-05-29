<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Organization</h1>
            <p>Premium workspace owners can manage their organization, add employees, and keep team access organized from one place.</p>
        </div>
        <span class="pill"><?php echo e($members->count()); ?> Members</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Organization Settings</h3>
            <form method="POST" action="<?php echo e(route('organization.update')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="organization">Organization Name</label>
                    <input id="organization" type="text" name="organization" value="<?php echo e(old('organization', $owner->organization ?: $owner->fullname.\"'s Organization\")); ?>" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Organization</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Add Organization Member</h3>
            <form method="POST" action="<?php echo e(route('organization.members.store')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="fullname">Full name</label>
                    <input id="fullname" type="text" name="fullname" value="<?php echo e(old('fullname')); ?>" required>
                </div>
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                </div>
                <div class="field-full">
                    <label for="department">Department</label>
                    <input id="department" type="text" name="department" value="<?php echo e(old('department')); ?>">
                </div>
                <div class="field">
                    <label for="password">Temporary password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Add Member</button>
                </div>
            </form>
        </article>
    </section>

    <section class="table-card">
        <h3>Organization Members</h3>
        <?php if($members->isEmpty()): ?>
            <p class="helper-text">No members added yet. Create your first employee account above.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Organization</th>
                            <th>Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($member->fullname); ?></td>
                                <td><?php echo e($member->email); ?></td>
                                <td><?php echo e($member->department ?: 'Not assigned'); ?></td>
                                <td><?php echo e($member->organization ?: 'Not assigned'); ?></td>
                                <td><span class="pill"><?php echo e($member->email_verified_at ? 'Verified' : 'Pending'); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Organization | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\organization\index.blade.php ENDPATH**/ ?>