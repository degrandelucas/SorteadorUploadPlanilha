<?php

namespace Traits;

trait DatabaseConnection{
    public function getConnection()
    {
        $configuration = require __DIR__ . '/../config/database.php';
        try {
            $connection = new \mysqli(
                $configuration["servername"],
                $configuration["username"],
                $configuration["password"],
                $configuration["dbname"]);
            //  $connection = new \mysqli(...array_values($config));
            if ($connection->connect_error) {
                throw new Exception("Erro conexão Banco Dados: $connection->connect_error");
            }
            //echo "Conectado com sucesso!";
            return $connection;
            //$connection->close();
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
            return null; //null no caso de erro
        }
    }
}
