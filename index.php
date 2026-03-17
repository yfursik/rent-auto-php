<?php
require_once 'db.php';

// Získání aut z databáze
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentAuto - Půjčovna luxusních vozů</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">RentAuto</a>
        <div class="ms-auto">
            <a href="admin.php" class="btn btn-outline-light btn-sm">Administrace</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-5">Naše nabídka vozů</h1>
    <div class="row">

        <?php
        // Výpis aut pomocí smyčky (выводим машины циклом)
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4 mb-4">
                    <div class="card bg-secondary text-white h-100 shadow">
                        <img src="' . htmlspecialchars($row["image_url"]) . '" class="card-img-top" alt="Auto" style="height: 250px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title fw-bold">' . htmlspecialchars($row["brand"]) . ' ' . htmlspecialchars($row["model"]) . '</h4>
                            <p class="card-text">' . htmlspecialchars($row["description"]) . '</p>
                            <h5 class="mt-auto text-warning">' . number_format($row["price_per_day"], 0, ',', ' ') . ' Kč / den</h5>
                            <a href="book.php?car_id=' . $row["id"] . '" class="btn btn-light mt-3 fw-bold text-dark">Rezervovat</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo "<p class='text-center'>Zatím tu nejsou žádná auta.</p>";
        }
        ?>

    </div>
</div>

</body>
</html>