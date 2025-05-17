<?php
session_start(); 
if (!isset($_SESSION['nome'])) {
    // Usuário não logado, redireciona
    header('Location: login.php');
    exit;
}
?>

<html>
    <head></head>
    <body>
        <div style="align-items: between">
            <h2>Tela inicial do Medico</h2>            
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h2> 
            <form action="logout.php" method="post">
                <button type="submit">Deslogar</button>
            </form>
        </div>

        <h2>Agenda do medico</h2>
        <hr>
        <table>
            <thead>
                <th>Data</th>
                <th>Paciente</th>
                <th>Presencial</th>
                <th>Ações</th>
            </thead>
            <tbody>
                <tr>
                    <td>12/08 09:00h</td>
                    <td>Maria da Silva</td>
                    <td>Não</td>
                    <td>
                        <button>Detalhar consulta</button>
                        <button>Consultar paciente</button>
                    </td>
                </tr>
                <tr>
                    <td>24/06 14:00h</td>
                    <td>Pedro Johnson</td>
                    <td>Não</td>
                    <td>
                        <button>Detalhar consulta</button>
                        <button>Consultar paciente</button>
                    </td>
                </tr>
                <tr>
                    <td>31/01 13:30h</td>
                    <td>Carina Folena</td>
                    <td>Sim</td>
                    <td>
                        <button>Detalhar consulta</button>
                        <button>Consultar paciente</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>