<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap and custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Admin Dashboard Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hostel Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Dashboard Main Content -->
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4>Admin Menu</h4>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#manage-rooms" class="list-group-item list-group-item-action">Manage Rooms</a>
                        <a href="#assign-rooms" class="list-group-item list-group-item-action">Assign Rooms</a>
                        <a href="#track-payments" class="list-group-item list-group-item-action">Track Payments</a>
                        <a href="#reports" class="list-group-item list-group-item-action">View Reports</a>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Section -->
            <div class="col-md-9">
                <!-- Manage Rooms Section -->
                <div id="manage-rooms" class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>Manage Rooms</h4>
                    </div>
                    <div class="card-body">
                        <form action="add_room.php" method="post">
                            <div class="mb-3">
                                <label for="room_number" class="form-label">Room Number</label>
                                <input type="text" class="form-control" id="room_number" name="room_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Room</button>
                        </form>
                    </div>
                </div>

                <!-- Assign Rooms Section -->
                <div id="assign-rooms" class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>Assign Rooms</h4>
                    </div>
                    <div class="card-body">
                        <form action="assign_room.php" method="post">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="number" class="form-control" id="student_id" name="student_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="room_id" class="form-label">Room ID</label>
                                <input type="number" class="form-control" id="room_id" name="room_id" required>
                            </div>
                            <button type="submit" class="btn btn-success">Assign Room</button>
                        </form>
                    </div>
                </div>

                <!-- Track Payments Section -->
                <div id="track-payments" class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>Track Payments</h4>
                    </div>
                    <div class="card-body">
                        <form action="add_payment.php" method="post">
                            <div class="mb-3">
                                <label for="student_id_payment" class="form-label">Student ID</label>
                                <input type="number" class="form-control" id="student_id_payment" name="student_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                            </div>
                            <button type="submit" class="btn btn-success">Record Payment</button>
                        </form>
                    </div>
                </div>

                <!-- View Reports Section -->
                <div id="reports" class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>View Reports</h4>
                    </div>
                    <div class="card-body">
                        <a href="view_rooms.php" class="btn btn-info">View Room Occupancy</a>
                        <a href="view_payments.php" class="btn btn-info">View Payment Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

