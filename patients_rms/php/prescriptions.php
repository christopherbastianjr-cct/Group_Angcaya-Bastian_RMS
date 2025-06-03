<?php
// Database connection settings
$db_server = 'localhost';
$db_user = 'root';    // change to your DB username
$db_password = '';// change to your DB password
$db_name = 'gms';              // change to your DB name

$conn = new mysqli($db_server, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'] ?: NULL; // allow NULL
    $prescription_date = $_POST['prescription_date'];
    $medication_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $duration = $_POST['duration'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO prescriptions_tbl 
        (patient_id, appointment_id, prescription_date, medication_name, dosage, frequency, duration, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssss", $patient_id, $appointment_id, $prescription_date, $medication_name, $dosage, $frequency, $duration, $notes);
    $stmt->execute();
    $stmt->close();
}

// Fetch prescriptions with patient name and appointment date
$sql = "SELECT p.prescription_id, p.prescription_date, p.medication_name, p.dosage, p.frequency, p.duration, p.notes, 
        pt.full_name AS patient_name, a.appointment_date
        FROM prescriptions_tbl p
        LEFT JOIN patient_tbl pt ON p.patient_id = pt.patient_id
        LEFT JOIN appointments_tbl a ON p.appointment_id = a.appointment_id
        ORDER BY p.prescription_date DESC";
$result = $conn->query($sql);

// Fetch patients for dropdown
$patients_result = $conn->query("SELECT patient_id, full_name FROM patient_tbl ORDER BY full_name ASC");

// Fetch appointments for dropdown
$appointments_result = $conn->query("SELECT appointment_id, appointment_date FROM appointments_tbl ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Prescriptions</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f9; }
        h2 { margin-bottom: 15px; }
        form, table { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ccc; }
        button { background-color: rgb(22, 50, 102); color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
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

<h2>Add Prescription</h2>
<form method="POST" action="">
    <label for="patient_id">Patient</label>
    <select name="patient_id" id="patient_id" required>
        <option value="">Select Patient</option>
        <?php while ($patient = $patients_result->fetch_assoc()): ?>
            <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['full_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="appointment_id">Appointment (optional)</label>
    <select name="appointment_id" id="appointment_id">
        <option value="">Select Appointment</option>
        <?php while ($app = $appointments_result->fetch_assoc()): ?>
            <option value="<?= $app['appointment_id'] ?>"><?= htmlspecialchars($app['appointment_date']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="prescription_date">Prescription Date</label>
    <input type="date" id="prescription_date" name="prescription_date" required>

    <label for="medication_name">Medication Name</label>
    <input type="text" id="medication_name" name="medication_name" required>

    <label for="dosage">Dosage</label>
    <input type="text" id="dosage" name="dosage" required>

    <label for="frequency">Frequency</label>
    <input type="text" id="frequency" name="frequency">

    <label for="duration">Duration</label>
    <input type="text" id="duration" name="duration">

    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="3"></textarea>

    <button type="submit">Add Prescription</button>
</form>

<h2>Prescription Records</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Appointment Date</th>
        <th>Prescription Date</th>
        <th>Medication</th>
        <th>Dosage</th>
        <th>Frequency</th>
        <th>Duration</th>
        <th>Notes</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['prescription_id'] ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= $row['appointment_date'] ?? 'N/A' ?></td>
                <td><?= $row['prescription_date'] ?></td>
                <td><?= htmlspecialchars($row['medication_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage']) ?></td>
                <td><?= htmlspecialchars($row['frequency']) ?></td>
                <td><?= htmlspecialchars($row['duration']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No prescriptions found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
