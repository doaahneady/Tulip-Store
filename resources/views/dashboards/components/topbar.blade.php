{{-- Dashboard Topbar Component --}}

@php
    $user = auth()->user();
    $unreadCount = $unreadCount ?? 0;
    $notifications = $notifications ?? collect();
    $pageTitle = $title ?? 'Dashboard';
@endphp

<header class="dashboard-topbar">
    <div style="display: flex; align-items: center; gap: var(--space-4);">
        <!-- Mobile Menu Toggle -->
        <button type="button" 
                style="display: none; padding: var(--space-2); border: none; background: none; cursor: pointer; color: var(--gray-600); font-size: 1.25rem; border-radius: var(--radius-md); transition: background-color 0.2s ease;"
                onclick="toggleSidebar()" 
                id="mobile-menu-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Page Title -->
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 600; color: var(--gray-900); margin: 0;">{{ $pageTitle }}</h1>
            @if(isset($subtitle))
                <p style="color: var(--gray-600); margin: 0; font-size: 0.875rem;">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: var(--space-4);">
        <!-- Search -->
        <div style="position: relative; display: none;" id="global-search-container">
            <input type="text" 
                   style="width: 300px; padding: var(--space-2) var(--space-3) var(--space-2) 2.5rem; border: 1px solid var(--gray-300); border-radius: var(--radius-lg); background: white; font-size: 0.875rem; color: var(--gray-900); transition: all 0.2s ease;"
                   placeholder="Search..." 
                   id="global-search">
            <i class="fas fa-search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--gray-400);"></i>
        </div>

        <!-- Real-time Status Indicator -->
        <div style="display: flex; align-items: center; gap: var(--space-2); padding: var(--space-2) var(--space-3); background: var(--success-50); border: 1px solid var(--success-200); border-radius: var(--radius-lg);">
            <div style="width: 8px; height: 8px; background: var(--success-500); border-radius: 50%; animation: pulse 2s infinite;"></div>
            <span style="font-size: 0.75rem; color: var(--success-700); font-weight: 500;">Live</span>
        </div>

        <!-- Notifications -->
        <div style="position: relative;">
            <button type="button" 
                    style="padding: var(--space-2); border: none; background: none; cursor: pointer; color: var(--gray-600); font-size: 1.25rem; border-radius: var(--radius-md); transition: all 0.2s ease; position: relative;"
                    onclick="toggleNotifications()"
                    id="notifications-toggle">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span style="position: absolute; top: 0; right: 0; width: 18px; height: 18px; background: var(--error-500); color: white; border-radius: 50%; font-size: 0.625rem; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Notifications Dropdown -->
            <div id="notifications-dropdown" 
                 style="display: none; position: absolute; top: 100%; right: 0; width: 380px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200); margin-top: var(--space-2); z-index: 50;">
                <div style="padding: var(--space-4); border-bottom: 1px solid var(--gray-200);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: var(--gray-900);">Notifications</span>
                        @if($unreadCount > 0)
                            <button onclick="markAllAsRead()" style="font-size: 0.75rem; color: var(--primary-600); background: none; border: none; cursor: pointer;">
                                Mark all read
                            </button>
                        @endif
                    </div>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($notifications->take(5) as $notification)
                        <div style="padding: var(--space-4); border-bottom: 1px solid var(--gray-100); cursor: pointer; transition: background-color 0.2s ease;" 
                             onclick="markAsRead({{ $notification->id }})"
                             onmouseover="this.style.backgroundColor='var(--gray-25)'"
                             onmouseout="this.style.backgroundColor='white'">
                            <div style="display: flex; gap: var(--space-3);">
                                <div style="width: 8px; height: 8px; background: {{ $notification->is_read ? 'var(--gray-300)' : 'var(--primary-500)' }}; border-radius: 50%; margin-top: 0.5rem; flex-shrink: 0;"></div>
                                <div style="flex: 1;">
                                    <div style="font-weight: {{ $notification->is_read ? '400' : '500' }}; color: var(--gray-900); font-size: 0.875rem;">
                                        {{ $notification->title ?? $notification->message }}
                                    </div>
                                    @if($notification->title && $notification->message)
                                        <div style="color: var(--gray-600); font-size: 0.75rem; margin-top: var(--space-1);">
                                            {{ Str::limit($notification->message, 80) }}
                                        </div>
                                    @endif
                                    <div style="color: var(--gray-500); font-size: 0.75rem; margin-top: var(--space-1);">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding: var(--space-8); text-align: center;">
                            <i class="fas fa-bell-slash" style="font-size: 2rem; color: var(--gray-400); margin-bottom: var(--space-2);"></i>
                            <p style="color: var(--gray-500); font-size: 0.875rem; margin: 0;">No notifications</p>
                        </div>
                    @endforelse
                </div>
                @if($notifications->count() > 5)
                    <div style="padding: var(--space-3); border-top: 1px solid var(--gray-200); text-align: center;">
                        <a href="{{ route('notifications') }}" style="font-size: 0.875rem; color: var(--primary-600); text-decoration: none;">
                            View all notifications
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Dropdown -->
        <div style="position: relative;">
            <button type="button" 
                    style="padding: var(--space-2); border: none; background: none; cursor: pointer; color: var(--gray-600); font-size: 1.25rem; border-radius: var(--radius-md); transition: all 0.2s ease;"
                    onclick="toggleQuickActions()"
                    id="quick-actions-toggle">
                <i class="fas fa-plus"></i>
            </button>

            <!-- Quick Actions Dropdown -->
            <div id="quick-actions-dropdown" 
                 style="display: none; position: absolute; top: 100%; right: 0; width: 250px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200); margin-top: var(--space-2); z-index: 50;">
                <div style="padding: var(--space-3);">
                    <div style="font-weight: 600; color: var(--gray-900); margin-bottom: var(--space-3); font-size: 0.875rem;">Quick Actions</div>
                    
                    @if($user->is_trader ?? false)
                        <a href="{{ route('dashboard.vendor.products') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-plus text-primary-600"></i>
                            <span>Add Product</span>
                        </a>
                    @endif
                    
                    @if($user->is_hr ?? false)
                        <a href="{{ route('dashboard.hr.employees') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-user-plus text-green-600"></i>
                            <span>Add Employee</span>
                        </a>
                    @endif
                    
                    @if($user->is_driver_supervisor ?? false)
                        <a href="{{ route('dashboard.supervisor.order-assignment') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-route text-orange-600"></i>
                            <span>Create Assignment</span>
                        </a>
                    @endif
                    
                    @if($user->is_admin ?? false)
                        <a href="{{ route('dashboard.admin.users') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-user-shield text-purple-600"></i>
                            <span>Add User</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Menu -->
        <div style="position: relative;">
            <button type="button" 
                    style="display: flex; align-items: center; gap: var(--space-2); padding: var(--space-2); border: none; background: none; cursor: pointer; border-radius: var(--radius-md); transition: background-color 0.2s ease;"
                    onclick="toggleUserMenu()"
                    id="user-menu-toggle">
                <div style="width: 32px; height: 32px; border-radius: var(--radius-xl); background: var(--primary-100); display: flex; align-items: center; justify-content: center; color: var(--primary-600); font-weight: 600; font-size: 0.875rem;">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size: 0.875rem; font-weight: 500; color: var(--gray-900); display: none;" id="user-name-desktop">{{ $user->name ?? 'User' }}</span>
                <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: var(--gray-500);"></i>
            </button>

            <!-- User Dropdown -->
            <div id="user-dropdown" 
                 style="display: none; position: absolute; top: 100%; right: 0; width: 220px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200); margin-top: var(--space-2); z-index: 50;">
                <div style="padding: var(--space-4); border-bottom: 1px solid var(--gray-200);">
                    <div style="font-weight: 500; color: var(--gray-900); font-size: 0.875rem;">{{ $user->name ?? 'User' }}</div>
                    <div style="color: var(--gray-500); font-size: 0.75rem;">{{ $user->email ?? '' }}</div>
                    <div style="margin-top: var(--space-2);">
                        @php
                            $roles = [];
                            if ($user->is_admin ?? false) $roles[] = 'Admin';
                            if ($user->is_trader ?? false) $roles[] = 'Store Owner';
                            if ($user->is_hr ?? false) $roles[] = 'HR';
                            if ($user->is_finance ?? false) $roles[] = 'Finance';
                            if ($user->is_driver_supervisor ?? false) $roles[] = 'Supervisor';
                            if ($user->is_it ?? false) $roles[] = 'IT';
                        @endphp
                        @foreach($roles as $role)
                            <span class="badge badge-info" style="margin-right: var(--space-1); margin-bottom: var(--space-1);">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
                <div style="padding: var(--space-2);">
                    <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-user" style="width: 16px;"></i>
                        <span>Profile Settings</span>
                    </a>
                    <a href="{{ route('notifications') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-bell" style="width: 16px;"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--gray-700); text-decoration: none; font-size: 0.875rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--gray-50)'" onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-home" style="width: 16px;"></i>
                        <span>Back to Store</span>
                    </a>
                </div>
                <div style="padding: var(--space-2); border-top: 1px solid var(--gray-200);">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2); border-radius: var(--radius-md); color: var(--error-600); background: none; border: none; cursor: pointer; font-size: 0.875rem; transition: background-color 0.2s ease; width: 100%; text-align: left;" onmouseover="this.style.backgroundColor='var(--error-50)'" onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

