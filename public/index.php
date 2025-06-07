<?php
/**
 * Ponto de entrada da aplicação
 */

// Habilitar exibição de erros para depuração
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Carregar o autoloader do Composer
require_once __DIR__ . "/../vendor/autoload.php";

// Carregar variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

use App\Auth\AuthService;
use App\Auth\AuthMiddleware;

// Definir o tipo de conteúdo padrão como HTML
header("Content-Type: text/html; charset=UTF-8");

// Obter a URI da requisição
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (strpos($uri, '/img/') === 0) {
    // Caminho absoluto da pasta de imagens
    $imagePath = __DIR__ . '/../img' . substr($uri, 4); // Remover "/img" da URI e buscar o arquivo na pasta 'img'

    // Verificar se o arquivo existe
    if (file_exists($imagePath)) {
        // Obter o tipo MIME do arquivo
        $mimeType = mime_content_type($imagePath);

        // Definir o cabeçalho apropriado para o tipo de imagem
        header("Content-Type: " . $mimeType);
        header("Content-Length: " . filesize($imagePath));

        // Ler o arquivo e enviá-lo para o cliente
        readfile($imagePath);
        exit(); // Termina a execução para não processar outras rotas
    } else {
        // Arquivo não encontrado
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Imagem não encontrada"]);
        exit();
    }
}
$usuario = null;
$authService = new AuthService();
$authMiddleware = new AuthMiddleware();

// Função auxiliar para renderizar views
function renderView($viewPath, $data = []) {
    extract($data);
    require $viewPath;
}

// --- Roteamento de Páginas (Views) ---
// Rotas que exigem autenticação
$protectedWebRoutes = [
    "/home",
    "/consultas",
    "/consultas/agendar",
    "/exames",
    "/exames/novo",
    "/pacientes",
    "/medicos",
    "/perfil"
];

// Verificar se a rota atual é uma rota de página protegida
if (in_array($uri, $protectedWebRoutes)) {
    $usuario = $authMiddleware->handleWebPageRequest();
}


