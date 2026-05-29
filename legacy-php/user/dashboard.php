<?php
include('../config/database.php');
include('../includes/app.php');

app_require_login();

$id = (int) $_SESSION['user_id'];

$todos = mysqli_query($conn, "SELECT * FROM todos WHERE user_id=$id ORDER BY id DESC");
$notes = mysqli_query($conn, "SELECT * FROM notes WHERE user_id=$id ORDER BY id DESC");
$inventoryRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory");
$messagesRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE sender_id=$id");

$todoCount = $todos ? mysqli_num_rows($todos) : 0;
$noteCount = $notes ? mysqli_num_rows($notes) : 0;
$inventoryCount = ($inventoryRes && ($row = mysqli_fetch_assoc($inventoryRes))) ? (int) $row['total'] : 0;
$messageCount = ($messagesRes && ($row = mysqli_fetch_assoc($messagesRes))) ? (int) $row['total'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | VA Tools</title>
    <style>
        :root {
            --cream: #ffd287;
            --orange: #ff874a;
            --orange-deep: #f46d34;
            --ink: #16100d;
            --ink-soft: #2a1d18;
            --panel: rgba(255, 236, 209, 0.44);
            --panel-strong: rgba(255, 247, 234, 0.74);
            --line: rgba(22, 16, 13, 0.12);
            --button: #261916;
            --button-hover: #1b100d;
            --shadow: 0 28px 60px rgba(80, 33, 10, 0.18);
            --shell: min(1220px, calc(100% - 48px));
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Arial Black", Impact, sans-serif;
            background: linear-gradient(90deg, var(--orange) 0%, var(--cream) 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .page-shell {
            width: var(--shell);
            margin: 0 auto;
            padding: 26px 0 48px;
        }

        .hero-panel,
        .content-panel {
            position: relative;
            overflow: hidden;
            border-radius: 34px;
            box-shadow: var(--shadow);
        }

        .hero-panel {
            min-height: 760px;
            padding: 30px 44px 44px;
            background: linear-gradient(90deg, var(--orange) 0%, var(--cream) 100%);
        }

        .hero-panel::before,
        .hero-panel::after,
        .content-panel::before {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .hero-panel::before {
            width: 320px;
            height: 320px;
            top: -160px;
            right: -90px;
            background: rgba(255, 247, 229, 0.22);
        }

        .hero-panel::after {
            width: 260px;
            height: 260px;
            left: -120px;
            bottom: -120px;
            background: rgba(255, 120, 54, 0.16);
        }

        .hero-panel > *,
        .content-panel > * {
            position: relative;
            z-index: 1;
        }

        .hero-nav {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 20px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            border: 1px solid rgba(22, 16, 13, 0.14);
            background: rgba(255, 226, 185, 0.34);
            font-size: 1rem;
            letter-spacing: 0.2em;
        }

        .brand-text {
            font-size: 2rem;
            line-height: 0.95;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .brand-text small {
            display: block;
            margin-top: 6px;
            font-family: "Courier New", monospace;
            font-size: 0.78rem;
            letter-spacing: 0.16em;
        }

        .top-links {
            display: flex;
            justify-content: center;
            gap: 34px;
            flex-wrap: wrap;
            padding: 0 10px;
            font-family: "Courier New", monospace;
            font-size: 1.08rem;
            text-transform: uppercase;
        }

        .top-links a {
            position: relative;
            padding-bottom: 4px;
        }

        .top-links a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: var(--ink);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }

        .top-links a:hover::after {
            transform: scaleX(1);
        }

        .button-dark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 208px;
            min-height: 72px;
            padding: 16px 28px;
            border-radius: 18px;
            background: var(--button);
            color: #fdf6ee;
            font-family: "Courier New", monospace;
            font-size: 1.08rem;
            letter-spacing: 0.36em;
            text-transform: uppercase;
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .button-dark:hover {
            transform: translateY(-2px);
            background: var(--button-hover);
        }

        .hero-copy-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.36fr) minmax(220px, 0.64fr);
            gap: 24px;
            margin-top: 128px;
            align-items: end;
        }

        .hero-title {
            margin: 0 0 40px;
            font-size: clamp(4.1rem, 8vw, 7.6rem);
            line-height: 0.84;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .hero-copy {
            max-width: 820px;
            margin: 0;
            font-family: "Courier New", monospace;
            font-size: clamp(1.18rem, 1.8vw, 1.54rem);
            line-height: 1.02;
        }

        .hero-copy + .hero-copy {
            margin-top: 28px;
        }

        .hero-actions {
            display: grid;
            gap: 16px;
            justify-items: end;
        }

        .content-panel {
            margin-top: 22px;
            padding: 30px;
            background: linear-gradient(180deg, rgba(255, 247, 235, 0.92) 0%, rgba(255, 235, 207, 0.86) 100%);
        }

        .content-panel::before {
            width: 240px;
            height: 240px;
            right: -80px;
            top: -90px;
            background: rgba(255, 170, 100, 0.16);
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 18px;
        }

        .feature-card,
        .text-card,
        .stats-card {
            padding: 24px;
            border-radius: 28px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }

        .section-label {
            margin: 0 0 12px;
            font-size: 1rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .section-body,
        .feature-list li,
        .stats-list li,
        .mini-note,
        .activity-list li {
            font-family: "Courier New", monospace;
            font-size: 1rem;
            line-height: 1.5;
        }

        .feature-list,
        .stats-list,
        .activity-list {
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 14px;
        }

        .feature-list li,
        .stats-list li,
        .activity-list li {
            padding: 14px 16px;
            border-radius: 18px;
            background: var(--panel);
            border: 1px solid rgba(22, 16, 13, 0.08);
        }

        .feature-list strong,
        .stats-list strong,
        .activity-list strong {
            display: block;
            margin-bottom: 6px;
            font-family: "Arial Black", Impact, sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-top: 18px;
        }

        .stat-tile {
            padding: 22px 16px;
            border-radius: 24px;
            text-align: center;
            background: rgba(255, 181, 113, 0.26);
            border: 1px solid rgba(22, 16, 13, 0.08);
        }

        .stat-tile strong {
            display: block;
            margin-bottom: 8px;
            font-size: 2.5rem;
            line-height: 1;
        }

        .stat-tile span {
            display: block;
            font-family: "Courier New", monospace;
            font-size: 0.92rem;
            line-height: 1.35;
            text-transform: uppercase;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 18px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(38, 25, 22, 0.08);
            font-family: "Courier New", monospace;
            font-size: 0.8rem;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .activity-list li {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: flex-start;
        }

        .footer-strip {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 18px;
            padding: 18px 22px;
            border-radius: 22px;
            background: rgba(255, 164, 97, 0.24);
            border: 1px solid rgba(22, 16, 13, 0.08);
        }

        .footer-strip p {
            margin: 0;
            font-family: "Courier New", monospace;
            line-height: 1.5;
        }

        @media (max-width: 1100px) {
            .hero-nav,
            .hero-copy-grid,
            .section-grid,
            .activity-grid {
                grid-template-columns: 1fr;
            }

            .hero-nav {
                justify-items: start;
            }

            .top-links {
                justify-content: flex-start;
                padding: 0;
            }

            .hero-copy-grid {
                margin-top: 72px;
            }

            .hero-actions {
                justify-items: start;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .page-shell {
                width: min(100% - 24px, 1220px);
                padding-top: 12px;
            }

            .hero-panel,
            .content-panel {
                border-radius: 26px;
            }

            .hero-panel {
                min-height: auto;
                padding: 22px 18px 24px;
            }

            .content-panel {
                padding: 18px;
            }

            .brand-text {
                font-size: 1.35rem;
            }

            .hero-title {
                margin-bottom: 26px;
                font-size: clamp(3rem, 18vw, 4.7rem);
            }

            .button-dark {
                width: 100%;
                min-width: 0;
                letter-spacing: 0.22em;
            }

            .stats-grid,
            .activity-grid {
                grid-template-columns: 1fr;
            }

            .activity-list li {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <section class="hero-panel" id="home">
            <div class="hero-nav">
                <a class="brand" href="#home">
                    <span class="brand-mark">VA</span>
                    <span class="brand-text">
                        Virtual Assistant Tools
                        <small><?php echo app_escape(app_user_name()); ?> workspace</small>
                    </span>
                </a>

                <nav class="top-links" aria-label="Primary">
                    <a href="#home">Home</a>
                    <a href="#about">About</a>
                    <a href="#features">FAQ</a>
                    <a href="chat.php">Contact</a>
                </nav>

                <a class="button-dark" href="../auth/logout.php">Logout</a>
            </div>

            <div class="hero-copy-grid">
                <div>
                    <h1 class="hero-title">VA Tools</h1>
                    <p class="hero-copy">
                        Welcome to Web-Based VA Tools - your all-in-one digital workspace designed to simplify
                        how you manage information, tasks, and communication.
                    </p>
                    <p class="hero-copy">
                        In today&apos;s fast-paced environment, handling emails, data, notes, and tasks across
                        multiple platforms can be overwhelming. Our system brings everything together into one
                        centralized platform, allowing you to stay organized, efficient, and in control.
                    </p>
                </div>

                <div class="hero-actions">
                    <a class="button-dark" href="report.php">Start Now!</a>
                </div>
            </div>
        </section>

        <section class="content-panel" id="about">
            <div class="section-grid">
                <article class="text-card">
                    <h2 class="section-label">About Us</h2>
                    <p class="section-body">
                        Whether you&apos;re a student, business owner, or part of an organization, our platform
                        acts as your virtual assistant, helping you streamline workflows, manage data accurately,
                        and collaborate with ease.
                    </p>
                    <h2 class="section-label" style="margin-top: 24px;">Our Mission</h2>
                    <p class="section-body">
                        Our platform is built with productivity, security, and usability in mind. Every feature
                        is designed to reduce manual work and improve efficiency.
                    </p>
                </article>

                <article class="stats-card">
                    <h2 class="section-label">Workspace Snapshot</h2>
                    <p class="mini-note">
                        Live dashboard totals are folded into the same visual system so the page follows the PDF
                        while still working as your logged-in control center.
                    </p>
                    <div class="stats-grid">
                        <div class="stat-tile">
                            <strong><?php echo app_escape($todoCount); ?></strong>
                            <span>Tasks</span>
                        </div>
                        <div class="stat-tile">
                            <strong><?php echo app_escape($noteCount); ?></strong>
                            <span>Notes</span>
                        </div>
                        <div class="stat-tile">
                            <strong><?php echo app_escape($inventoryCount); ?></strong>
                            <span>Inventory</span>
                        </div>
                        <div class="stat-tile">
                            <strong><?php echo app_escape($messageCount); ?></strong>
                            <span>Messages</span>
                        </div>
                    </div>
                </article>
            </div>

            <div class="activity-grid" id="features">
                <article class="feature-card">
                    <h2 class="section-label">Key Features</h2>
                    <ul class="feature-list">
                        <li>
                            <strong>Task Management</strong>
                            Stay on track with built-in task lists, reminders, and checklists to help you meet
                            deadlines and stay productive.
                        </li>
                        <li>
                            <strong>Data Handling</strong>
                            Easily encode, organize, and manage large amounts of data with a structured database
                            system. Perfect for records, reports, and business information.
                        </li>
                        <li>
                            <strong>Inventory Tracking</strong>
                            Track and manage inventory in real time with organized listings and status updates.
                        </li>
                        <li>
                            <strong>Dashboards</strong>
                            Visualize data and performance through dashboards to make smarter, data-driven decisions.
                        </li>
                    </ul>
                </article>

                <article class="feature-card">
                    <h2 class="section-label">What Makes Us Different</h2>
                    <ul class="feature-list">
                        <li>
                            <strong>Live Communication</strong>
                            Communicate instantly with admins or team members for faster coordination and support.
                        </li>
                        <li>
                            <strong>Instant Updates</strong>
                            Get instant updates on activities, messages, and system alerts so you never miss
                            important information.
                        </li>
                        <li>
                            <strong>Secure Access</strong>
                            Different access levels for users and admins ensure proper control, privacy, and
                            system security.
                        </li>
                        <li>
                            <strong>Document Storage</strong>
                            Securely upload and store important files, documents, and records in one accessible
                            location.
                        </li>
                    </ul>
                </article>
            </div>

            <div class="activity-grid" style="margin-top: 18px;">
                <article class="feature-card">
                    <h2 class="section-label">Recent To-Do Items</h2>
                    <?php if ($todoCount > 0): ?>
                        <ul class="activity-list">
                            <?php
                            $shownTodos = 0;
                            while (($todo = mysqli_fetch_assoc($todos)) && $shownTodos < 4):
                                $shownTodos++;
                            ?>
                                <li>
                                    <div>
                                        <strong><?php echo app_escape($todo['task']); ?></strong>
                                        <span class="mini-note">Stay on track with built-in task lists and reminders.</span>
                                    </div>
                                    <span class="pill"><?php echo app_escape(ucfirst($todo['status'])); ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="section-body">No tasks yet. Add one from your workflow tools when you are ready.</p>
                    <?php endif; ?>
                </article>

                <article class="feature-card">
                    <h2 class="section-label">Saved Notes</h2>
                    <?php if ($noteCount > 0): ?>
                        <ul class="activity-list">
                            <?php
                            $shownNotes = 0;
                            while (($note = mysqli_fetch_assoc($notes)) && $shownNotes < 4):
                                $shownNotes++;
                            ?>
                                <li>
                                    <div>
                                        <strong><?php echo app_escape($note['title']); ?></strong>
                                        <span class="mini-note"><?php echo app_escape(app_excerpt($note['content'], 96)); ?></span>
                                    </div>
                                    <span class="pill">Note</span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="section-body">No notes found for this account yet.</p>
                    <?php endif; ?>
                </article>
            </div>

            <div class="footer-strip">
                <p>
                    Logged in as <strong><?php echo app_escape(app_user_name()); ?></strong>
                    with <strong><?php echo app_escape(strtoupper($_SESSION['role'] ?? 'USER')); ?></strong> access.
                </p>
                <p>
                    Quick links:
                    <a href="chat.php"><strong>Chat</strong></a>,
                    <a href="inventory.php"><strong>Inventory</strong></a>,
                    <a href="profile.php"><strong>Profile</strong></a>,
                    <a href="report.php"><strong>Reports</strong></a>
                </p>
            </div>
        </section>
    </div>
</body>
</html>
