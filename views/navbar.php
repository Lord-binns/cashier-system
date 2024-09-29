<nav class="navbar navbar-expand-lg navbar-light bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">AB Meat Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <div class="d-flex align-items-center">
    <button class="btn btn-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogout" aria-controls="offcanvasLogout">
        <i class="bi bi-list"></i>
    </button>
  
</div>

                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offcanvas Logout Menu -->
<div class="offcanvas offcanvas-end d-flex flex-column bg-dark text-white" tabindex="-1" id="offcanvasLogout" aria-labelledby="offcanvasLogoutLabel" style="height: 100%;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLogoutLabel">Menu Options</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <p>Admin Nav Bar</p>
        <div class="d-grid gap-2">
            <a href="dashboard.php" class="btn btn-secondary border border-white">
                <i class="bi bi-grid"></i> Back to Dashboard
            </a>
            <a href="adminList.php" class="btn btn-success border border-white">
                <i class="bi bi-person"></i> Edit Users
            </a>
            <a href="manage-inventory.php" class="btn btn-success border border-white">
                <i class="bi bi-gear-fill"></i> Manage Inventory
            </a>
            <a href="sales-report.php" class="btn btn-success border border-white">
                <i class="bi bi-file-earmark-bar-graph"></i> View Sales Report
            </a>

        </div>
    </div>
    <div class="offcanvas-footer">
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-danger border border-white" data-bs-toggle="modal" data-bs-target="#logoutConfirmationModal">Logout</button>
        </div>
    </div>
</div>


<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutConfirmationModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="../actions/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>