switch ($uri) {
    case "/":
    case "/home":
        renderView(__DIR__ . "/../src/View/home/index.php", ["usuario" => $usuario]);
        break;
    case "/auth/login":
        renderView(__DIR__ . "/../src/View/auth/login.php", ["usuario" => $usuario]);
        break;
    case "/auth/register":
        renderView(__DIR__ . "/../src/View/auth/register.php", ["usuario" => $usuario]);
        break;
    case "/consultas":
        renderView(__DIR__ . "/../src/View/consultas/index.php", ["usuario" => $usuario]);
        break;
    case "/consultas/agendar":
        renderView(__DIR__ . "/../src/View/consultas/agendar.php", ["usuario" => $usuario]);
        break;
    case "/exames":
        renderView(__DIR__ . "/../src/View/exames/index.php", ["usuario" => $usuario]);
        break;
    case "/exames/novo":
        renderView(__DIR__ . "/../src/View/exames/novo.php", ["usuario" => $usuario]);
        break;
    case "/pacientes":
        renderView(__DIR__ . "/../src/View/pacientes/index.php", ["usuario" => $usuario]);
        break;
    case "/medicos":
        renderView(__DIR__ . "/../src/View/medicos/index.php", ["usuario" => $usuario]);
        break;
    case "/perfil":
        renderView(__DIR__ . "/../src/View/users/profile.php", ["usuario" => $usuario]);
        break;
    case "/auth/logout":
        // Endpoint de logout
        $authController = new App\Controller\AuthController();
        $authController->logout();
        header("Location: /auth/login");
        exit();

    
    // --- Roteamento de API ---
    default:
        // Configurar cabeçalhos para CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        
        // Se for uma requisição OPTIONS, retornar apenas os cabeçalhos
        if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            exit(0);
        }
        
        // Obter a URI da requisição para a API
        $apiUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $apiUriSegments = explode("/", trim($apiUri, "/")); // Trim slashes and explode

        // Remove "api" segment if present (e.g., /api/auth/login)
        if (isset($apiUriSegments[0]) && $apiUriSegments[0] === "api") {
            array_shift($apiUriSegments);
        }
        
        $controllerName = isset($apiUriSegments[0]) ? $apiUriSegments[0] : "";
        $actionName = isset($apiUriSegments[1]) ? $apiUriSegments[1] : "";
        $params = array_slice($apiUriSegments, 2);

        // Mapear as rotas para os controladores da API
        $controllerMap = [
            "users" => "App\\Controller\\UserController",
            "pacientes" => "App\\Controller\\PacienteController",
            "medicos" => "App\\Controller\\MedicoController",
            "consultas" => "App\\Controller\\ConsultaController",
            "exames" => "App\\Controller\\ExameController",
            "auth" => "App\\Controller\\AuthController",
            "" => "App\\Controller\\HomeController" // Para o dashboard da API (se a URI for apenas /api/)
        ];

        // Rotas de API que exigem autenticação
        $protectedApiRoutes = [
            "consultas",
            "exames"
        ];

        // Check if controller exists
        if (!isset($controllerMap[$controllerName])) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "API Controller not found"]);
            exit();
        }

        $controllerClass = $controllerMap[$controllerName];
        $controllerInstance = new $controllerClass();
        $httpMethod = $_SERVER["REQUEST_METHOD"];
        $result = [];

        try {
            // Specific routing for MedicoController
            if ($controllerName === "medicos" && $httpMethod === "GET") {
                // /api/medicos/2/disponibilidade
                if (count($apiUriSegments) === 3 && is_numeric($apiUriSegments[1]) && $apiUriSegments[2] === "disponibilidade") {
                    $id = $apiUriSegments[1];
                    $result = $controllerInstance->disponibilidade($id);
                    echo json_encode($result);
                    exit();
                }
                // /api/medicos/2/consultas  
                elseif (count($apiUriSegments) === 3 && is_numeric($apiUriSegments[1]) && $apiUriSegments[2] === "consultas") {
                    $id = $apiUriSegments[1];
                    $result = $controllerInstance->consultas($id);
                    echo json_encode($result);
                    exit();
                }
                // /api/medicos/2/historico/3
                elseif (count($apiUriSegments) === 4 && is_numeric($apiUriSegments[1]) && $apiUriSegments[2] === "historico" && is_numeric($apiUriSegments[3])) {
                    $medicoId = $apiUriSegments[1];
                    $pacienteId = $apiUriSegments[3];
                    $result = $controllerInstance->historicoPaciente($medicoId, $pacienteId);
                    echo json_encode($result);
                    exit();
                }
            }
            // Specific routing for AuthController
            if ($controllerName === "auth") {
                switch ($actionName) {
                    case "login":
                        if ($httpMethod === "POST") {
                            $result = $controllerInstance->login();
                        } else {
                            header("HTTP/1.1 405 Method Not Allowed");
                            echo json_encode(["error" => "Method not allowed for /auth/login"]);
                            exit();
                        }
                        break;
                    case "verificarToken":
                        if ($httpMethod === "POST") {
                            $result = $controllerInstance->verificarToken();
                        } else {
                            header("HTTP/1.1 405 Method Not Allowed");
                            echo json_encode(["error" => "Method not allowed for /auth/verificarToken"]);
                            exit();
                        }
                        break;
                    default:
                        header("HTTP/1.1 404 Not Found");
                        echo json_encode(["error" => "Auth API action not found"]);
                        exit();
                }
            } else {
                // Verificar se a rota de API atual é protegida e autenticar
                if (in_array($controllerName, $protectedApiRoutes)) {
                    $authenticatedUser = $authMiddleware->handleApiRequest();
                    // Se o controlador tiver o método setAuthenticatedUser, passe o usuário
                    if (method_exists($controllerInstance, 'setAuthenticatedUser')) {
                        $controllerInstance->setAuthenticatedUser($authenticatedUser);
                    }
                }

                // Generic CRUD routing for other controllers
                switch ($httpMethod) {
                    case "GET":
                        if (empty($actionName)) { // e.g., /users
                            $result = $controllerInstance->index();
                        } elseif (count($params) === 0) { // e.g., /users/123
                            $result = $controllerInstance->read($actionName); // $actionName is the ID here
                        } else { // e.g., /medicos/1/consultas
                            $methodName = $actionName;
                            if (method_exists($controllerInstance, $methodName)) {
                                $result = call_user_func_array([$controllerInstance, $methodName], $params);
                            } else {
                                header("HTTP/1.1 404 Not Found");
                                echo json_encode(["error" => "API method not found"]);
                                exit();
                            }
                        }
                        break;
                    case "POST":
                        $result = $controllerInstance->create();
                        break;
                    case "PUT":
                        if (empty($actionName)) {
                            header("HTTP/1.1 400 Bad Request");
                            echo json_encode(["error" => "ID required for update"]);
                            exit();
                        }
                        $result = $controllerInstance->update($actionName);
                        break;
                    case "DELETE":
                        if (empty($actionName)) {
                            header("HTTP/1.1 400 Bad Request");
                            echo json_encode(["error" => "ID required for deletion"]);
                            exit();
                        }
                        $result = $controllerInstance->delete($actionName);
                        break;
                    default:
                        header("HTTP/1.1 405 Method Not Allowed");
                        echo json_encode(["error" => "Method not allowed"]);
                        exit();
                }
            }
            echo json_encode($result);
        } catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => $e->getMessage()]);
            exit();
        }
        break;
}
?>

