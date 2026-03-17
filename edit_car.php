<?php
require_once 'db.php';

// Zkontrolujeme, zda máme ID auta k úpravě
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = intval($_GET['id']);

// Zpracování formuláře pro úpravu (Обновление данных в базе)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_car'])) {
    $brand = $conn->real_escape_string($_POST['brand']);
    $model = $conn->real_escape_string($_POST['model']);
    $price = intval($_POST['price']);
    $desc = $conn->real_escape_string($_POST['description']);
    $image = $conn->real_escape_string($_POST['image_url']);

    $update_sql = "UPDATE cars SET 
                    brand = '$brand', 
                    model = '$model', 
                    price_per_day = $price, 
                    description = '$desc', 
                    image_url = '$image' 
                   WHERE id = $id";

    $conn->query($update_sql);
    header("Location: admin.php");
    exit;
}

// Načtení aktuálních dat o autě (Загружаем текущие данные, чтобы подставить в форму)
$car_sql = "SELECT * FROM cars WHERE id = $id";
$car_result = $conn->query($car_sql);
$car = $car_result->fetch_assoc();

if (!$car) {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upravit auto - RentAuto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-5 shadow">
    <div class="container">
        <span class="navbar-brand mb-0 h1 fw-bold">Úprava vozidla</span>
        <a href="admin.php" class="btn btn-outline-light btn-sm">Zpět do administrace</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning fw-bold">Upravit detaily auta: <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Značka</label>
                            <input type="text" name="brand" class="form-control" value="<?php echo htmlspecialchars($car['brand']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" value="<?php echo htmlspecialchars($car['model']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cena za den (Kč)</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $car['price_per_day']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Popis</label>
                            <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($car['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL obrázku</label>
                            <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($car['image_url']); ?>" required>
                        </div>
                        <button type="submit" name="update_car" class="btn btn-warning w-100 fw-bold">Uložit změny</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>