#mobile-menu-toggle {
    display: none !important;
}

#user-name-desktop {
    display: inline !important;
}

#global-search-container {
    display: block !important;
}

@media (max-width: 1024px) {
    #mobile-menu-toggle {
        display: flex !important;
    }
    
    #user-name-desktop {
        display: none !important;
    }
    
    #global-search-container {
        display: none !important;
    }
}

#mobile-menu-toggle:hover {
    background-color: var(--gray-100);
}

#notifications-toggle:hover,
#quick-actions-toggle:hover,
#user-menu-toggle:hover {
    background-color: var(--gray-100);
}

#global-search:focus {
    outline: none;
    border-color: var(--primary-300);
    box-shadow: 0 0 0 4px var(--primary-100);
}
</style>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notifications-dropdown');
    const userDropdown = document.getElementById('user-dropdown');
    const quickActionsDropdown = document.getElementById('quick-actions-dropdown');
    
    // Close other dropdowns
    userDropdown.style.display = 'none';
    quickActionsDropdown.style.display = 'none';
    
    // Toggle notifications
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleQuickActions() {
    const dropdown = document.getElementById('quick-actions-dropdown');
    const userDropdown = document.getElementById('user-dropdown');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    // Close other dropdowns
    userDropdown.style.display = 'none';
    notificationsDropdown.style.display = 'none';
    
    // Toggle quick actions
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleUserMenu() {
    const dropdown = document.getElementById('user-dropdown');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const quickActionsDropdown = document.getElementById('quick-actions-dropdown');
    
    // Close other dropdowns
    notificationsDropdown.style.display = 'none';
    quickActionsDropdown.style.display = 'none';
    
    // Toggle user menu
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function markAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(() => {
        // Reload notifications or update UI
        location.reload();
    });
}

function markAllAsRead() {
    fetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(() => {
        location.reload();
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const userDropdown = document.getElementById('user-dropdown');
    const quickActionsDropdown = document.getElementById('quick-actions-dropdown');
    
    if (!e.target.closest('#notifications-toggle') && !e.target.closest('#notifications-dropdown')) {
        notificationsDropdown.style.display = 'none';
    }
    if (!e.target.closest('#user-menu-toggle') && !e.target.closest('#user-dropdown')) {
        userDropdown.style.display = 'none';
    }
    if (!e.target.closest('#quick-actions-toggle') && !e.target.closest('#quick-actions-dropdown')) {
        quickActionsDropdown.style.display = 'none';
    }
});

// Global search functionality
document.getElementById('global-search')?.addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length > 2) {
        // Implement global search
        console.log('Searching for:', query);
    }
});
</script>