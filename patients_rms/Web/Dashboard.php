<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Patient Record System</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
    }

    .sidebar {
      width: 220px;
      height: 100vh;
      background-color:rgb(22, 50, 102);
      color: white;
      position: fixed;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 10px 0;
      transition: 0.3s;
      white-space: pre;
    }

    .sidebar a:hover {
      background-color:rgb(85, 129, 165);
      border-radius: 4px;
    }

    .main {
      margin-left: 17%;
      padding: 20px;
    }

    .header {
      background-color:#d4efff;
      
      border-radius: 8px;
      
      <!--background-image: url("welcome.jpg");
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      background-size: cover; -->
    }
    .welcomeImg {
      margin-top: 0px;
      margin-left: 32%;
      margin-bottom: 0px;
    }
    .welcomeTxt {
      padding: 20px;
      margin-bottom: 20px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .card-link {
      text-decoration: none;
      color: inherit;
    }

    .card {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: center;
      transition: 0.3s;
      height: 60%;
    }

    .card:hover {
      background-color: #dfe4ea;
      cursor: pointer;
    }

    .card h3 {
      margin: 10px 0 0;
    }
    span {
      float: right;
    }
  </style>
  <script src="https://kit.fontawesome.com/bf3e859ff1.js" crossorigin="anonymous"></script>

</head>
<body>

  <div class="sidebar">
    <h2>Dashboard</h2>
    <a href="dashboard.php">Home<span><i class="fa-solid fa-house"></i></span></a> 
    <a href="patient.php">Patients<span><i class="fa-solid fa-user"></i></span></a>
    <a href="appointments.php">Appointments<span><i class="fa-solid fa-clock"></i></span></a>
    <a href="labreports.php">Lab Reports<span><i class="fa-solid fa-desktop"></i></span></a>
    <a href="prescriptions.php">Prescriptions<span><i class="fa-solid fa-file-medical"></i></span></a>
    <a href="medications.php">Medications<span><i class="fa-solid fa-prescription-bottle-medical"></i></span></a>
    <a href="logout.php">Logout<span><i class="fa-solid fa-right-from-bracket"></i></span></a>
  </div>

  <div class="main">
    <div class="header">
      <div class="welcomeImg"><img src="welcome.jpg" height="50%" width="50%"></div>
    </div>

    <div class="welcomeTxt">  
        <h1>Welcome to the Patient Records System</h1>
        <p>Select a section to manage records.</p>
      </div>

    <div class="cards">
      <a href="patient.php" class="card-link">
        <div class="card">
          <h3>Patients</h3>
          <p>View or add patient records</p>
        </div>
      </a>

      <a href="appointments.php" class="card-link">
        <div class="card">
          <h3>Appointments</h3>
          <p>Manage appointment schedules</p>
        </div>
      </a>

      <a href="labreports.php" class="card-link">
        <div class="card">
          <h3>Lab Reports</h3>
          <p>Upload and review test results</p>
        </div>
      </a>

      <a href="prescriptions.php" class="card-link">
        <div class="card">
          <h3>Prescriptions</h3>
          <p>View or issue prescriptions</p>
        </div>
      </a>

      <a href="medications.php" class="card-link">
        <div class="card">
          <h3>Medications</h3>
          <p>Track prescribed medicines</p>
        </div>
      </a>
    </div>
  </div>

</body>
</html>
