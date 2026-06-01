<!DOCTYPE html>
<html >

<head>
    <title>Admin Panel</title>
    <style>

        
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #233142;
            background: #edf2f7;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .admin-page {
            min-height: 100vh;
            display: flex;
            width: 100%;
            /* padding: 20px; */
        }

        .admin-shell {
            width: 100%;
            min-height: 100vh;
            /* max-width: 1320px; */
            /* margin: 0 auto; */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid #cfdbe6;
            /* border-radius: 14px; */
            box-shadow: 0 18px 45px rgba(35, 49, 66, 0.12);

        }

        .admin-body {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        .sidebar {
            width: 230px;
            flex-shrink: 0;
            padding: 20px 14px;
            color: #eaf2f8;
            background: linear-gradient(180deg, #21394d, #16293a);
            border-right: 1px solid #cfdbe6;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            position: fixed;
            top: 66px;
            left: 0;
            height: calc(100vh - 66px);
            overflow-y: auto;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 8px 20px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .brand-mark {
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            border-radius: 7px;
            color: #173046;
            background: #ffffff;
            border: 1px solid #dbe4ec;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .nav-list {
            display: grid;
            gap: 7px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #d8e5ee;
            font-size: 14px;
            transition: 0.2s ease;
            border: 1px solid transparent;

        }

        .nav-link:hover,
        .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: inset 3px 0 0 #9fc0d5;
        }

        .nav-icon {
            display: grid;
            width: 22px;
            height: 22px;
            place-items: center;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
        }

        .main-panel {
            flex: 1;
            min-width: 0;
            background: #f4f7fb;
            margin-left: 230px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 66px;
            padding: 0 26px;
            background: linear-gradient(180deg, #ffffff, #f9fbfd);
            border-bottom: 2px solid #cfdbe6;
            border-radius: 30px 30px;

            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.06);
            z-index: 10;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .topbar .brand {
            padding: 0;
        }

        .topbar h2 {
            margin: 0;
            font-size: 16px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
            font-size: 14px;
        }

        .user-avatar {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 50%;
            color: #ffffff;
            font-weight: 700;
            background: #2f526d;
        }

        .content {
            /* padding: 28px; */
            padding: 72px 28px 28px 28px;
            width: 100%;
        }

        .content>h3 {
            margin: 0 0 16px;
            color: #172536;
            font-size: 24px;
        }

        .content>hr {
            margin: 18px 0;
            border: 0;
            border-top: 1px solid #dfe7ef;
        }

        .content>a[href*="/create"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            background: #20384d;
            border: 1px solid #20384d;
            box-shadow: 0 8px 18px rgba(32, 56, 77, 0.18);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .page-header h3 {
            margin: 0;
            font-size: 24px;
            color: #172536;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #d7e0ea;
            border-radius: 8px;
            color: #233142;
            font-size: 14px;
            font-weight: 700;
            background: #ffffff;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn:hover,
        button:hover,
        .content>a[href*="/create"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(32, 56, 77, 0.14);
        }

        .btn.primary {
            color: #ffffff;
            border-color: #20384d;
            background: #20384d;
        }

        .card {
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 8px;
            box-shadow: 0 10px 28px rgba(35, 49, 66, 0.06);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 8px;
            box-shadow: 0 10px 28px rgba(35, 49, 66, 0.06);
        }

        th,
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e6edf3;
            text-align: left;
            font-size: 14px;
        }

        th {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            background: #f8fafc;
            letter-spacing: 0.3px;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        tr:hover td {
            background: #fbfdff;
        }

        td>a {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 0 10px;
            border-radius: 7px;
            color: #20384d;
            font-size: 13px;
            font-weight: 700;
            background: #edf4f8;
            border: 1px solid #d7e6ef;
        }

        form {
            max-width: 520px;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 8px;
            box-shadow: 0 10px 28px rgba(35, 49, 66, 0.06);
        }

        td form {
            max-width: none;
            padding: 0;
            display: inline;
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        input,
        textarea,
        select {
            width: 100%;
            min-height: 40px;
            padding: 10px 12px;
            border: 1px solid #ccd8e3;
            border-radius: 8px;
            color: #233142;
            font: inherit;
            background: #fbfdff;
            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #7fa6bf;
            box-shadow: 0 0 0 3px rgba(127, 166, 191, 0.18);
            background: #ffffff;
        }

        button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            background: #20384d;
            border: 1px solid #20384d;
            cursor: pointer;
            transition: 0.2s ease;
        }

        td button {
            min-height: 32px;
            margin-left: 6px;
            color: #9f1d1d;
            font-size: 13px;
            background: #fff1f1;
            border-color: #f2cccc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 20px;
            color: #20384d;
            font-size: 12px;
            font-weight: 700;
            background: #edf4f8;
            border: 1px solid #d7e6ef;
        }

        .status-badge.success {
            color: #166534;
            background: #ecfdf3;
            border-color: #bbf7d0;
        }

        .status-badge.warning {
            color: #92400e;
            background: #fffbeb;
            border-color: #fde68a;
        }

        .status-badge.danger {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fecaca;
        }

        @media (max-width: 760px) {
            .admin-page {
                padding: 0;
            }

            .admin-shell {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
            }

            .sidebar {
                width: 78px;
                padding: 14px 10px;
            }

            .brand span,
            .nav-text {
                display: none;
            }

            .brand {
                justify-content: center;
                padding-bottom: 16px;
            }

            .nav-link {
                justify-content: center;
                padding: 11px 8px;
            }

            .topbar,
            .content {
                padding-left: 16px;
                padding-right: 16px;

            }

            .admin-user span {
                display: none;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

</head>

<body >

    <div class="admin-page">
        <div class="admin-shell">
            <header class="topbar">
                <div class="brand">
                    <div class="brand-mark">P</div>
                    <span>Prahari Admin</span>
                </div>

                <div class="admin-user">
                    <span>MAYANK </span>
                    <div class="user-avatar">M</div>
                </div>
            </header>

            <div class="admin-body">
                <aside class="sidebar">
                    <nav class="nav-list">
                        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                            href="/admin/dashboard">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="5"></rect>
                                    <rect x="14" y="12" width="7" height="9"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                            </span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/praharis*') ? 'active' : '' }}"
                            href="/admin/praharis">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M5 21a7 7 0 0 1 14 0"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Prahari</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/cases*') ? 'active' : '' }}" href="/admin/cases">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                                    <line x1="8" y1="8" x2="16" y2="8"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                    <line x1="8" y1="16" x2="13" y2="16"></line>
                                </svg>
                            </span>
                            <span class="nav-text">Cases</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/challans*') ? 'active' : '' }}"
                            href="/admin/challans">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 3h12v4a2 2 0 0 0 2 2v12H4V9a2 2 0 0 0 2-2V3z"></path>
                                    <line x1="8" y1="13" x2="16" y2="13"></line>
                                    <line x1="8" y1="17" x2="14" y2="17"></line>
                                </svg>
                            </span>
                            <span class="nav-text">Challans</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}"
                            href="/admin/payments">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                    <circle cx="12" cy="14" r="2"></circle>
                                </svg>
                            </span>
                            <span class="nav-text">Payments</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}" href="/admin/reports">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="4" y1="20" x2="20" y2="20"></line>
                                    <rect x="6" y="11" width="3" height="7"></rect>
                                    <rect x="11" y="8" width="3" height="10"></rect>
                                    <rect x="16" y="5" width="3" height="13"></rect>
                                </svg>
                            </span>
                            <span class="nav-text">Reports</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/subadmins') ? 'active' : '' }}"
                            href="/admin/subadmins">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="8" r="3"></circle>
                                    <circle cx="17" cy="10" r="2"></circle>
                                    <path d="M3 20a6 6 0 0 1 12 0"></path>
                                    <path d="M14 20a4 4 0 0 1 8 0"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Admins</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}"
                            href="/admin/settings">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.2a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.2a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h.1a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.2a1.7 1.7 0 0 0 1 1.5h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v.1a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.2a1.7 1.7 0 0 0-1.5 1z"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Settings</span>
                        </a>
                        <a class="nav-link" href="#" id="logout-btn">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </span>
                            <span class="nav-text">Logout</span>
                        </a>
                    </nav>
                </aside>

                <main class="main-panel">
                    <section class="content">
                        @yield('content')
                    </section>
                </main>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#20384d',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                color: '#233142',
                customClass: {
                    popup: 'swal-popup-custom'
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = '/admin/logout';
                }
            });
        });
    </script>

    
    

   

</body>

</html>
