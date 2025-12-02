<?php
class Database {
    private $pdo;
    private $db_file = "database/consultec_auth.sqlite";
    
    public function __construct() {
        try {
            if (!file_exists($this->db_file)) {
                $this->criarBanco();
            }
            
            $this->pdo = new PDO('sqlite:' . $this->db_file);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            die("Erro ao conectar com o banco: " . $e->getMessage());
        }
    }
    
    private function criarBanco() {
        if (!is_dir('database')) {
            mkdir('database', 0777, true);
        }
        
        $sql = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            telefone TEXT,
            cpf TEXT UNIQUE,
            data_nascimento TEXT,
            senha TEXT NOT NULL,
            tipo TEXT DEFAULT 'paciente',
            ativo INTEGER DEFAULT 1,
            data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS medicos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            crm TEXT UNIQUE NOT NULL,
            uf_crm TEXT NOT NULL,
            especialidade TEXT NOT NULL,
            telefone TEXT,
            senha TEXT NOT NULL,
            ativo INTEGER DEFAULT 1,
            data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS administradores (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            senha TEXT NOT NULL,
            nivel_acesso TEXT DEFAULT 'operacional',
            ativo INTEGER DEFAULT 1,
            data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        -- Inserir dados iniciais
        INSERT OR IGNORE INTO administradores (nome, email, senha, nivel_acesso) 
        VALUES ('Administrador Principal', 'admin@consultec.com', '123456', 'super');
        
        INSERT OR IGNORE INTO medicos (nome, email, crm, uf_crm, especialidade, telefone, senha) 
        VALUES ('Dr. Carlos Monteiro', 'carlos@consultec.com', '12345', 'SP', 'Cardiologia', '(11) 99999-9999', '123456');
        ";
        
        $temp_pdo = new PDO('sqlite:' . $this->db_file);
        $temp_pdo->exec($sql);
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function autenticarUsuario($email, $senha) {
        $sql = "
        SELECT id, nome, email, telefone, 'paciente' as tipo, 'usuarios' as tabela
        FROM usuarios 
        WHERE email = ? AND senha = ? AND ativo = 1
        
        UNION ALL
        
        SELECT id, nome, email, telefone, 'medico' as tipo, 'medicos' as tabela
        FROM medicos 
        WHERE email = ? AND senha = ? AND ativo = 1
        
        UNION ALL
        
        SELECT id, nome, email, '' as telefone, nivel_acesso as tipo, 'administradores' as tabela
        FROM administradores 
        WHERE email = ? AND senha = ? AND ativo = 1
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $senha, $email, $senha, $email, $senha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>