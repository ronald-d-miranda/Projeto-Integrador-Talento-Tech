<?php
$title = 'Meu Perfil - Sistema Médico';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Meu Perfil</h1>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Informações do Usuário</h5>
        </div>
        <div class="card-body">
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando dados do perfil...</p>
            </div>
            
            <div id="dadosPerfil" class="d-none">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Nome Completo</h6>
                        <p id="nome" class="lead"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>E-mail</h6>
                        <p id="email" class="lead"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6>CPF</h6>
                        <p id="cpf" class="lead"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6>RG</h6>
                        <p id="rg" class="lead"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6>Data de Nascimento</h6>
                        <p id="data_nascimento" class="lead"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Sexo</h6>
                        <p id="sexo" class="lead"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Telefone</h6>
                        <p id="telefone" class="lead"></p>
                    </div>
                </div>
                
                <div id="infoPaciente" class="d-none">
                    <hr>
                    <h6>Informações de Paciente</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Método de Pagamento</h6>
                            <p id="metodo_pagamento" class="lead"></p>
                        </div>
                    </div>
                </div>
                
                <div id="infoMedico" class="d-none">
                    <hr>
                    <h6>Informações de Médico</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6>CRM</h6>
                            <p id="crm" class="lead"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>Matrícula</h6>
                            <p id="matricula" class="lead"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>Especialização</h6>
                            <p id="especializacao" class="lead"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <button id="btnEditarPerfil" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Editar Perfil
        </button>
    </div>
    
    <!-- Formulário de edição (inicialmente oculto) -->
    <div id="formEdicaoPerfil" class="card mt-4 d-none">
        <div class="card-header">
            <h5 class="mb-0">Editar Perfil</h5>
        </div>
        <div class="card-body">
            <form id="editarPerfilForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="edit_nome" name="nome" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="edit_cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="edit_cpf" name="cpf" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_rg" class="form-label">RG</label>
                        <input type="text" class="form-control" id="edit_rg" name="rg" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="edit_data_nascimento" name="data_nascimento" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_sexo" class="form-label">Sexo</label>
                        <select class="form-select" id="edit_sexo" name="sexo" required>
                            <option value="">Selecione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="edit_telefone" name="telefone" required>
                    </div>
                </div>
                
                <div id="editInfoPaciente" class="d-none">
                    <hr>
                    <h6>Informações de Paciente</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_metodo_pagamento" class="form-label">Método de Pagamento</label>
                            <select class="form-select" id="edit_metodo_pagamento" name="metodo_pagamento">
                                <option value="">Selecione</option>
                                <option value="Plano de Saúde">Plano de Saúde</option>
                                <option value="Particular">Particular</option>
                                <option value="Convênio">Convênio</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div id="editInfoMedico" class="d-none">
                    <hr>
                    <h6>Informações de Médico</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edit_crm" class="form-label">CRM</label>
                            <input type="text" class="form-control" id="edit_crm" name="crm" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_matricula" class="form-label">Matrícula</label>
                            <input type="text" class="form-control" id="edit_matricula" name="matricula" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_especializacao" class="form-label">Especialização</label>
                            <input type="text" class="form-control" id="edit_especializacao" name="especializacao" required>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" id="btnCancelarEdicao" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$scripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('token');
    const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
    
    if (!token || !usuario || !usuario.id) {
        // Se não houver token ou o usuário não estiver no localStorage, redireciona
        window.location.href = '/auth/login';
        return;
    }
    
    const carregando = document.getElementById('carregando');
    const dadosPerfil = document.getElementById('dadosPerfil');
    const infoPaciente = document.getElementById('infoPaciente');
    const infoMedico = document.getElementById('infoMedico');
    const btnEditarPerfil = document.getElementById('btnEditarPerfil');
    const formEdicaoPerfil = document.getElementById('formEdicaoPerfil');
    const btnCancelarEdicao = document.getElementById('btnCancelarEdicao');
    const editarPerfilForm = document.getElementById('editarPerfilForm');
    const editInfoPaciente = document.getElementById('editInfoPaciente');
    const editInfoMedico = document.getElementById('editInfoMedico');    

    const usuarioId = usuario.id;
    const usuarioTipo = usuario.tipo;
    // Função para carregar dados do perfil
    function carregarPerfil() {  // Pega o id do usuário do localStorage
        fetch(`/api/users/` + usuarioId + "?tipo=" + usuarioTipo, {  // Usa o id para fazer a requisição correta
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            carregando.classList.add('d-none');
            
            if (data.sucesso && data.dados) {
                dadosPerfil.classList.remove('d-none');
                const perfil = data.dados;
                
                document.getElementById('nome').textContent = perfil.nome;
                document.getElementById('email').textContent = perfil.email;
                document.getElementById('cpf').textContent = perfil.cpf;
                document.getElementById('rg').textContent = perfil.rg;
                document.getElementById('data_nascimento').textContent = perfil.data_nascimento;
                document.getElementById('sexo').textContent = perfil.sexo;
                document.getElementById('telefone').textContent = perfil.telefone;
                
                // Preencher formulário de edição
                document.getElementById('edit_nome').value = perfil.nome;
                document.getElementById('edit_email').value = perfil.email;
                document.getElementById('edit_cpf').value = perfil.cpf;
                document.getElementById('edit_rg').value = perfil.rg;
                document.getElementById('edit_data_nascimento').value = perfil.data_nascimento;
                document.getElementById('edit_sexo').value = perfil.sexo;
                document.getElementById('edit_telefone').value = perfil.telefone;
                
                if (perfil.tipo === 'paciente') {
                    infoPaciente.classList.remove('d-none');
                    document.getElementById('metodo_pagamento').textContent = perfil.metodo_pagamento;
                    editInfoPaciente.classList.remove('d-none');
                    document.getElementById('edit_metodo_pagamento').value = perfil.metodo_pagamento;
                } else if (perfil.tipo === 'medico') {
                    infoMedico.classList.remove('d-none');
                    document.getElementById('crm').textContent = perfil.crm;
                    document.getElementById('matricula').textContent = perfil.matricula;
                    document.getElementById('especializacao').textContent = perfil.especializacao;
                    editInfoMedico.classList.remove('d-none');
                    document.getElementById('edit_crm').value = perfil.crm;
                    document.getElementById('edit_matricula').value = perfil.matricula;
                    document.getElementById('edit_especializacao').value = perfil.especializacao;
                }
            } else {
                alert('Erro ao carregar dados do perfil: ' + (data.mensagem || 'Perfil não encontrado.'));
                window.location.href = '/home';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar perfil:', error);
            carregando.classList.add('d-none');
            alert('Erro ao carregar dados do perfil. Tente novamente mais tarde.');
        });
    }
    
    carregarPerfil();
    
    // Botão Editar Perfil
    btnEditarPerfil.addEventListener('click', function() {
        dadosPerfil.classList.add('d-none');
        btnEditarPerfil.classList.add('d-none');
        formEdicaoPerfil.classList.remove('d-none');
    });
    
    // Botão Cancelar Edição
    btnCancelarEdicao.addEventListener('click', function() {
        formEdicaoPerfil.classList.add('d-none');
        dadosPerfil.classList.remove('d-none');
        btnEditarPerfil.classList.remove('d-none');
    });
    
    // Formulário de Edição
    editarPerfilForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const dadosAtualizados = {
            nome: document.getElementById('edit_nome').value,
            email: document.getElementById('edit_email').value,
            cpf: document.getElementById('edit_cpf').value,
            rg: document.getElementById('edit_rg').value,
            data_nascimento: document.getElementById('edit_data_nascimento').value,
            sexo: document.getElementById('edit_sexo').value,
            telefone: document.getElementById('edit_telefone').value,
        };
        
        if (usuario.tipo === 'paciente') {
            dadosAtualizados.metodo_pagamento = document.getElementById('edit_metodo_pagamento').value;
        } else if (usuario.tipo === 'medico') {
            dadosAtualizados.crm = document.getElementById('edit_crm').value;
            dadosAtualizados.matricula = document.getElementById('edit_matricula').value;
            dadosAtualizados.especializacao = document.getElementById('edit_especializacao').value;
        }
        
        fetch(`/api/users/` + usuarioId + "?tipo=" + usuarioTipo, {  // Atualiza o perfil com base no id do usuário
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(dadosAtualizados)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Perfil atualizado com sucesso!');
                // Atualizar dados no localStorage
                localStorage.setItem('usuario', JSON.stringify(data.dados));
                window.location.href = '/auth/login';
            } else {
                alert(data.mensagem || 'Erro ao atualizar perfil.');
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar perfil:', error);
            alert('Erro ao atualizar perfil. Tente novamente mais tarde.');
        });
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>
