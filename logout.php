<?php
session_start(); // Inicia a sessão (caso ainda não tenha sido iniciada)

// Destroi todas as variáveis da sessão
$_SESSION = array();

// Se desejar destruir completamente a sessão, apague também o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão
session_destroy();

// Redireciona de volta para a página de login
header("Location: login.php");
exit;
?>
