<?php
require_once 'db.php';

// Zpracování smazání auta
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    $conn->query("DELETE FROM cars WHERE id = $id_to_delete");
    header("Location: admin.php");
    exit;
}

// Zpracování smazání rezervace (Удаление брони)
if (isset($_GET['delete_booking'])) {
    $id_to_delete = intval($_GET['delete_booking']);
    $conn->query("DELETE FROM bookings WHERE id = $id_to_delete");
    header("Location: admin.php");
    exit;
}

// Zpracování přidání nového auta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_car'])) {
    $brand = $conn->real_escape_string($_POST['brand']);
    $model = $conn->real_escape_string($_POST['model']);
    $price = intval($_POST['price']);
    $desc = $conn->real_escape_string($_POST['description']);
    $image = $conn->real_escape_string($_POST['image_url']);

    $add_sql = "INSERT INTO cars (brand, model, price_per_day, description, image_url) 
                VALUES ('$brand', '$model', $price, '$desc', '$image')";
    $conn->query($add_sql);
    header("Location: admin.php");
    exit;
}

// Načtení všech aut
$sql_cars = "SELECT * FROM cars";
$result_cars = $conn->query($sql_cars);

// Načtení rezervací propojených s auty pomocí JOIN (Связываем брони и машины)
$sql_bookings = "SELECT b.id, b.client_name, b.client_phone, b.start_date, b.end_date, c.brand, c.model 
                 FROM bookings b 
                 JOIN cars c ON b.car_id = c.id 
                 ORDER BY b.start_date ASC";
$result_bookings = $conn->query($sql_bookings);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace - RentAuto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light pb-5">

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <span class="navbar-brand mb-0 h1 fw-bold">Administrace systému</span>
        <a href="index.php" class="btn btn-outline-light btn-sm">Zpět na web</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Přidat nové auto</div>
                <div class="card-body">
                    <form method="POST" action="admin.php">
                        <div class="mb-3">
                            <label class="form-label">Značka</label>
                            <input type="text" name="brand" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cena za den (Kč)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Popis</label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL obrázku</label>
                            <input type="text" name="image_url" class="form-control" required>
                        </div>
                        <button type="submit" name="add_car" class="btn btn-success w-100 fw-bold">Přidat do katalogu</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">Seznam vozidel v databázi</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Auto</th>
                            <th>Cena</th>
                            <th>Akce</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result_cars->num_rows > 0) {
                            while($row = $result_cars->fetch_assoc()) {
                                echo "<tr>
                                            <td class='align-middle'>{$row['id']}</td>
                                            <td class='align-middle'><b>" . htmlspecialchars($row['brand']) . "</b> " . htmlspecialchars($row['model']) . "</td>
                                            <td class='align-middle'>" . number_format($row['price_per_day'], 0, ',', ' ') . " Kč</td>
                                            <td class='align-middle'>
                                                <a href='edit_car.php?id={$row['id']}' class='btn btn-warning btn-sm'>Upravit</a>
                                                <a href='admin.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Opravdu smazat toto auto?\")'>Smazat</a>
                                            </td>
                                          </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-3'>Zatím tu nejsou žádná auta.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white fw-bold">Nové rezervace od klientů</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Klient</th>
                            <th>Telefon</th>
                            <th>Vybrané auto</th>
                            <th>Od</th>
                            <th>Do</th>
                            <th>Akce</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result_bookings->num_rows > 0) {
                            while($row = $result_bookings->fetch_assoc()) {
                                // Formátování data do hezkého tvaru (DD.MM.YYYY)
                                $start = date("d.m.Y", strtotime($row['start_date']));
                                $end = date("d.m.Y", strtotime($row['end_date']));

                                echo "<tr>
                                            <td class='align-middle'><b>" . htmlspecialchars($row['client_name']) . "</b></td>
                                            <td class='align-middle'>" . htmlspecialchars($row['client_phone']) . "</td>
                                            <td class='align-middle text-primary fw-bold'>" . htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']) . "</td>
                                            <td class='align-middle'>$start</td>
                                            <td class='align-middle'>$end</td>
                                            <td class='align-middle'>
                                                <a href='admin.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Opravdu smazat toto auto?\")'>Smazat</a>
                                            </td>
                                          </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4'>Zatím nemáte žádné rezervace.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>