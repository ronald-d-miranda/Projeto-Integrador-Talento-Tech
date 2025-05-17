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
            <h2>Tela inicial do Paciente</h2>                
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h2>
            <form action="logout.php" method="post">
                <button type="submit">Deslogar</button>
            </form>
        </div>


        <h2>Agenda do Paciente</h2>
        <hr>
        <table>
            <thead>
                <th>Data</th>
                <th>Medico</th>
                <th>Presencial</th>
            </thead>
            <tbody>
                <tr>
                    <td>12/08 09:00h</td>
                    <td>Dr. Jao</td>
                    <td>Não</td>
                </tr>
                <tr>
                    <td>24/06 14:00h</td>
                    <td>Dra. Maria</td>
                    <td>Não</td>
                </tr>
                <tr>
                    <td>31/01 13:30h</td>
                    <td>Dr. Claudio</td>
                    <td>Sim</td>
                </tr>
            </tbody>
        </table>

        <h2>Meus Exames</h2>
        <hr>
        <table>
            <thead>
                <th>Data</th>
                <th>Tipo</th>
                <th>Ações</th>
            </thead>
            <tbody>
                <tr>
                    <td>21/04 20:00h</td>
                    <td>Ressonancia Magentica</td>
                    <td>
                        <button>Consultar resultado</button>
                    </td>
                </tr>
                <tr>
                    <td>08/11 14:00h</td>
                    <td>Exame de Sangue</td>
                    <td>
                        <button>Consultar resultado</button>
                    </td>
                </tr>
                <tr>
                    <td>31/09 09:00h</td>
                    <td>Raio X</td>
                    <td>
                        <button>Consultar resultado</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2>Agendar Consulta</h2>
        <hr>
        <form action="agendar_consulta.php">
            <label for="medico">Selecione um medico</label>
            <select name="medico" id="medico">
                <option value="Dr. Jao">Dr. Jao</option>
                <option value="Dra. Marina">Dra. Marina</option>
                <option value="Dr. Carlos">Dr. Carlos</option>
            </select>
            <button>Verificar disponibilidade</button>
        </form>
    </body>
</html>