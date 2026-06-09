<?php

final class DB{

    private ?PDO $connection;

    public function __construct()
    {
        $this->connection = $this->connection();
    }

    private function connection(): PDO|null
    {
        try{
            $this->parseEnvFile();
            $dsn = "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']};charset=UTF8";
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];
            $pdo = new PDO($dsn, $user, $password);

            return $pdo;
        }catch(\Throwable $e){
            error_log($e->getTraceAsString() . "\r\n", 3, "error_log");
            return null; 
        }
    }

    public function saveSearchTerm(string $searchTerm): void
    {
        try{
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare("INSERT INTO search (term, search_date) VALUES (:term,:search_date)");
            $statement->execute(["term" => $searchTerm, "search_date" => date('Y-m-d H:i:s')]);

            $this->connection->commit();
        }catch(Exception $e){
            error_log($e->getTraceAsString() . "\r\n", 3, "error_log");
            $this->connection->rollBack();
        }
    }

    public function getSearchTerms(): array
    {
        try{
            $query = $this->connection->query("SELECT * FROM search ORDER BY search_date DESC");
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }catch(\Throwable $e){
            error_log($e->getTraceAsString() . "\r\n", 3, "error_log");
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    private function parseEnvFile(): void
    {
        foreach(file(__DIR__ . '/../.env') as $line){
            if(trim($line) && !str_starts_with($line, '#')){
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
}