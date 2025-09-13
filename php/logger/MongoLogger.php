<?php
// Logger minimale su MongoDB usando l'estensione "mongodb" (PECL)
class MongoLogger {
    private MongoDB\Driver\Manager $manager;
    private string $namespace;

    public function __construct(array $cfg = []) {
        $host = $cfg['host'] ?? getenv('MONGO_HOST') ?: 'mongodb';
        $port = $cfg['port'] ?? getenv('MONGO_PORT') ?: '27017';
        $user = $cfg['username'] ?? getenv('MONGO_USERNAME') ?: 'admin_username';
        $pass = $cfg['password'] ?? getenv('MONGO_PASSWORD') ?: 'admin_password';
        $auth = $cfg['authSource'] ?? getenv('MONGO_AUTHSOURCE') ?: 'admin';
        $db   = $cfg['database'] ?? getenv('MONGO_DATABASE') ?: 'BostarterLogs';
        $col  = $cfg['collection'] ?? getenv('MONGO_COLLECTION') ?: 'event_log';

        $uri = sprintf(
            'mongodb://%s:%s@%s:%s/?authSource=%s',
            urlencode($user), urlencode($pass), $host, $port, $auth
        );

        // Manager + namespace "db.collection"
        $this->manager   = new MongoDB\Driver\Manager($uri);
        $this->namespace = $db . '.' . $col;
    }

    public function log(string $event, array $payload = [], array $meta = []): void {
        try {
            $bulk = new MongoDB\Driver\BulkWrite; // vedi manuale BulkWrite::insert :contentReference[oaicite:4]{index=4}
            $doc  = [
                'ts'     => new MongoDB\BSON\UTCDateTime(),
                'event'  => $event,
                'payload'=> $payload,
                'meta'   => array_merge([
                    'ip'     => $_SERVER['REMOTE_ADDR']  ?? null,
                    'uri'    => $_SERVER['REQUEST_URI']  ?? null,
                    'method' => $_SERVER['REQUEST_METHOD'] ?? null,
                    'session_email' => $_SESSION['Email'] ?? null,
                ], $meta),
            ];
            $bulk->insert($doc);
            $this->manager->executeBulkWrite($this->namespace, $bulk);
        } catch (Throwable $e) {
            // Non bloccare il flusso applicativo
            error_log('[MongoLogger] ' . $e->getMessage());
        }
    }
}
