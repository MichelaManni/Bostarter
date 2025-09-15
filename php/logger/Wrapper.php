<?php
// Il wrapper incapsula mysqli che è usato in tutta l'applicazione per l'accesso al databse.
class MysqliLoggerWrapper {
    private mysqli $inner;
    private MongoLogger $logger;

    public function __construct(mysqli $inner, MongoLogger $logger) {
        $this->inner  = $inner;
        $this->logger = $logger;
    }

    //Per passare alla parte stmt
    public function prepare(string $query) {
        $stmt = $this->inner->prepare($query);
        if (!$stmt) return false;
        return new MysqliStmtLoggerWrapper($stmt, $query, $this->logger);
    }

    public function query(string $query) { return $this->inner->query($query); }
    public function __call($name, $args) { return $this->inner->$name(...$args); }
    public function __get($name) { return $this->inner->$name; }
}

// Wrapper ulteriore dello statement mysqli che: 
// intercetta execute() per loggare eventi legati a CALL di stored procedure
class MysqliStmtLoggerWrapper {
    private mysqli_stmt $inner;
    private string $query;
    private MongoLogger $logger;
    private ?string $types = null;
    private array $boundValues = [];

    public function __construct(mysqli_stmt $inner, string $query, MongoLogger $logger) {
        $this->inner   = $inner;
        $this->query   = $query;
        $this->logger  = $logger;
    }

    public function bind_result(&...$vars) {
    return $this->inner->bind_result(...$vars);
    }

    public function bind_param($types, &...$vars) {
        $this->types = $types;
        $this->boundValues = $vars; 
        $args = [$types];
        foreach ($vars as &$v) { $args[] = &$v; }
        return $this->inner->bind_param(...$args);
    }

    public function execute() {
        $ok = $this->inner->execute();
        if ($ok) $this->Logging();
        return $ok;
    }

    private function Logging(): void {
        if (preg_match('/^\s*CALL\s+([A-Za-z0-9_]+)/i', $this->query, $m)) {
            $proc = $m[1];
            $payload = $this->CreaLog($proc);
            if ($payload !== null) {
                $this->logger->log($payload['event'], $payload['data']);
            }
        }
    }

    //Per scrivere su Mongodb a seconda dell'operazione
    private function CreaLog(string $proc): ?array {
        $p = array_map(function($v){
            if (is_object($v) || is_array($v)) return json_encode($v);
            return $v;
        }, $this->boundValues);

        switch ($proc) {
            case 'Registrazione':
                return ['event'=>'registrazione.utente','data'=>[
                    'email'=>$p[0]??null,'nickname'=>$p[5]??null,'ruolo'=>$p[7]??null
                ]];
            case 'AggiungiProgetto':
                return ['event'=>'creazione.progetto','data'=>[
                    'email_creatore'=>$p[0]??null,'nome'=>$p[1]??null,'tipologia'=>$p[5]??null,
                    'budget'=>$p[3]??null,'scadenza'=>$p[4]??null
                ]];
            case 'AggiungiFotoProgetto':
                return ['event'=>'aggiunta.foto','data'=>[
                    'nome_progetto'=>$p[0]??null,'path'=>$p[1]??null
                ]];
            case 'AggiungiComponente':
                return ['event'=>'aggiunta.componente','data'=>[
                    'nome'=>$p[0]??null,'progetto'=>$p[4]??null,'quantita'=>$p[2]??null,'prezzo'=>$p[3]??null
                ]];
            case 'AggiungiSkillProfilo':
                return ['event'=>'aggiunta.skill','data'=>[
                    'id_profilo'=>$p[0]??null,'skill'=>$p[1]??null,'livello'=>$p[2]??null
                ]];
            case 'InserimentoReward':
                return ['event'=>'aggiunta.reward','data'=>[
                    'progetto'=>$p[1]??null,'descrizione'=>$p[0]??null
                ]];
            case 'InserimentoCandidatura':
                return ['event'=>'aggiunta.candidatura','data'=>[
                    'email'=>$p[0]??null,'id_profilo'=>$p[1]??null
                ]];
            case 'InserimentoCommento':
                return ['event'=>'add.commento','data'=>[
                    'email'=>$p[0]??null,'progetto'=>$p[2]??null
                ]];
            case 'InserimentoRisposta':
                return ['event'=>'add.risposta','data'=>[
                    'email_creatore'=>$p[0]??null,'commento_id'=>$p[1]??null
                ]];
            case 'FinanziaProgetto':
                return ['event'=>'aggiunta.finanziamento','data'=>[
                    'email'=>$p[0]??null,'progetto'=>$p[1]??null,
                    'importo'=>$p[2]??null,'codice_reward'=>$p[3]??null
                ]];
            default:
                return null;
        }
    }

    public function close() { return $this->inner->close(); }
    public function __call($name, $args) { return $this->inner->$name(...$args); }
    public function __get($name) { return $this->inner->$name; }
}
