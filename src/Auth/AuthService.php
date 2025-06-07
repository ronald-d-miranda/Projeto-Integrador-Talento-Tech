<?php

namespace App\Auth;

use App\Model\Pessoa;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthService
{
    private $secretKey;
    private $expiration;

    public function __construct()
    {
        // Adicionar verificação para garantir que as variáveis de ambiente estão carregadas
        if (!isset($_ENV["JWT_SECRET"]) || !isset($_ENV["JWT_EXPIRATION"])) {
            throw new Exception("Variáveis de ambiente JWT_SECRET ou JWT_EXPIRATION não definidas.");
        }
        $this->secretKey = $_ENV["JWT_SECRET"];
        $this->expiration = (int)$_ENV["JWT_EXPIRATION"];
    }

    /**
     * Realiza o login do usuário
     * 
     * @param string $credencial Email ou CPF do usuário
     * @param string $senha Senha do usuário
     * @return array Resultado da operação com token JWT se bem-sucedido
     */
    public function login(string $credencial, string $senha)
    {
        try {
            $pessoa = new Pessoa();
            $autenticado = false;
            
            // Verificar se a credencial é um email
            if (filter_var($credencial, FILTER_VALIDATE_EMAIL)) {
                $autenticado = $pessoa->findByEmail($credencial);
            } else {
                // Caso contrário, assume que é um CPF
                $autenticado = $pessoa->findByCpf($credencial);
            }
            
            if (!$autenticado) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Credenciais inválidas.'
                ];
            }
            
            // Verificar a senha
            if (!$pessoa->verificarSenha($senha)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Credenciais inválidas.'
                ];
            }
            
            // Gerar token JWT
            $token = $this->gerarToken([
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'email' => $pessoa->getEmail(),
                'tipo' => $pessoa->getTipo()
            ]);
            
            return [
                'sucesso' => true,
                'mensagem' => 'Login realizado com sucesso.',
                'token' => $token,
                'usuario' => [
                    'id' => $pessoa->getId(),
                    'nome' => $pessoa->getNome(),
                    'email' => $pessoa->getEmail(),
                    'tipo' => $pessoa->getTipo()
                ]
            ];
        } catch (Exception $e) {
            // Logar a exceção completa para depuração
            error_log("Erro no login: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao realizar login: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Gera um token JWT
     * 
     * @param array $dados Dados a serem incluídos no token
     * @return string Token JWT gerado
     */
    private function gerarToken(array $dados)
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->expiration;
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $dados
        ];
        
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * Valida um token JWT
     * 
     * @param string $token Token JWT a ser validado
     * @return array Resultado da validação com dados do usuário se bem-sucedido
     */
    public function validarToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            
            return [
                'sucesso' => true,
                'dados' => (array)$decoded->data
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Token inválido ou expirado.'
            ];
        }
    }

    /**
     * Verifica se o usuário tem permissão para acessar um recurso
     * 
     * @param array $usuario Dados do usuário
     * @param string $tipoPermissao Tipo de permissão necessária (paciente ou medico)
     * @return bool True se o usuário tem permissão, False caso contrário
     */
    public function verificarPermissao(array $usuario, string $tipoPermissao)
    {
        // Se o tipo de permissão for vazio, qualquer usuário autenticado pode acessar
        if (empty($tipoPermissao)) {
            return true;
        }
        
        // Verificar se o usuário tem o tipo necessário
        return $usuario['tipo'] === $tipoPermissao;
    }

    /**
     * Obtém os dados do usuário a partir do cabeçalho de autorização
     * 
     * @param string $authHeader Cabeçalho de autorização
     * @return array|null Dados do usuário ou null se o token for inválido
     */
    public function obterUsuarioDoToken(string $authHeader)
    {
        if (empty($authHeader)) {
            return null;
        }
        
        // Extrair o token do cabeçalho
        $token = str_replace('Bearer ', '', $authHeader);
        
        // Validar o token
        $resultado = $this->validarToken($token);
        
        if ($resultado['sucesso']) {
            return $resultado['dados'];
        }
        
        return null;
    }
}


