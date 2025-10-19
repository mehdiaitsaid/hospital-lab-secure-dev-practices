<?php
$page_title = "Search Doctor";
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/models/User.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Search Doctor</h2>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Enter doctor name..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php
    if (!empty($_GET['q'])) {
        $query = trim($_GET['q']);
        $query_escaped = $mysqli->real_escape_string($query);

        // Simple search by full_name (vulnerable for lab exercise)
        $sql = "SELECT * FROM users WHERE role_id = 2 and full_name LIKE '%$query_escaped%' ORDER BY full_name ASC";
        $res = $mysqli->query($sql);

        if ($res && $res->num_rows > 0) {
            echo '<div class="list-group">';
            while ($row = $res->fetch_assoc()) {
                echo '<div class="list-group-item">';
                echo '<h5 class="mb-1">' . htmlspecialchars($row['full_name']) . '</h5>';
                echo '<p class="mb-0"><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
                echo '<p class="mb-0"><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning">No doctors found matching "' . htmlspecialchars($query) . '".</div>';
        }
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
