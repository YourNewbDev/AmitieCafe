<?php

session_start();

include "config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $qry = $pdo->prepare("SELECT * FROM tbluser WHERE user_name = :username");
    $qry->execute(['username' => $username]);
    $user = $qry->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];

            header("Location: index.php");

            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amitie | Login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="icon" href="assets/image/Amitie.png" type="image/x-icon">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card rounded" style="background-color: #EFE7E3; color: #273852; max-width: 400px; width: 100%;">

            <!-- Image section -->
            <div class="card-header text-center" style="background-color: #EFE7E3; padding: 10px; border-bottom: none;">
                <img src="assets/image/Amitie.png" class="img-fluid" style="max-width: 60%; border-radius: 10px;" alt="">
            </div>

            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="d-flex justify-content-center">
                    <form method="POST" style="width: 80%; max-width: 320px;">
                        <div class="mb-3 text-start">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3 text-left">
                            <a href="#" class="text-decoration-none">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-5">Login</button>
                    </form>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>