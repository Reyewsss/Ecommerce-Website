<div class="admin-header">
    <div class="header-left">
        <button class="btn btn-link mobile-menu-toggle d-md-none">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="header-right">
        <div class="user-dropdown dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-user-circle"></i>
                <?php echo $_SESSION['user_name']; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a class="dropdown-item" href="settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../user/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
