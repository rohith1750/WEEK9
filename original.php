<?php
if  if (isset($_POST["submit"]))  {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $class = $_POST["class"];

    // Check if a user with the same name already exists
    require_once "db.php"; // Include your database connection file
    $sql = "SELECT * FROM users WHERE name = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            echo "User with the same name already exists:<br>";
            echo "Name: " . $row["name"] . "<br>";
            echo "Email: " . $row["email"] . "<br>";
            echo "Phone: " . $row["phone"] . "<br>";
            echo "Class: " . $row["class"] . "<br>";
        } else {
            // Insert the new user into the database
            $insertSql = "INSERT INTO users (name, mail, phone, class) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($insertStmt, $insertSql)) {
                mysqli_stmt_bind_param($insertStmt, "ssss", $name, $email, $phone, $class);
                mysqli_stmt_execute($insertStmt);
                echo "User registration successful!";
            } else {
                echo "Error inserting user into the database.";
            }
        }
    } else {
        echo "Database error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Registration Form</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Name (characters only):</label>
                <input type="text" name="name" id="name" required pattern="[A-Za-z\s]+">
            </div>
            <div class="form-group">
                <label for="email">Email (ending with .com, .in, or other TLDs):</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone (10 digits, begins with 0, 6, or 9):</label>
                <input type="tel" name="phone" id="phone" required pattern="[0-9]{10}" maxlength="10">
            </div>
            <div class="form-group">
                <label for="class">Class (silver, gold, or platinum):</label>
                <select name="class" id="class" required>
                    <option value="silver">Silver</option>
                    <option value="gold">Gold</option>
                    <option value="platinum">Platinum</option>
                </select>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Save">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("form").submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#userDetails").html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
