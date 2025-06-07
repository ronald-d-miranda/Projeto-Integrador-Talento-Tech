<?php

namespace App\Controller;

class HomeController
{
    /**
     * Página inicial da API
     * 
     * @return array Informações sobre a API
     */
    public function index()
    {
        return [
            'nome' => 'API Clínica Saúde Mais',
            'versao' => '1.0.0',
            'status' => 'online',
            'endpoints' => [
                '/auth/login' => 'Autenticação de usuários',
                '/users' => 'Gerenciamento de usuários',
                '/pacientes' => 'Gerenciamento de pacientes',
                '/medicos' => 'Gerenciamento de médicos',
                '/consultas' => 'Gerenciamento de consultas',
                '/exames' => 'Gerenciamento de exames'
            ]
        ];
    }
}

