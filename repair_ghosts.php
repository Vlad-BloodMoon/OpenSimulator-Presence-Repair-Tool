<?php
$host = 'localhost';
$db   = 'robust';
$user = 'your_mysql_user';
$pass = 'your_mysql_password';
$charset = 'utf8mb4';

$logfile = __DIR__ . '/repair_ghosts.log';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function append_log($logfile, $line) {
    $line = trim($line) . "\n";
    if (!file_exists($logfile)) {
        file_put_contents($logfile, $line);
        return;
    }
    $lines = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines[] = $line;
    $lines = array_slice($lines, -100);
    file_put_contents($logfile, implode("\n", $lines) . "\n");
}

append_log($logfile, "=== Script started at " . date('Y-m-d H:i:s') . " ===");

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->prepare("SELECT UserID FROM Presence WHERE RegionID = '00000000-0000-0000-0000-000000000000'");
    $stmt->execute();
    $ghosts = $stmt->fetchAll();

    if (empty($ghosts)) {
        $log = "No ghost avatars found.";
        echo $log . "\n";
        append_log($logfile, $log);
    }

    foreach ($ghosts as $ghost) {
        $uuid = $ghost['UserID'];
        $fullID = 'Unknown';

        $find = $pdo->prepare("SELECT UserID FROM GridUser WHERE UserID LIKE ?");
        $find->execute([$uuid . '%']);
        $row = $find->fetch();
        if ($row) {
            $fullID = $row['UserID'];
        }

        $del = $pdo->prepare("DELETE FROM Presence WHERE UserID = ?");
        $del->execute([$uuid]);

        $upd = $pdo->prepare("UPDATE GridUser SET Online = 'False' WHERE UserID LIKE ?");
        $upd->execute([$uuid . '%']);

        $log = "Fixed: UUID = $uuid | GridUser = $fullID";
        echo $log . "\n";
        append_log($logfile, $log);
    }

} catch (\PDOException $e) {
    $log = "Error: " . $e->getMessage();
    echo $log . "\n";
    append_log($logfile, $log);
}
?>