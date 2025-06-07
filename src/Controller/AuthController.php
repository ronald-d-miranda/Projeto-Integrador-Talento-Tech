<?php

namespace App\Controller;

use App\Auth\AuthService;

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Realiza o login do usuário
     * 
     * @return array Resultado da operação com token JWT se bem-sucedido
     */
    public function login()
    {
        // Verificar se a requisição é POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return [
                "sucesso" => false,
                "mensagem" => "Método não permitido. Use POST para login."
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents("php://input"), true);

        // Validar dados obrigatórios
        if (!isset($dados["credencial"]) || !isset($dados["senha"])) {
            return [
                "sucesso" => false,
                "mensagem" => "Credencial e senha são obrigatórios."
            ];
        }

        // Realizar login
        $result = $this->authService->login($dados["credencial"], $dados["senha"]);

        if ($result["sucesso"]) {
            // Definir o token JWT em um cookie HTTP-only
            // O tempo de expiração do cookie deve ser o mesmo do token JWT
            $expiration = time() + (int)$_ENV["JWT_EXPIRATION"];
            setcookie("jwt_token", $result["token"], [
                "expires" => $expiration,
                "path" => "/",
                "httponly" => true, // Importante para segurança
                "samesite" => "Lax", // Proteção CSRF
                "secure" => isset($_SERVER["HTTPS"]) // Apenas em HTTPS em produção
            ]);
        }

        return $result;
    }

    /**
     * Verifica se um token JWT é válido
     * 
     * @return array Resultado da validação
     */
    public function verificarToken()
    {
        // Verificar se a requisição é POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return [
                "sucesso" => false,
                "mensagem" => "Método não permitido. Use POST para verificar token."
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents("php://input"), true);

        // Validar dados obrigatórios
        if (!isset($dados["token"])) {
            return [
                "sucesso" => false,
                "mensagem" => "Token é obrigatório."
            ];
        }

        // Verificar token
        return $this->authService->validarToken($dados["token"]);
    }

    /**
     * Realiza o logout do usuário
     * 
     * @return array Resultado da operação
     */
    public function logout()
    {
        // Limpar o cookie do token JWT
        setcookie("jwt_token", "", [
            "expires" => time() - 3600, // Expira no passado
            "path" => "/",
            "httponly" => true,
            "samesite" => "Lax",
            "secure" => isset($_SERVER["HTTPS"])
        ]);

        return [
            "sucesso" => true,
            "mensagem" => "Logout realizado com sucesso."
        ];
    }
}
