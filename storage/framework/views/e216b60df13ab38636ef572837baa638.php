<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Messages</h1>
            <p>Chat in direct conversations, keep replies organized by contact, and manage your workspace messages in one place.</p>
        </div>
        <div class="inline-actions">
            <span class="pill"><?php echo e($messageCount); ?> Messages</span>
            <span class="pill"><?php echo e($unreadCount); ?> Unread</span>
            <?php if($unreadCount > 0): ?>
                <form method="POST" action="<?php echo e(route('chat.read-all')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="button-light button-small">Read All</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <section class="chat-layout">
        <article class="card">
            <h3>Contacts</h3>
            <form method="GET" action="<?php echo e(route('chat.index')); ?>" class="form-grid">
                <div class="field-full">
                    <label for="search">Search users</label>
                    <input id="search" type="search" name="search" value="<?php echo e($search); ?>" placeholder="Name, email, or role">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Search</button>
                </div>
            </form><br>

            <?php if($conversationSummaries->isEmpty()): ?>
                <p class="helper-text"><?php echo e($search ? 'No users matched your search.' : 'No other users are available for messaging yet.'); ?></p>
            <?php else: ?>
                <div class="conversation-list">
                    <?php $__currentLoopData = $conversationSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($contact = $summary['contact']); ?>
                        <?php ($latestMessage = $summary['latest_message']); ?>
                        <?php ($contactUnreadCount = $summary['unread_count']); ?>
                        <a
                            href="<?php echo e(route('chat.index', ['contact' => $contact->id])); ?>"
                            class="conversation-item <?php echo e($selectedContact?->id === $contact->id ? 'active' : ''); ?>"
                        >
                            <div class="conversation-item-main">
                                <strong><?php echo e($contact->fullname); ?></strong>
                                <span><?php echo e(strtoupper($contact->role)); ?></span>
                            </div>
                            <?php if($contactUnreadCount > 0): ?>
                                <span class="message-badge"><?php echo e($contactUnreadCount); ?></span>
                            <?php endif; ?>
                            <small><?php echo e($contact->email); ?></small>
                            <small>
                                <?php echo e($latestMessage ? \Illuminate\Support\Str::limit($latestMessage->message, 56) : 'Start a new conversation'); ?>

                            </small>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </article>

        <article class="card">
            <div class="chat-panel-header">
                <div>
                    <h3><?php echo e($selectedContact?->fullname ?? 'Choose a contact'); ?></h3>
                    <p class="helper-text">
                        <?php echo e($selectedContact ? 'Direct conversation with '.strtoupper($selectedContact->role) : 'Select a contact from the left to start messaging.'); ?>

                    </p>
                </div>
                <?php if($selectedContact): ?>
                    <div class="inline-actions">
                        <span class="pill"><?php echo e(strtoupper($selectedContact->role)); ?></span>
                        <form method="POST" action="<?php echo e(route('chat.read')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="contact_id" value="<?php echo e($selectedContact->id); ?>">
                            <button type="submit" class="button-light button-small">Mark Read</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($selectedContact): ?>
                <div class="chat-thread">
                    <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="chat-bubble <?php echo e($message->sender_id === $userId ? 'chat-bubble-own' : 'chat-bubble-other'); ?>">
                            <strong><?php echo e($message->sender_id === $userId ? 'You' : $message->sender?->fullname); ?></strong>
                            <div><?php echo e($message->message); ?></div>
                            <small><?php echo e($message->created_at?->format('M d, Y h:i A')); ?></small>
                            <form method="POST" action="<?php echo e(route('chat.destroy', $message)); ?>" class="inline-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="button-light button-small">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="helper-text">No messages in this conversation yet. Send the first one below.</p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="<?php echo e(route('chat.store')); ?>" class="form-grid">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="receiver_id" value="<?php echo e($selectedContact->id); ?>">
                    <div class="field-full">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required><?php echo e(old('message')); ?></textarea>
                    </div>
                    <div class="field-full">
                        <button type="submit" class="button-dark">Send Message</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="helper-text">There are no available contacts yet.</p>
            <?php endif; ?>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Chat | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\chat\index.blade.php ENDPATH**/ ?>