<?php
require_once 'db.php';

// Zkontrolujeme, zda bylo vybráno auto (проверяем, передали ли ID машины)
if (!isset($_GET['car_id'])) {
    header("Location: index.php");
    exit;
}

$car_id = intval($_GET['car_id']);

// Načtení dat o autě z databáze
$car_sql = "SELECT * FROM cars WHERE id = $car_id";
$car_result = $conn->query($car_sql);
$car = $car_result->fetch_assoc();

// Pokud auto neexistuje, vrátíme se na hlavní stranu
if (!$car) {
    header("Location: index.php");
    exit;
}

$message = "";

// Zpracování rezervačního formuláře (сохраняем бронь в базу)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_car'])) {
    $name = $conn->real_escape_string($_POST['client_name']);
    $phone = $conn->real_escape_string($_POST['client_phone']);
    $start = $conn->real_escape_string($_POST['start_date']);
    $end = $conn->real_escape_string($_POST['end_date']);

    $book_sql = "INSERT INTO bookings (car_id, client_name, client_phone, start_date, end_date) 
                 VALUES ($car_id, '$name', '$phone', '$start', '$end')";

    if ($conn->query($book_sql)) {
        $message = "<div class='alert alert-success'>Rezervace byla úspěšně odeslána! Brzy se vám ozveme.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Došlo k chybě při rezervaci.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervace - <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">RentAuto</a>
        <a href="index.php" class="btn btn-outline-light btn-sm ms-auto">Zpět na katalog</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Rezervace vozidla</h2>

            <?php echo $message; // Выводим сообщение об успехе или ошибке ?>

            <div class="card bg-secondary text-white shadow">
                <div class="row g-0">
                    <div class="col-md-5">
                        <img src="<?php echo htmlspecialchars($car['image_url']); ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="Auto">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h4 class="card-title fw-bold text-warning"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h4>
                            <p class="mb-4">Cena: <b><?php echo number_format($car['price_per_day'], 0, ',', ' '); ?> Kč / den</b></p>

                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Jméno a příjmení</label>
                                    <input type="text" name="client_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telefonní číslo</label>
                                    <input type="text" name="client_phone" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Od (Datum)</label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Do (Datum)</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>
                                </div>
                                <button type="submit" name="book_car" class="btn btn-light w-100 fw-bold mt-2">Potvrdit rezervaci</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>