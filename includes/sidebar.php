<?php
// Function to determine if a menu item is active
function isActive($currentPage, $menuItem) {
    return $currentPage === $menuItem ? 'active' : '';
}

// Get the base path for links
$basePath = dirname(__DIR__) . '/';
?>

<!-- Sidebar -->
<div class="sidebar p-4">
    <div class="mb-8">
        <h1 class="font-bold text-xl text-gray-800">Seto Semero</h1>
        <p class="text-sm text-gray-500">Kebele Services</p>
    </div>
    
    <div class="space-y-2">
        <a href="<?php echo $basePath; ?>home.php" class="nav-item <?php echo isActive($currentPage, 'home'); ?>">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>
        <a href="<?php echo $basePath; ?>homeappointment.php" class="nav-item <?php echo isActive($currentPage, 'appointment'); ?>">
            <i class="far fa-calendar-alt mr-3"></i>
            Book Appointment
        </a>
        <div class="nav-item">
            <i class="far fa-bell mr-3"></i>
            Notifications
        </div>
        <div class="nav-item">
            <i class="far fa-comment mr-3"></i>
            Feedback
        </div>
        <a href="<?php echo $basePath; ?>logout.php" class="nav-item mt-auto">
            <i class="fas fa-sign-out-alt mr-3"></i>
            Logout
        </a>
    </div>
</div>

<style>
    .sidebar {
        background-color: #f5f7fb;
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        border-right: 1px solid #e5e7eb;
    }
    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #374151;
        transition: all 0.2s;
        text-decoration: none;
    }
    .nav-item:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    .nav-item.active {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-radius: 8px;
    }
</style> 