<html>
<head>
</head>
<body>
    <form method="POST" action="processa_registro.php">
        <h2>Cadastro</h2>

        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo">
            <option value="paciente">Paciente</option>
            <option value="medico">Médico</option>
        </select>

        <fieldset>
            <legend>Dados Pessoais</legend>
            <p>Nome: <input type="text" name="nome" required></p>
            <p>RG: <input type="text" name="rg" required></p>
            <p>CPF: <input type="text" name="cpf" required></p>
            <p>Data de Nascimento: <input type="date" name="dataNasc" required></p>
            <p>Sexo: 
                <select name="sexo" required>
                    <option value="">Selecione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                    <option value="O">Outro</option>
                </select>
            </p>
            <p>Email: <input type="email" name="email" required></p>
            <p>Senha: <input type="password" name="senha" required></p>
            <p>Telefone: <input type="text" name="telefone" required></p>
        </fieldset>

        <!-- Campos Paciente -->
        <div id="camposPaciente">
            <fieldset>
                <legend>Dados do Paciente</legend>
                <p>Método de Pagamento: <input type="text" name="metodo_pagamento"></p>
                <p>Logradouro: <input type="text" name="logradouro"></p>
                <p>Número: <input type="text" name="numero"></p>
                <p>Bairro: <input type="text" name="bairro"></p>
                <p>Cidade: <input type="text" name="cidade"></p>
                <p>UF: <input type="text" name="uf" maxlength="2"></p>
            </fieldset>
        </div>

        <!-- Campos Médico -->
        <div id="camposMedico" style="display:none;">
            <fieldset>
                <legend>Dados do Médico</legend>
                <p>Registro CRF: <input type="text" name="registro_crf"></p>
                <p>Matrícula: <input type="text" name="matricula"></p>
                <p>Especialização: <input type="text" name="especializacao"></p>
            </fieldset>
        </div>

        <input type="submit" value="Cadastrar">
    </form>

    <script>
        document.getElementById('tipo').addEventListener('change', function() {
            const isPaciente = this.value === 'paciente';
            document.getElementById('camposPaciente').style.display = isPaciente ? 'block' : 'none';
            document.getElementById('camposMedico').style.display = isPaciente ? 'none' : 'block';
        });
    </script>
</body>
</html>
