<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Zircon Inspections') - Petroleum Stock Monitoring</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.svg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0;
        }
        
        .service-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        
        .portal-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        .portal-icon {
            transition: transform 0.3s ease;
        }
        
        .portal-card:hover .portal-icon {
            transform: scale(1.1);
        }
        
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 1rem 0;
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Zircon Inspections Logo" class="navbar-logo" style="height:48px; margin-right:14px; vertical-align:middle; background:transparent;">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#portals">Portals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Client Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Staff Login</a>
                        </li>
                    @else
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notification-badge" style="display: none;">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <div id="notifications-list">
                                    <li class="text-center py-3">
                                        <i class="fas fa-bell-slash text-muted"></i>
                                        <p class="text-muted mb-0">No notifications</p>
                                    </li>
                                </div>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#" onclick="markAllAsRead()">Mark all as read</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->isClient())
                                    <li><a class="dropdown-item" href="{{ route('client.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.settings') }}">Settings</a></li>
                                @elseif(Auth::user()->isInspector())
                                    <li><a class="dropdown-item" href="{{ route('inspector.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('inspector.profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('inspector.settings') }}">Settings</a></li>
                                @elseif(Auth::user()->isOperations())
                                    <li><a class="dropdown-item" href="{{ route('operations.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('operations.profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('operations.settings') }}">Settings</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Zircon Inspections</h5>
                    <p>We don't just monitor stocks — we safeguard value.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Zircon Inspections. All rights reserved.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Logout fallback script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    // Add a small delay to ensure CSRF token is properly set
                    setTimeout(() => {
                        // If form submission fails, try alternative logout
                        if (!this.submitted) {
                            this.submitted = true;
                        }
                    }, 100);
                });
            }
        });
    </script>
    
    <!-- Real-time Notifications Script -->
    @auth
    <script>
        // Notification functionality
        let notificationCount = 0;
        let notifications = [];
        
        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            loadUnreadCount();
            
            // Poll for new notifications every 30 seconds
            setInterval(function() {
                loadUnreadCount();
            }, 30000);
        });
        
        function loadNotifications() {
            fetch('{{ route("notifications.index") }}')
                .then(response => response.json())
                .then(data => {
                    notifications = data.data || [];
                    updateNotificationList();
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        function loadUnreadCount() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    notificationCount = data.count || 0;
                    updateNotificationBadge();
                })
                .catch(error => console.error('Error loading notification count:', error));
        }
        
        function updateNotificationBadge() {
            const badge = document.getElementById('notification-badge');
            if (notificationCount > 0) {
                badge.textContent = notificationCount > 99 ? '99+' : notificationCount;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
        
        function updateNotificationList() {
            const list = document.getElementById('notifications-list');
            
            if (notifications.length === 0) {
                list.innerHTML = `
                    <li class="text-center py-3">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="text-muted mb-0">No notifications</p>
                    </li>
                `;
                return;
            }
            
            list.innerHTML = notifications.map(notification => `
                <li>
                    <a class="dropdown-item ${!notification.read ? 'fw-bold' : ''}" href="#" onclick="markAsRead(${notification.id})">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate">${notification.title}</strong>
                                    <small class="text-muted">${formatTime(notification.created_at)}</small>
                                </div>
                                <p class="mb-0 text-truncate">${notification.message}</p>
                            </div>
                        </div>
                    </a>
                </li>
            `).join('');
        }
        
        function markAsRead(notificationId) {
            fetch('{{ route("notifications.mark-read", ["id" => ":id"]) }}'.replace(':id', notificationId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadUnreadCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }
        
        function markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadUnreadCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }
        
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) return 'Just now';
            if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
            if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`;
            return date.toLocaleDateString();
        }
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html> 