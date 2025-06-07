<?php
$title = 'Cadastro - Clínica Saúde Mais';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card mt-4 mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Cadastro de Usuário</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="userTypeTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="paciente-tab" data-bs-toggle="tab" data-bs-target="#paciente" type="button" role="tab" aria-controls="paciente" aria-selected="true">Paciente</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="medico-tab" data-bs-toggle="tab" data-bs-target="#medico" type="button" role="tab" aria-controls="medico" aria-selected="false">Médico</button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="userTypeTabContent">
                    <!-- Formulário de Paciente -->
                    <div class="tab-pane fade show active" id="paciente" role="tabpanel" aria-labelledby="paciente-tab">
                        <form id="pacienteForm" method="post" action="/pacientes">
                            <h5 class="mb-3">Dados Pessoais</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome_paciente" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome_paciente" name="nome" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email_paciente" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email_paciente" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cpf_paciente" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf_paciente" name="cpf" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="rg_paciente" class="form-label">RG</label>
                                    <input type="text" class="form-control" id="rg_paciente" name="rg" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="data_nascimento_paciente" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento_paciente" name="data_nascimento" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sexo_paciente" class="form-label">Sexo</label>
                                    <select class="form-select" id="sexo_paciente" name="sexo" required>
                                        <option value="">Selecione</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefone_paciente" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone_paciente" name="telefone" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">Endereço</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="logradouro_paciente" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro_paciente" name="logradouro" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="numero_paciente" class="form-label">Número</label>
                                    <input type="text" class="form-control" id="numero_paciente" name="numero" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bairro_paciente" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro_paciente" name="bairro" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cidade_paciente" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade_paciente" name="cidade" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="uf_paciente" class="form-label">UF</label>
                                    <select class="form-select" id="uf_paciente" name="uf" required>
                                        <option value="">Selecione</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">Informações Adicionais</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="metodo_pagamento_paciente" class="form-label">Método de Pagamento</label>
                                    <select class="form-select" id="metodo_pagamento_paciente" name="metodo_pagamento">
                                        <option value="">Selecione</option>
                                        <option value="Plano de Saúde">Plano de Saúde</option>
                                        <option value="Particular">Particular</option>
                                        <option value="Convênio">Convênio</option>
                                    </select>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">Senha</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="senha_paciente" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="senha_paciente" name="senha" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirmar_senha_paciente" class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" id="confirmar_senha_paciente" name="confirmar_senha" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Formulário de Médico -->
                    <div class="tab-pane fade" id="medico" role="tabpanel" aria-labelledby="medico-tab">
                        <form id="medicoForm" method="post" action="/medicos">
                            <h5 class="mb-3">Dados Pessoais</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome_medico" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome_medico" name="nome" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email_medico" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email_medico" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cpf_medico" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf_medico" name="cpf" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="rg_medico" class="form-label">RG</label>
                                    <input type="text" class="form-control" id="rg_medico" name="rg" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="data_nascimento_medico" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento_medico" name="data_nascimento" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sexo_medico" class="form-label">Sexo</label>
                                    <select class="form-select" id="sexo_medico" name="sexo" required>
                                        <option value="">Selecione</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefone_medico" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone_medico" name="telefone" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">Informações Profissionais</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="crm_medico" class="form-label">CRM</label>
                                    <input type="text" class="form-control" id="crm_medico" name="crm" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="matricula_medico" class="form-label">Matrícula</label>
                                    <input type="text" class="form-control" id="matricula_medico" name="matricula" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="especializacao_medico" class="form-label">Especialização</label>
                                    <input type="text" class="form-control" id="especializacao_medico" name="especializacao" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 mt-4">Senha</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="senha_medico" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="senha_medico" name="senha" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirmar_senha_medico" class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" id="confirmar_senha_medico" name="confirmar_senha" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Já tem uma conta? <a href="/auth/login">Faça login</a></p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$scripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formulário de Paciente
    const pacienteForm = document.getElementById('pacienteForm');
    pacienteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar senhas
        const senha = document.getElementById('senha_paciente').value;
        const confirmarSenha = document.getElementById('confirmar_senha_paciente').value;
        
        if (senha !== confirmarSenha) {
            alert('As senhas não coincidem.');
            return;
        }
        
        // Coletar dados do formulário
        const formData = new FormData(pacienteForm);
        const data = {};
        formData.forEach((value, key) => {
            if (key !== 'confirmar_senha') {
                data[key] = value;
            }
        });
        
        // Enviar requisição AJAX
        fetch('/api/pacientes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Cadastro realizado com sucesso! Faça login para continuar.');
                window.location.href = '/auth/login';
            } else {
                alert(data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar cadastrar. Tente novamente.');
        });
    });
    
    // Formulário de Médico
    const medicoForm = document.getElementById('medicoForm');
    medicoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar senhas
        const senha = document.getElementById('senha_medico').value;
        const confirmarSenha = document.getElementById('confirmar_senha_medico').value;
        
        if (senha !== confirmarSenha) {
            alert('As senhas não coincidem.');
            return;
        }
        
        // Coletar dados do formulário
        const formData = new FormData(medicoForm);
        const data = {};
        formData.forEach((value, key) => {
            if (key !== 'confirmar_senha') {
                data[key] = value;
            }
        });
        
        // Enviar requisição AJAX
        fetch('/api/medicos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Cadastro realizado com sucesso! Faça login para continuar.');
                window.location.href = '/auth/login';
            } else {
                alert(data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar cadastrar. Tente novamente.');
        });
    });
    
    // Máscaras para CPF e telefone
    function maskCPF(cpf) {
        return cpf
            .replace(/\D/g, '')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})/, '$1-$2')
            .replace(/(-\d{2})\d+?$/, '$1');
    }
    
    function maskPhone(phone) {
        return phone
            .replace(/\D/g, '')
            .replace(/(\d{2})(\d)/, '($1) $2')
            .replace(/(\d{5})(\d)/, '$1-$2')
            .replace(/(-\d{4})\d+?$/, '$1');
    }
    
    // Aplicar máscaras
    document.getElementById('cpf_paciente').addEventListener('input', function(e) {
        e.target.value = maskCPF(e.target.value);
    });
    
    document.getElementById('cpf_medico').addEventListener('input', function(e) {
        e.target.value = maskCPF(e.target.value);
    });
    
    document.getElementById('telefone_paciente').addEventListener('input', function(e) {
        e.target.value = maskPhone(e.target.value);
    });
    
    document.getElementById('telefone_medico').addEventListener('input', function(e) {
        e.target.value = maskPhone(e.target.value);
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

