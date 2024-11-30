<?php
include 'con.php';
if (isset($_POST['submit'])) {
    $pass = $_POST['pass'];
    if ($pass == "darshan_made_checkout") {
        $oid = $_POST['oid'] ?? '';
        $amo = $_POST['amo'] ?? '';
        $tnote = $_POST['tnote'] ?? '';
        $curl = $_POST['curl'] ?? '';
        $uname = $_POST['uname'] ?? '';
        $email = $_POST['email'] ?? '';
        $status = 'Pending';
        $numberAsString = strval($oid);
        $length = strlen($numberAsString);

        if ($length < 13) {
            $url = "Order ID Length Must Be More Than 12 Characters";
        } else {
            // Step 1: Check if a record with the same 'oid' already exists
            $checkSql = "SELECT * FROM payment WHERE oid = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $oid);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // A record with the same 'oid' already exists
                $url = "This Order ID Already Exists";
            } else {
                // Step 2: If no duplicate, proceed to insert the new record
                $id = "FB" . mt_rand(100000, 999999);
                $sql = "INSERT INTO payment (id, oid, amo, tnote, curl, uname, email, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param("ssssssss", $id, $oid, $amo, $tnote, $curl, $uname, $email, $status);

                if ($stmt->execute()) {
                    $url = "http://localhost:8000/?id=" . $id;
                } else {
                    $url = "Error: " . $stmt->error;
                }

                // Step 4: Close the prepared statement
                $stmt->close();
            }
        }
    } else {
        $url = "Incorrect Password";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FastBack - Create Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="https://infotecharmy.in/lifafa/logo/IMG_20230826_112146_439.jpg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top" style="border-radius: 100%;">
      Mr Dark
    </a>
  </div>
</nav>
<form method="post">
<div class="container my-3">
	<input class="form-control" type="text" placeholder="Order ID" aria-label="" name="oid" required>
		<input class="form-control my-2" type="text" placeholder="Amount " aria-label="" name="amo" required>
 <input class="form-control my-2" type="text" placeholder="Transcation Note " aria-label="" name="tnote" required>
 	<input class="form-control my-2" type="text" placeholder="Enter User Name" aria-label="" name="uname" required>
 	<input class="form-control my-2" type="text" placeholder="Enter User Email " aria-label="" name="email" required>
 	<input class="form-control my-2" type="text" placeholder="Call Back URL " aria-label="" name="curl">
 	<input class="form-control my-2" type="text" placeholder="Enter Your Password" aria-label="" name="pass" required>
 	<div class="d-grid gap-2">
  <button class="btn btn-primary" type="submit" name="submit">Create Link</button>
  </form>
</div><hr>
<input class="form-control my-2" type="text" placeholder="URL Will Generate Here" value="<?= $url; ?>" readonly>
	<div class="d-grid gap-2">
  <button class="btn btn-primary" type="button">Copy Link</button>
</div>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>