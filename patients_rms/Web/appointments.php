<?php
// appointments.php

// Connect to database
$db_host = 'localhost';
$db_user = 'root'; // change to your DB user
$db_pass = '';     // change to your DB password
$db_name = 'gms';  // your database

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert new appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments_tbl (patient_id, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $appointment_date, $appointment_time, $reason);
    $stmt->execute();
    $stmt->close();
}

// Get patient list for dropdown
$patients = $conn->query("SELECT patient_id, full_name FROM patient_tbl");

// Get all appointments
$appointments = $conn->query("
  SELECT a.*, p.full_name 
  FROM appointments_tbl a
  JOIN patient_tbl p ON a.patient_id = p.patient_id
  ORDER BY a.appointment_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Appointments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 20px;
    }
    h2 { margin-bottom: 10px; }
    form {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    form input, form select, form textarea {
      display: block;
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    form button {
      background-color: rgb(22, 50, 102);
      color: white;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: rgb(22, 50, 102);
      color: white;
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

  <h2>Add Appointment</h2>
  <form method="POST">
    <label>Select Patient:</label>
    <select name="patient_id" required>
      <option value="">-- Choose Patient --</option>
      <?php while ($row = $patients->fetch_assoc()): ?>
        <option value="<?= $row['patient_id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
      <?php endwhile; ?>
    </select>

    <input type="date" name="appointment_date" required>
    <input type="time" name="appointment_time" required>
    <textarea name="reason" placeholder="Reason for appointment" rows="3" required></textarea>

    <button type="submit">Add Appointment</button>
  </form>

  <h2>Appointment Records</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Patient</th>
      <th>Date</th>
      <th>Time</th>
      <th>Reason</th>
      <th>Status</th>
    </tr>
    <?php if ($appointments->num_rows > 0): ?>
      <?php while ($row = $appointments->fetch_assoc()): ?>
        <tr>
          <td><?= $row['appointment_id'] ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= $row['appointment_date'] ?></td>
          <td><?= $row['appointment_time'] ?></td>
          <td><?= htmlspecialchars($row['reason']) ?></td>
          <td><?= $row['status'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6" style="text-align:center;">No appointments yet.</td></tr>
    <?php endif; ?>
  </table>

</body>
</html>

<?php $conn->close(); ?>
