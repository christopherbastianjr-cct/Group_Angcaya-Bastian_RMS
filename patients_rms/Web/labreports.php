<?php
// labreports.php

// Database connection
$db_host = 'localhost';
$db_user = 'root';     // update if needed
$db_pass = '';         // update if needed
$db_name = 'gms';      // your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload and form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $report_date = $_POST['report_date'];
    $report_type = $_POST['report_type'];
    $report_description = $_POST['report_description'];

    $upload_dir = 'uploads/labreports/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $report_file = null;
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['report_file']['tmp_name'];
        $filename = basename($_FILES['report_file']['name']);
        $target_file = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $report_file = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO labreports_tbl (patient_id, report_date, report_type, report_description, report_file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patient_id, $report_date, $report_type, $report_description, $report_file);
    $stmt->execute();
    $stmt->close();
}

// Fetch lab reports with patient names
$sql = "SELECT lr.report_id, lr.report_date, lr.report_type, lr.report_description, lr.report_file,
               p.full_name
        FROM labreports_tbl lr
        JOIN patient_tbl p ON lr.patient_id = p.patient_id
        ORDER BY lr.report_date DESC";

$result = $conn->query($sql);

// Fetch all patients for dropdown
$patients_res = $conn->query("SELECT patient_id, full_name FROM patient_tbl ORDER BY full_name");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Lab Reports</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
    h2 { margin-bottom: 15px; }
    form, table { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
    input, select, textarea { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 4px; }
    button { background: rgb(22, 50, 102); color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #57606f; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: rgb(22, 50, 102); color: white; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #f1f1f1; }
    a { color: #2f3542; text-decoration: none; }
    .back-button { margin-bottom: 5px; display: inline-block; background: rgb(22, 50, 102); color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
  </style>
</head>
<body>

  <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>

  <h2>Add Lab Report</h2>
  <form method="POST" enctype="multipart/form-data">
    <label for="patient_id">Patient</label>
    <select name="patient_id" id="patient_id" required>
      <option value="">Select Patient</option>
      <?php while($patient = $patients_res->fetch_assoc()): ?>
        <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['full_name']) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="report_date">Report Date</label>
    <input type="date" name="report_date" id="report_date" required />

    <label for="report_type">Report Type</label>
    <input type="text" name="report_type" id="report_type" placeholder="e.g., Blood Test, X-Ray" required />

    <label for="report_description">Description</label>
    <textarea name="report_description" id="report_description" rows="3" placeholder="Optional notes"></textarea>

    <label for="report_file">Upload Report File (PDF, Image, etc.)</label>
    <input type="file" name="report_file" id="report_file" accept=".pdf,.jpg,.jpeg,.png" />

    <button type="submit">Add Report</button>
  </form>

  <h2>Lab Reports List</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th>File</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['report_id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= $row['report_date'] ?></td>
            <td><?= htmlspecialchars($row['report_type']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['report_description'])) ?></td>
            <td>
              <?php if ($row['report_file']): ?>
                <a href="<?= htmlspecialchars($row['report_file']) ?>" target="_blank">View</a>
              <?php else: ?>
                No File
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">No lab reports found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>

<?php $conn->close(); ?>
