<?php
session_start();
include "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];

            header("Location: dashboard.php");
            exit();

        } else {

            $error = "Incorrect password.";

        }

    } else {

        $error = "User not found.";

    }

}
?>

<?php include "templates/header.php"; ?>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h3 class="text-center mb-4">AZAL SPORT HOUSE</h3>

<?php if($error): ?>

<div class="alert alert-danger">
<?= $error ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label>Username or Email</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100">

Login

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "templates/footer.php"; ?>