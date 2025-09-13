<?php
// Visualizzatore minimale dei log
// URL: http://localhost:8000/logger/LogViewer.php

$host = getenv('MONGO_HOST') ?: 'mongodb';
$port = getenv('MONGO_PORT') ?: '27017';
$user = getenv('MONGO_USERNAME') ?: 'admin_username';
$pass = getenv('MONGO_PASSWORD') ?: 'admin_password';
$auth = getenv('MONGO_AUTHSOURCE') ?: 'admin';
$db   = getenv('MONGO_DATABASE') ?: 'BostarterLogs';
$col  = getenv('MONGO_COLLECTION') ?: 'event_log';

$uri = sprintf('mongodb://%s:%s@%s:%s/?authSource=%s',
    urlencode($user), urlencode($pass), $host, $port, $auth
);
$manager = new MongoDB\Driver\Manager($uri);

$filter = [];
if (!empty($_GET['event'])) $filter['event'] = $_GET['event'];

$query  = new MongoDB\Driver\Query($filter, ['sort'=>['ts'=>-1], 'limit'=>200]);
$cursor = $manager->executeQuery($db.'.'.$col, $query);
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<title>Log eventi</title>
<style>
 body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:20px}
 table{border-collapse:collapse;width:100%}
 th,td{border:1px solid #ddd;padding:8px;vertical-align:top}
 th{background:#f2f2f2}
 pre{margin:0;white-space:pre-wrap}
 code{font-family:ui-monospace,Menlo,Consolas,monospace}
</style>
</head>
<body>
<h1>Log eventi</h1>
<form method="get" style="margin-bottom:12px">
  <label>Filtra per <code>event</code>:</label>
  <input name="event" value="<?= htmlspecialchars($_GET['event'] ?? '') ?>">
  <button type="submit">Filtra</button>
  <a href="LogViewer.php">Reset</a>
</form>
<table>
  <tr><th>ts</th><th>event</th><th>payload</th><th>meta</th></tr>
  <?php foreach ($cursor as $doc): 
        $ts = ($doc->ts instanceof MongoDB\BSON\UTCDateTime) ? $doc->ts->toDateTime()->format('Y-m-d H:i:s') : '';
        $event = $doc->event ?? '';
        $payload = json_encode($doc->payload ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        $meta    = json_encode($doc->meta ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
  ?>
  <tr>
    <td><?= htmlspecialchars($ts) ?></td>
    <td><code><?= htmlspecialchars($event) ?></code></td>
    <td><pre><?= htmlspecialchars($payload) ?></pre></td>
    <td><pre><?= htmlspecialchars($meta) ?></pre></td>
  </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
