<?php
// Database connection settings
$db_server = 'localhost';
$db_user = 'root';      // Change to your DB username
$db_password = '';  // Change to your DB password
$db_name = 'gms';                // Change to your DB name

$conn = new mysqli($db_server, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add medication
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medication_name = $_POST['medication_name'];
    $description = $_POST['description'];
    $manufacturer = $_POST['manufacturer'];
    $side_effects = $_POST['side_effects'];

    $stmt = $conn->prepare("INSERT INTO medications_tbl (medication_name, description, manufacturer, side_effects) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $medication_name, $description, $manufacturer, $side_effects);
    $stmt->execute();
    $stmt->close();
}

// Fetch medications
$sql = "SELECT * FROM medications_tbl ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Medications</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; }
    h2 { margin-bottom: 15px; }
    form, table { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
    input[type=text], textarea { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ccc; }
    button { background-color: rgb(22, 50, 102); color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background-color: #57606f; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: rgb(22, 50, 102); color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #f1f1f1; }
    .back-btn { margin-bottom: 20px; display: inline-block; text-decoration: none; background: rgb(22, 50, 102); color: white; padding: 10px 15px; border-radius: 5px; }
    .back-btn:hover { background: #57606f; }
  </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">&larr; Back to Dashboard</a>

<h2>Add Medication</h2>
<form method="POST" action="">
  <input type="text" name="medication_name" placeholder="Medication Name" required />
  <textarea name="description" placeholder="Description" rows="3"></textarea>
  <input type="text" name="manufacturer" placeholder="Manufacturer" />
  <textarea name="side_effects" placeholder="Side Effects" rows="2"></textarea>
  <button type="submit">Add Medication</button>
</form>

<h2>Medication List</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Manufacturer</th>
    <th>Side Effects</th>
    <th>Created At</th>
  </tr>
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['medication_id'] ?></td>
        <td><?= htmlspecialchars($row['medication_name']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
        <td><?= htmlspecialchars($row['manufacturer']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['side_effects'])) ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No medications found.</td></tr>
  <?php endif; ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
