<?php

namespace App\Auth;

class AuthMiddleware
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

     /**
     * Processa a requisição e verifica a autenticação.
     * Define o usuário autenticado no escopo global se o token for válido.
     * Redireciona ou retorna erro 401 se não autenticado.
     * 
     * @return array|null Retorna os dados do usuário se autenticado, ou um array de erro para API.
     */
    public function handleApiRequest()
    {
        $token = null;

        // Tentar obter o token do cabeçalho Authorization (para requisições AJAX)
        $headers = getallheaders();
        if (isset($headers["Authorization"])) {
            $authHeader = $headers["Authorization"];
            if (preg_match("/Bearer\s((.*)\\.(.*)\\.(.*))/",$authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        // Se não encontrou no cabeçalho, tentar obter do cookie (para requisições de página ou se o JS não enviou o header)
        if (!$token && isset($_COOKIE["jwt_token"])) {
            $token = $_COOKIE["jwt_token"];
        }

        if (!$token) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "Token de autenticação ausente."]);
            exit();
        }

        $validationResult = $this->authService->validarToken($token);

        if (!$validationResult["sucesso"]) {
            // Token inválido ou expirado, limpar o cookie
            setcookie("jwt_token", "", [
                "expires" => time() - 3600, // Expira no passado
                "path" => "/",
                "httponly" => true,
                "samesite" => "Lax",
                "secure" => isset($_SERVER["HTTPS"])
            ]);
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "Token inválido ou expirado."]);
            exit();
        }

        // Define o usuário autenticado para ser acessado pelos controladores
        return $validationResult["dados"];
    }

    /**
     * Processa a requisição e verifica a autenticação para rotas de visualização.
     * Redireciona para a página de login se não autenticado.
     */
    public function handleWebPageRequest()
    {
        $token = null;
        if (isset($_COOKIE["jwt_token"])) {
            $token = $_COOKIE["jwt_token"];
        }

        if (!$token) {
            header("Location: /auth/login");
            exit();
        }

        $validationResult = $this->authService->validarToken($token);

        if (!$validationResult["sucesso"]) {
            // Token inválido ou expirado, limpar o cookie e redirecionar
            setcookie("jwt_token", "", [
                "expires" => time() - 3600, // Expira no passado
                "path" => "/",
                "httponly" => true,
                "samesite" => "Lax",
                "secure" => isset($_SERVER["HTTPS"])
            ]);
            header("Location: /auth/login");
            exit();
        }

        return $validationResult["dados"];
    }

    /**
     * Verifica se o usuário está autenticado
     * 
     * @return array|null Dados do usuário autenticado ou null se não estiver autenticado
     */
    public function verificarAutenticacao()
    {
        // Obter o cabeçalho de autorização
        $authHeader = $this->getAuthorizationHeader();
        
        if (!$authHeader) {
            return null;
        }
        
        // Obter os dados do usuário a partir do token
        return $this->authService->obterUsuarioDoToken($authHeader);
    }

    /**
     * Verifica se o usuário tem permissão para acessar um recurso
     * 
     * @param string $tipoPermissao Tipo de permissão necessária (paciente ou medico)
     * @return bool|array True se o usuário tem permissão, array com erro caso contrário
     */
    public function verificarPermissao(string $tipoPermissao = '')
    {
        $usuario = $this->verificarAutenticacao();
        
        if (!$usuario) {
            return [
                'sucesso' => false,
                'mensagem' => 'Não autorizado. Faça login para continuar.',
                'status' => 401
            ];
        }
        
        if (!$this->authService->verificarPermissao($usuario, $tipoPermissao)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Acesso negado. Você não tem permissão para acessar este recurso.',
                'status' => 403
            ];
        }
        
        return true;
    }

    /**
     * Obtém o cabeçalho de autorização da requisição
     * 
     * @return string|null Cabeçalho de autorização ou null se não existir
     */
    private function getAuthorizationHeader()
    {
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
            
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }

    /**
     * Protege uma rota, verificando autenticação e permissão
     * 
     * @param string $tipoPermissao Tipo de permissão necessária (paciente ou medico)
     * @return array|null Erro se não autorizado, null se autorizado
     */
    public function protegerRota(string $tipoPermissao = '')
    {
        $resultado = $this->verificarPermissao($tipoPermissao);
        
        if ($resultado !== true) {
            header('HTTP/1.1 ' . $resultado['status'] . ' Unauthorized');
            echo json_encode($resultado);
            exit;
        }
        
        return null;
    }

    /**
     * Obtém o usuário autenticado
     * 
     * @return array|null Dados do usuário autenticado ou null se não estiver autenticado
     */
    public function getUsuarioAutenticado()
    {
        return $this->verificarAutenticacao();
    }
}

