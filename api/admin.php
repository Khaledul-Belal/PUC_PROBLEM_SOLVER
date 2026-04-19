<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — PUC HUB</title>
    <link rel="icon" type="image/png" href="assets/2.png">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --gold:#f39c12; --bg:#080808; --card:#0f0f0f; --border:#1e1e1e; --text:#ececec; --muted:#666; --red:#e74c3c; --green:#27ae60; }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;}

        /* TOPBAR */
        .topbar{display:flex;align-items:center;justify-content:space-between;padding:14px 28px;background:#0a0a0a;border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;}
        .topbar-left{display:flex;align-items:center;gap:12px;}
        .topbar h1{font-size:18px;font-weight:700;color:var(--gold);}
        .topbar-sub{font-size:11px;color:var(--muted);}
        .btn-sm{padding:7px 16px;border:1px solid var(--border);border-radius:8px;background:transparent;color:var(--muted);font-size:13px;font-family:'Space Grotesk',sans-serif;cursor:pointer;text-decoration:none;transition:.2s;}
        .btn-sm:hover{border-color:var(--gold);color:var(--gold);}

        /* TABS */
        .tabs{display:flex;background:#0a0a0a;border-bottom:1px solid var(--border);padding:0 28px;gap:4px;}
        .tab-btn{padding:12px 20px;background:none;border:none;border-bottom:3px solid transparent;color:var(--muted);font-size:14px;font-family:'Space Grotesk',sans-serif;cursor:pointer;transition:.2s;}
        .tab-btn.active{color:var(--gold);border-bottom-color:var(--gold);}
        .tab-btn:hover{color:var(--gold);}

        /* CONTENT */
        .content{padding:28px;max-width:1200px;margin:0 auto;}
        .panel{display:none;} .panel.active{display:block;}

        /* STATS */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px;}
        .stat-card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center;transition:.2s;}
        .stat-card:hover{border-color:rgba(243,156,18,0.3);}
        .stat-num{font-size:32px;font-weight:700;color:var(--gold);}
        .stat-label{font-size:12px;color:var(--muted);margin-top:4px;}

        /* TABLE */
        .table-wrap{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
        .table-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);}
        .table-title{font-size:15px;font-weight:600;}
        .search-input{padding:8px 14px;background:#111;border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:13px;font-family:'Space Grotesk',sans-serif;outline:none;width:220px;transition:.2s;}
        .search-input:focus{border-color:var(--gold);}
        table{width:100%;border-collapse:collapse;}
        th{padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.6px;border-bottom:1px solid var(--border);}
        td{padding:12px 16px;font-size:13px;border-bottom:1px solid rgba(255,255,255,0.03);}
        tr:last-child td{border-bottom:none;}
        tr:hover td{background:rgba(255,255,255,0.02);}
        .badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
        .badge.admin{background:rgba(243,156,18,0.15);color:var(--gold);}
        .badge.blocked{background:rgba(231,76,60,0.15);color:var(--red);}
        .badge.active{background:rgba(39,174,96,0.15);color:var(--green);}
        .badge.success{background:rgba(39,174,96,0.12);color:var(--green);}
        .badge.failed{background:rgba(231,76,60,0.12);color:var(--red);}
        .action-btns{display:flex;gap:6px;}
        .btn-action{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none;font-family:'Space Grotesk',sans-serif;transition:.2s;}
        .btn-block{background:rgba(231,76,60,0.15);color:var(--red);}
        .btn-block:hover{background:rgba(231,76,60,0.25);}
        .btn-unblock{background:rgba(39,174,96,0.15);color:var(--green);}
        .btn-unblock:hover{background:rgba(39,174,96,0.25);}
        .btn-delete{background:rgba(231,76,60,0.08);color:#a93226;}
        .btn-delete:hover{background:rgba(231,76,60,0.2);}
        .empty-row td{text-align:center;color:var(--muted);padding:40px;}

        /* Login required overlay */
        #adminAuth{position:fixed;inset:0;background:var(--bg);z-index:999;display:flex;align-items:center;justify-content:center;}
        .auth-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:40px;text-align:center;max-width:380px;width:100%;margin:20px;}
        .auth-card h2{color:var(--gold);font-size:22px;margin-bottom:8px;}
        .auth-card p{color:var(--muted);font-size:14px;margin-bottom:24px;}
        .auth-input{width:100%;padding:13px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:10px;color:var(--text);font-size:15px;font-family:'Space Grotesk',sans-serif;text-align:center;letter-spacing:2px;outline:none;margin-bottom:16px;}
        .auth-input:focus{border-color:var(--gold);}
        .auth-btn{width:100%;padding:13px;background:var(--gold);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;font-family:'Space Grotesk',sans-serif;cursor:pointer;}
        .auth-btn:hover{background:#e67e22;}

        @media(max-width:768px){
            .content{padding:16px;}
            .table-head{flex-direction:column;gap:10px;align-items:flex-start;}
            .search-input{width:100%;}
            td,th{padding:10px 10px;font-size:12px;}
        }
    </style>
</head>
<body>

<!-- Admin Auth Gate -->
<div id="adminAuth">
    <div class="auth-card">
        <h2>🔐 Admin Access</h2>
        <p>Enter the admin password to continue</p>
        <input type="password" class="auth-input" id="adminPwInput" placeholder="••••••••" onkeydown="if(event.key==='Enter')checkAdminPw()">
        <div id="authError" style="color:#e74c3c;font-size:13px;margin-bottom:12px;display:none;">Wrong password</div>
        <button class="auth-btn" onclick="checkAdminPw()">Enter Admin Panel</button>
    </div>
</div>

<!-- Main Panel -->
<div id="mainPanel" style="display:none;">
    <div class="topbar">
        <div class="topbar-left">
            <img src="assets/2.png" style="width:36px;height:36px;border-radius:50%;border:2px solid var(--gold);object-fit:cover;" onerror="this.style.display='none'">
            <div>
                <div class="topbar h1" style="font-size:18px;font-weight:700;color:var(--gold);">PUC HUB Admin</div>
                <div class="topbar-sub">Premier University Chittagong</div>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="btn-sm" onclick="loadAll()">🔄 Refresh</button>
            <a href="index.html" class="btn-sm">← Back to Site</a>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('dashboard')">📊 Dashboard</button>
        <button class="tab-btn" onclick="switchTab('users')">👥 Users</button>
        <button class="tab-btn" onclick="switchTab('logins')">🔑 Login Logs</button>
        <button class="tab-btn" onclick="switchTab('activity')">📋 Activity</button>
    </div>

    <div class="content">

        <!-- DASHBOARD -->
        <div class="panel active" id="panel-dashboard">
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card"><div class="stat-num" id="s-total">—</div><div class="stat-label">Total Users</div></div>
                <div class="stat-card"><div class="stat-num" id="s-today">—</div><div class="stat-label">Today's Logins</div></div>
                <div class="stat-card"><div class="stat-num" id="s-total-login">—</div><div class="stat-label">Total Logins</div></div>
                <div class="stat-card"><div class="stat-num" id="s-failed" style="color:var(--red)">—</div><div class="stat-label">Failed Attempts</div></div>
                <div class="stat-card"><div class="stat-num" id="s-blocked" style="color:var(--red)">—</div><div class="stat-label">Blocked Users</div></div>
            </div>
            <div class="table-wrap">
                <div class="table-head"><span class="table-title">🏆 Top Visited Pages</span></div>
                <table>
                    <thead><tr><th>Page</th><th>Visits</th></tr></thead>
                    <tbody id="topPagesBody"><tr class="empty-row"><td colspan="2">Loading...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- USERS -->
        <div class="panel" id="panel-users">
            <div class="table-wrap">
                <div class="table-head">
                    <span class="table-title">👥 All Users (<span id="userCount">0</span>)</span>
                    <input type="text" class="search-input" id="userSearch" placeholder="🔍 Search by name or ID..." oninput="filterUsers()">
                </div>
                <table>
                    <thead><tr><th>#</th><th>Name</th><th>Student ID</th><th>Dept.</th><th>Semester</th><th>Blood</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
                    <tbody id="usersBody"><tr class="empty-row"><td colspan="9">Loading...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- LOGIN LOGS -->
        <div class="panel" id="panel-logins">
            <div class="table-wrap">
                <div class="table-head"><span class="table-title">🔑 Login History</span></div>
                <table>
                    <thead><tr><th>#</th><th>Student ID</th><th>Name</th><th>Status</th><th>IP Address</th><th>Time</th></tr></thead>
                    <tbody id="logsBody"><tr class="empty-row"><td colspan="6">Loading...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- ACTIVITY -->
        <div class="panel" id="panel-activity">
            <div class="table-wrap">
                <div class="table-head"><span class="table-title">📋 Page Activity</span></div>
                <table>
                    <thead><tr><th>#</th><th>Student ID</th><th>Name</th><th>Page Visited</th><th>Time</th></tr></thead>
                    <tbody id="activityBody"><tr class="empty-row"><td colspan="5">Loading...</td></tr></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    // ============================================================
    // CONFIG
    const API_BASE   = 'http://localhost/puc_hub/api';
    const ADMIN_KEY  = 'puchub_admin_2025';
    const ADMIN_PW   = 'admin123'; // Change this!
    // ============================================================

    let allUsers = [];

    function checkAdminPw() {
        var pw = document.getElementById('adminPwInput').value;
        if (pw === ADMIN_PW) {
            document.getElementById('adminAuth').style.display = 'none';
            document.getElementById('mainPanel').style.display = 'block';
            loadAll();
        } else {
            document.getElementById('authError').style.display = 'block';
        }
    }

    document.getElementById('adminPwInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') checkAdminPw();
    });

    function switchTab(name) {
        document.querySelectorAll('.panel').forEach(function(p){ p.classList.remove('active'); });
        document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
        document.getElementById('panel-' + name).classList.add('active');
        event.target.classList.add('active');
    }

    async function apiCall(action, extra) {
        var params = Object.assign({ action: action, admin_key: ADMIN_KEY }, extra || {});
        try {
            var res = await fetch(API_BASE + '/admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(params)
            });
            return await res.json();
        } catch(e) {
            return { success: false, _offline: true };
        }
    }

    async function loadAll() {
        loadStats();
        loadUsers();
        loadLogs();
        loadActivity();
    }

    async function loadStats() {
        var d = await apiCall('stats');
        if (!d.success) {
            // offline fallback: read from localStorage
            renderStatsOffline(); return;
        }
        document.getElementById('s-total').textContent       = d.total_users;
        document.getElementById('s-today').textContent       = d.today_logins;
        document.getElementById('s-total-login').textContent = d.total_logins;
        document.getElementById('s-failed').textContent      = d.failed_logins;
        document.getElementById('s-blocked').textContent     = d.blocked;
        var tbody = document.getElementById('topPagesBody');
        tbody.innerHTML = d.top_pages.map(function(p, i) {
            return '<tr><td>' + (i+1) + '. ' + escHtml(p.page_visited) + '</td><td><strong>' + p.visits + '</strong></td></tr>';
        }).join('') || '<tr class="empty-row"><td colspan="2">No data yet</td></tr>';
    }

    async function loadUsers() {
        var d = await apiCall('users');
        if (!d.success) { renderUsersOffline(); return; }
        allUsers = d.users;
        renderUsers(allUsers);
    }

    function renderUsers(users) {
        document.getElementById('userCount').textContent = users.length;
        var tbody = document.getElementById('usersBody');
        if (!users.length) {
            tbody.innerHTML = '<tr class="empty-row"><td colspan="9">No users found</td></tr>'; return;
        }
        tbody.innerHTML = users.map(function(u, i) {
            var status = u.is_admin ? '<span class="badge admin">Admin</span>'
                       : u.is_blocked ? '<span class="badge blocked">Blocked</span>'
                       : '<span class="badge active">Active</span>';
            var actions = u.is_admin ? '<span style="color:#333;font-size:12px;">Protected</span>'
                : '<div class="action-btns">'
                + (u.is_blocked
                    ? '<button class="btn-action btn-unblock" onclick="blockUser(\'' + u.student_id + '\',0)">✓ Unblock</button>'
                    : '<button class="btn-action btn-block" onclick="blockUser(\'' + u.student_id + '\',1)">🚫 Block</button>')
                + '<button class="btn-action btn-delete" onclick="deleteUser(\'' + u.student_id + '\',\'' + escHtml(u.username) + '\')">🗑</button>'
                + '</div>';
            return '<tr><td>' + (i+1) + '</td><td>' + escHtml(u.username) + '</td><td><code>' + u.student_id + '</code></td><td>' + (u.department||'—') + '</td><td>' + (u.semester||'—') + '</td><td>' + (u.blood_group||'—') + '</td><td>' + status + '</td><td style="font-size:12px;color:var(--muted)">' + (u.last_login ? u.last_login.replace('T',' ').substr(0,16) : 'Never') + '</td><td>' + actions + '</td></tr>';
        }).join('');
    }

    function filterUsers() {
        var q = document.getElementById('userSearch').value.toLowerCase();
        var filtered = allUsers.filter(function(u) {
            return u.username.toLowerCase().includes(q) || u.student_id.includes(q);
        });
        renderUsers(filtered);
    }

    async function blockUser(sid, blocked) {
        if (!confirm((blocked ? 'Block' : 'Unblock') + ' this user?')) return;
        var d = await apiCall('block', { student_id: sid, blocked: blocked });
        if (d.success) { loadUsers(); loadStats(); }
        else alert('Failed: ' + d.message);
    }

    async function deleteUser(sid, name) {
        if (!confirm('Delete user "' + name + '" (ID: ' + sid + ')? This cannot be undone.')) return;
        var d = await apiCall('delete', { student_id: sid });
        if (d.success) { loadUsers(); loadStats(); }
        else alert('Failed: ' + d.message);
    }

    async function loadLogs() {
        var d = await apiCall('logs');
        if (!d.success) { document.getElementById('logsBody').innerHTML = '<tr class="empty-row"><td colspan="6">Cannot connect to server</td></tr>'; return; }
        var tbody = document.getElementById('logsBody');
        if (!d.logs.length) { tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No logs yet</td></tr>'; return; }
        tbody.innerHTML = d.logs.map(function(l, i) {
            var badge = l.status === 'success' ? '<span class="badge success">Success</span>' : '<span class="badge failed">Failed</span>';
            return '<tr><td>' + (i+1) + '</td><td><code>' + l.student_id + '</code></td><td>' + escHtml(l.username||'—') + '</td><td>' + badge + '</td><td style="font-size:12px;color:var(--muted)">' + (l.ip_address||'—') + '</td><td style="font-size:12px;color:var(--muted)">' + l.login_time.substr(0,16) + '</td></tr>';
        }).join('');
    }

    async function loadActivity() {
        var d = await apiCall('activity');
        if (!d.success) { document.getElementById('activityBody').innerHTML = '<tr class="empty-row"><td colspan="5">Cannot connect to server</td></tr>'; return; }
        var tbody = document.getElementById('activityBody');
        if (!d.activity.length) { tbody.innerHTML = '<tr class="empty-row"><td colspan="5">No activity yet</td></tr>'; return; }
        tbody.innerHTML = d.activity.map(function(a, i) {
            return '<tr><td>' + (i+1) + '</td><td><code>' + a.student_id + '</code></td><td>' + escHtml(a.username||'—') + '</td><td>' + escHtml(a.page_visited) + '</td><td style="font-size:12px;color:var(--muted)">' + a.visited_at.substr(0,16) + '</td></tr>';
        }).join('');
    }

    // OFFLINE fallback: read from localStorage
    function renderStatsOffline() {
        document.getElementById('s-total').textContent = 'Offline';
        document.getElementById('s-today').textContent = '—';
        document.getElementById('topPagesBody').innerHTML = '<tr class="empty-row"><td colspan="2">Start XAMPP to see live data</td></tr>';
    }

    function renderUsersOffline() {
        // Try to read from any stored userData
        var rows = '';
        try {
            var ud = JSON.parse(localStorage.getItem('userData'));
            if (ud) {
                rows = '<tr><td>1</td><td>' + escHtml(ud.username||'?') + '</td><td><code>' + (ud.studentId||'?') + '</code></td><td>' + (ud.department||'—') + '</td><td>' + (ud.semester||'—') + '</td><td>' + (ud.bloodGroup||'—') + '</td><td><span class="badge active">Local</span></td><td style="font-size:12px;color:var(--muted)">' + (ud.loginTime||'').substr(0,16) + '</td><td style="color:#333;font-size:12px;">XAMPP needed</td></tr>';
            }
        } catch(e) {}
        document.getElementById('usersBody').innerHTML = rows || '<tr class="empty-row"><td colspan="9">⚠️ XAMPP not running — showing offline data</td></tr>';
    }

    function escHtml(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
</script>
</body>
</html>