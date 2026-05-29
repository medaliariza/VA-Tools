<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>User Access Control</h1>
            <p>Admins can create accounts, assign departments, and manage the `user` and `admin` access levels from one Laravel-managed screen.</p>
        </div>
        <span class="pill"><?php echo e($users->count()); ?> Registered Users</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create Account</h3>
            <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="fullname">Full name</label>
                    <input id="fullname" type="text" name="fullname" value="<?php echo e(old('fullname')); ?>" required>
                </div>
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                </div>
                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role); ?>" <?php if(old('role') === $role): echo 'selected'; endif; ?>><?php echo e(strtoupper($role)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="field">
                    <label for="department">Department</label>
                    <input id="department" type="text" name="department" value="<?php echo e(old('department')); ?>">
                </div>
                <div class="field-full">
                    <label class="checkbox-row">
                        <input type="checkbox" name="is_premium" value="1" <?php if(old('is_premium')): echo 'checked'; endif; ?>>
                        <span>Premium Organization Access</span>
                    </label>
                </div>
                <div class="field-full">
                    <label for="organization">Organization</label>
                    <input id="organization" type="text" name="organization" value="<?php echo e(old('organization')); ?>">
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
                    <button type="submit" class="button-dark">Create User</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Access Rules</h3>
            <ul class="clean-list">
                <li><span>Email verification</span><strong>Required before full access</strong></li>
                <li><span>Password policy</span><strong>12+ chars with mixed case, number, symbol</strong></li>
                <li><span>Roles available</span><strong>User and Admin</strong></li>
                <li><span>Session handling</span><strong>Managed by Laravel database sessions</strong></li>
            </ul>
        </article>
    </section>

    <section class="table-card">
        <h3>Manage Existing Users</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Organization</th>
                        <th>Verified</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($user->fullname); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td colspan="5">
                                <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="inline-actions">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="role">
                                        <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role); ?>" <?php if($user->role === $role): echo 'selected'; endif; ?>><?php echo e(strtoupper($role)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label class="checkbox-row">
                                        <input type="checkbox" name="is_premium" value="1" <?php if($user->is_premium): echo 'checked'; endif; ?>>
                                        <span>Premium</span>
                                    </label>
                                    <input type="text" name="department" value="<?php echo e($user->department); ?>" placeholder="Department">
                                    <input type="text" name="organization" value="<?php echo e($user->organization); ?>" placeholder="Organization">
                                    <span class="pill"><?php echo e($user->email_verified_at ? 'Verified' : 'Pending'); ?></span>
                                    <button type="submit" class="button-dark button-small">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'User Management | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\admin\users.blade.php ENDPATH**/ ?>