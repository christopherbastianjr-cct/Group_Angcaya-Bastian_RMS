<?php
// patients.php

// Database connection
$db_host = 'localhost';
$db_user = 'root';     // update if needed
$db_pass = '';         // update if needed
$db_name = 'gms';      // your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert patient record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO patient_tbl (full_name, date_of_birth, gender, contact_number, email_address, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $date_of_birth, $gender, $contact_number, $email_address, $address);
    $stmt->execute();
    $stmt->close();
}

// Fetch patient records
$result = $conn->query("SELECT * FROM patient_tbl ORDER BY patient_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Patient Records</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 20px;
    }
    h2 {
      margin-bottom: 10px;
    }
    form {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: rgb(22, 50, 102);
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #57606f;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: rgb(22, 50, 102);
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .back-button {
      margin-bottom: 20px;
      display: inline-block;
      background: rgb(22, 50, 102);
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 4px;
    }
  </style>
</head>
<body>

  <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>

  <h2>Add a Patient</h2>
  <form method="POST">
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="date" name="date_of_birth" required>
    <select name="gender" required>
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Other">Other</option>
    </select>
    <input type="text" name="contact_number" placeholder="Contact Number" required>
    <input type="email" name="email_address" placeholder="Email Address" required>
    <input type="text" name="address" placeholder="Address" required>
    <button type="submit">Add Patient</button>
  </form>

  <h2>Patient Records</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Date of Birth</th>
      <th>Gender</th>
      <th>Contact</th>
      <th>Email</th>
      <th>Address</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['patient_id'] ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= $row['date_of_birth'] ?></td>
          <td><?= $row['gender'] ?></td>
          <td><?= $row['contact_number'] ?></td>
          <td><?= $row['email_address'] ?></td>
          <td><?= htmlspecialchars($row['address']) ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" style="text-align:center;">No patient records found.</td></tr>
    <?php endif; ?>
  </table>

</body>
</html>

<?php $conn->close(); ?>
