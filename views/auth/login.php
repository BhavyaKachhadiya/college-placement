<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sign In — College Management System</title>
    <meta name="description" content="Sign in to your College Portal account — Student & Admin Authentication">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <style>
        body.auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #060919 0%, #0a0e27 35%, #141d47 70%, #1e2d6b 100%);
            position: relative;
            overflow-x: hidden;
            padding: 2rem 1rem;
        }

        /* Decorative Background Orbs */
        body.auth-page::before {
            content: '';
            position: fixed;
            top: -15%;
            left: -10%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(59, 91, 219, 0.25) 0%, rgba(124, 58, 237, 0.08) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }

        body.auth-page::after {
            content: '';
            position: fixed;
            bottom: -15%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.22) 0%, rgba(59, 91, 219, 0.08) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
            animation: fadeInAuth 0.4s var(--ease-out);
        }

        @keyframes fadeInAuth {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-header-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo-badge {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--brand-500) 0%, var(--accent-500) 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            color: #ffffff;
            box-shadow: 0 12px 32px rgba(59, 91, 219, 0.45);
        }

        .auth-header-brand h1 {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .auth-header-brand p {
            color: var(--neutral-400);
            font-size: 0.92rem;
        }

        /* Glassmorphic Auth Card */
        .auth-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 2.25rem 2rem;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* Dual Role Selector Tabs */
        .auth-role-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(2, 6, 23, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 4px;
            margin-bottom: 1.75rem;
        }

        .auth-role-tab {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--neutral-400);
            cursor: pointer;
            transition: all 0.2s var(--ease-out);
            border: none;
            background: transparent;
            text-decoration: none;
        }

        .auth-role-tab:hover {
            color: #ffffff;
        }

        .auth-role-tab.active {
            background: linear-gradient(135deg, var(--brand-500) 0%, var(--brand-600) 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(59, 91, 219, 0.35);
        }

        .auth-role-tab i {
            font-size: 0.95rem;
        }

        /* Alert notifications */
        .auth-alert {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            line-height: 1.45;
        }

        .auth-alert-danger {
            background: rgba(225, 29, 72, 0.15);
            border: 1px solid rgba(225, 29, 72, 0.35);
            color: #fecdd3;
        }

        .auth-alert-success {
            background: rgba(22, 163, 74, 0.15);
            border: 1px solid rgba(22, 163, 74, 0.35);
            color: #bbf7d0;
        }

        .auth-alert i {
            font-size: 1.05rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        /* Form Inputs */
        .auth-field-group {
            margin-bottom: 1.25rem;
        }

        .auth-field-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--neutral-300);
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .auth-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .auth-input-icon {
            position: absolute;
            left: 1rem;
            color: var(--neutral-400);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .auth-input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            background: rgba(2, 6, 23, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.95rem;
            font-family: var(--font-primary);
            transition: all 0.2s var(--ease-out);
            outline: none;
        }

        .auth-input:focus {
            border-color: var(--brand-400);
            background: rgba(2, 6, 23, 0.75);
            box-shadow: 0 0 0 3px rgba(92, 124, 250, 0.2);
        }

        .auth-input:focus + .auth-input-icon,
        .auth-input-wrapper:focus-within .auth-input-icon {
            color: var(--brand-300);
        }

        .auth-toggle-pwd {
            position: absolute;
            right: 0.85rem;
            background: none;
            border: none;
            color: var(--neutral-400);
            cursor: pointer;
            padding: 0.4rem;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .auth-toggle-pwd:hover {
            color: #ffffff;
        }

        /* Quick Fill Helper Box */
        .auth-quick-box {
            background: rgba(255, 255, 255, 0.04);
            border: 1px dashed rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .auth-quick-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--neutral-300);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.65rem;
        }

        .auth-quick-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .auth-quick-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(59, 91, 219, 0.15);
            border: 1px solid rgba(91, 120, 250, 0.25);
            color: var(--brand-200);
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s var(--ease-out);
        }

        .auth-quick-chip:hover {
            background: rgba(59, 91, 219, 0.35);
            border-color: var(--brand-400);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .auth-submit-btn {
            width: 100%;
            padding: 0.95rem 1.5rem;
            background: linear-gradient(135deg, var(--brand-500) 0%, var(--brand-600) 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            font-family: var(--font-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 24px rgba(59, 91, 219, 0.4);
            transition: all 0.22s var(--ease-out);
        }

        .auth-submit-btn:hover {
            background: linear-gradient(135deg, var(--brand-400) 0%, var(--brand-500) 100%);
            box-shadow: 0 12px 28px rgba(59, 91, 219, 0.55);
            transform: translateY(-1px);
        }

        .auth-submit-btn:active {
            transform: translateY(0);
        }

        .auth-footer-text {
            text-align: center;
            margin-top: 1.75rem;
            color: var(--neutral-400);
            font-size: 0.82rem;
        }
    </style>
</head>
<body class="auth-page">

    <div class="auth-container">

        <!-- Header / Logo -->
        <div class="auth-header-brand">
            <div class="auth-logo-badge">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1>College Portal</h1>
            <p>Institutional Management &amp; Student Portal</p>
        </div>

        <!-- Glass Auth Card -->
        <div class="auth-card">

            <!-- Role Selector Tabs -->
            <div class="auth-role-tabs" role="tablist">
                <button type="button" 
                        class="auth-role-tab <?= $activeTab === 'student' ? 'active' : '' ?>"
                        onclick="switchTab('student')" id="tabBtnStudent" role="tab">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Student Login</span>
                </button>
                <button type="button" 
                        class="auth-role-tab <?= $activeTab === 'admin' ? 'active' : '' ?>"
                        onclick="switchTab('admin')" id="tabBtnAdmin" role="tab">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Admin Login</span>
                </button>
            </div>

            <!-- Error / Success Alert -->
            <?php if (!empty($error)): ?>
                <div class="auth-alert auth-alert-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="auth-alert auth-alert-success" role="alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <div><?= htmlspecialchars($success) ?></div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="index.php?module=auth&action=processLogin" method="POST" id="authLoginForm">
                <input type="hidden" name="login_type" id="loginTypeInput" value="<?= htmlspecialchars($activeTab) ?>">

                <!-- Dynamic Username Label -->
                <div class="auth-field-group">
                    <label id="usernameLabel" for="usernameInput">
                        <?= $activeTab === 'student' ? 'Enrollment Number' : 'Admin Username' ?>
                    </label>
                    <div class="auth-input-wrapper">
                        <i class="fa-solid <?= $activeTab === 'student' ? 'fa-id-card' : 'fa-user' ?> auth-input-icon" id="usernameIcon"></i>
                        <input type="text" 
                               name="username" 
                               id="usernameInput" 
                               class="auth-input"
                               placeholder="<?= $activeTab === 'student' ? 'e.g. 250114305001' : 'e.g. admin' ?>" 
                               required 
                               autocomplete="username"
                               autofocus>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="auth-field-group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label for="passwordInput">Password</label>
                        <span id="pwdHint" style="font-size: 0.76rem; color: var(--brand-300);">
                            <?= $activeTab === 'student' ? '(Password = Enrollment No)' : '(Default: admin)' ?>
                        </span>
                    </div>
                    <div class="auth-input-wrapper">
                        <i class="fa-solid fa-lock auth-input-icon"></i>
                        <input type="password" 
                               name="password" 
                               id="passwordInput" 
                               class="auth-input"
                               placeholder="Enter your password" 
                               required
                               autocomplete="current-password">
                        <button type="button" class="auth-toggle-pwd" onclick="togglePasswordVisibility()" title="Toggle password visibility">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Test Helper Box -->
                <div class="auth-quick-box">
                    <div class="auth-quick-header">
                        <span><i class="fa-solid fa-bolt" style="color:#fbbf24;"></i> Quick Click Demo Login</span>
                        <span style="font-weight: 400; opacity: 0.8;" id="quickHintText">Click to auto-fill</span>
                    </div>

                    <!-- Student Quick Fill -->
                    <div class="auth-quick-chips" id="studentQuickChips" style="<?= $activeTab === 'admin' ? 'display:none;' : '' ?>">
                        <?php if (!empty($sampleStudents)): ?>
                            <?php foreach ($sampleStudents as $st): ?>
                                <button type="button" class="auth-quick-chip" onclick="fillCredentials('<?= htmlspecialchars($st['enroll_no']) ?>', '<?= htmlspecialchars($st['enroll_no']) ?>')">
                                    <i class="fa-solid fa-user-graduate"></i>
                                    <span><?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['enroll_no']) ?>)</span>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <button type="button" class="auth-quick-chip" onclick="fillCredentials('250114305001', '250114305001')">
                                <i class="fa-solid fa-user-graduate"></i>
                                <span>Aarav Mehta (250114305001)</span>
                            </button>
                            <button type="button" class="auth-quick-chip" onclick="fillCredentials('250114305002', '250114305002')">
                                <i class="fa-solid fa-user-graduate"></i>
                                <span>Ananya Sharma (250114305002)</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Admin Quick Fill -->
                    <div class="auth-quick-chips" id="adminQuickChips" style="<?= $activeTab === 'student' ? 'display:none;' : '' ?>">
                        <button type="button" class="auth-quick-chip" onclick="fillCredentials('admin', 'admin')">
                            <i class="fa-solid fa-key"></i>
                            <span>Admin / admin</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-submit-btn" id="btnSubmit">
                    <span id="submitBtnText">Sign In to Account</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="auth-footer-text">
            College Management System &bull; System Version 2.0
        </div>

    </div>

    <script>
        function switchTab(type) {
            const loginTypeInput = document.getElementById('loginTypeInput');
            const usernameLabel  = document.getElementById('usernameLabel');
            const usernameInput  = document.getElementById('usernameInput');
            const passwordInput  = document.getElementById('passwordInput');
            const usernameIcon   = document.getElementById('usernameIcon');
            const pwdHint        = document.getElementById('pwdHint');

            const tabStudent = document.getElementById('tabBtnStudent');
            const tabAdmin   = document.getElementById('tabBtnAdmin');

            const studentChips = document.getElementById('studentQuickChips');
            const adminChips   = document.getElementById('adminQuickChips');

            loginTypeInput.value = type;
            usernameInput.value = '';
            passwordInput.value = '';

            if (type === 'admin') {
                tabAdmin.classList.add('active');
                tabStudent.classList.remove('active');

                usernameLabel.innerText = 'Admin Username';
                usernameInput.placeholder = 'e.g. admin';
                usernameIcon.className = 'fa-solid fa-user-shield auth-input-icon';
                pwdHint.innerText = '(Default: admin)';

                studentChips.style.display = 'none';
                adminChips.style.display = 'flex';
            } else {
                tabStudent.classList.add('active');
                tabAdmin.classList.remove('active');

                usernameLabel.innerText = 'Enrollment Number';
                usernameInput.placeholder = 'e.g. 250114305001';
                usernameIcon.className = 'fa-solid fa-id-card auth-input-icon';
                pwdHint.innerText = '(Password = Enrollment No)';

                studentChips.style.display = 'flex';
                adminChips.style.display = 'none';
            }

            usernameInput.focus();
        }

        function fillCredentials(user, pwd) {
            document.getElementById('usernameInput').value = user;
            document.getElementById('passwordInput').value = pwd;
            document.getElementById('usernameInput').focus();
        }

        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('passwordInput');
            const eyeIcon  = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.className = 'fa-solid fa-eye-slash';
            } else {
                pwdInput.type = 'password';
                eyeIcon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>
</html>
