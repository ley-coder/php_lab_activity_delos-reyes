
<?php
include 'dbcon.php';

if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email) VALUES (?, ?)"
        );

        $stmt->bind_param("ss", $name, $email);

        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit;
        }

        $stmt->close();
    }
}

$result = $conn->query(
    "SELECT * FROM users ORDER BY id DESC"
);


function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>User Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #ffffff;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 35px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .form-control {
            margin-bottom: 15px;
        }

        .table th {
            background: #f8f9fa;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>User Management System</h2>


    <?php if (isset($_GET['msg'])): ?>

        <?php if ($_GET['msg'] == 'added'): ?>

            <div class="alert alert-success alert-dismissible fade show">
                User added successfully!
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>

        <?php elseif ($_GET['msg'] == 'updated'): ?>

            <div class="alert alert-success alert-dismissible fade show">
                User updated successfully!
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>

        <?php elseif ($_GET['msg'] == 'deleted'): ?>

            <div class="alert alert-danger alert-dismissible fade show">
                User deleted successfully!
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>

        <?php endif; ?>

    <?php endif; ?>


    <div class="row g-4">


        <div class="col-md-4">

            <div class="card p-4">

                <h5 class="mb-3">Add New User</h5>

                <form method="POST" action="index.php">

                    <label class="form-label">Full Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Enter full name"
                        required>

                    <label class="form-label">Email Address</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter email address"
                        required>

                    <button
                        type="submit"
                        name="submit"
                        class="btn btn-primary w-100">

                        Submit

                    </button>

                </form>

            </div>

        </div>



        <div class="col-md-8">

            <div class="card p-4">

                <h5 class="mb-3">Registered Users</h5>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php if ($result->num_rows > 0): ?>

                            <?php $count = 1; ?>

                            <?php while ($row = $result->fetch_assoc()): ?>

                                <tr>

                                    <td><?= $count++ ?></td>

                                    <td><?= e($row['name']) ?></td>

                                    <td><?= e($row['email']) ?></td>

                                    <td>
                                        <?= date(
                                            "M d, Y",
                                            strtotime($row['created_at'])
                                        ) ?>
                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="edit.php?id=<?= (int)$row['id'] ?>"
                                                class="btn btn-success btn-sm">

                                                Edit

                                            </a>

                                            <a
                                                href="delete.php?id=<?= (int)$row['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this user?')">

                                                Delete

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center">
                                    No Data Found.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>