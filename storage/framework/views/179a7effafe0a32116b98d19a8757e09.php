<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Notes</h1>
            <p>Store reminders, references, and personal notes directly inside the Laravel workspace.</p>
        </div>
        <span class="pill"><?php echo e($noteCount); ?> Saved Notes</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create Note</h3>
            <form method="POST" action="<?php echo e(route('notes.store')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="<?php echo e(old('title')); ?>" required>
                </div>
                <div class="field-full">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="8" required><?php echo e(old('content')); ?></textarea>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Note</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Recent Notes</h3>
            <?php if($notes->isEmpty()): ?>
                <p class="helper-text">No notes saved yet.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="item-main">
                                <strong><?php echo e($note->title); ?></strong>
                                <span class="meta-text"><?php echo e(\Illuminate\Support\Str::limit($note->content, 120)); ?></span>
                            </div>
                            <span class="pill">Note</span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Notes | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\notes\index.blade.php ENDPATH**/ ?>