# 🎓 EAD SENAI - Plataforma de Ensino a Distância

Uma plataforma LMS (Learning Management System) desenvolvida do zero para oferecer uma experiência de aprendizado moderna, fluida e interativa. O sistema permite a gestão completa de cursos, módulos e aulas, além de rastrear o progresso individual de cada aluno com cálculos dinâmicos em tempo real.

## 🚀 Funcionalidades Principais

* **Área do Aluno (Mochila):** Dashboard exclusivo para os alunos acessarem os cursos em que estão matriculados.
* **Player de Vídeo Inteligente:** Conversão automática de qualquer formato de link do YouTube para `iframe` incorporado através de Expressões Regulares (Regex).
* **Rastreamento de Progresso:** Barra de progresso dinâmica (%) e sistema de toggle (liga/desliga) para marcar aulas como concluídas, salvo diretamente no banco de dados.
* **Navegação Contínua:** Lógica avançada de "Próxima Aula" que identifica automaticamente o fim de um módulo e direciona o aluno para a primeira aula do módulo seguinte.
* **Painel Administrativo:** CRUD completo para criação e edição de cursos, módulos e aulas, sem necessidade de manipulação manual do banco de dados.

## 🛠️ Tecnologias Utilizadas

* **Linguagem Principal:** PHP
* **Banco de Dados:** MySQL (Arquitetura relacional com tabelas de usuários, cursos, módulos, aulas e histórico de conclusão)
* **Estilização:** HTML5 e Tailwind CSS (via CDN)

## 🗺️ Roadmap (Próximos Passos)

O sistema está em constante evolução para se tornar um produto SaaS completo. As próximas implementações incluem:

- [ ] Lógica de auto-conclusão usando a YouTube Iframe API (aula marcada como concluída assim que o vídeo acaba).
- [ ] Sistema de Autenticação com o Google (Google Auth).
- [ ] Verificação de segurança de dois fatores via E-mail (PHPMailer) com envio de código de confirmação.
- [ ] Deploy completo da aplicação web em um servidor/VPS.

## 💻 Como Rodar o Projeto Localmente

1. Clone este repositório na sua máquina:
   `git clone https://github.com/seu-usuario/nome-do-repositorio.git`
2. Mova a pasta do projeto para o diretório raiz do seu servidor local (ex: `htdocs` se estiver usando o XAMPP).
3. Importe o banco de dados via phpMyAdmin (arquivo `.sql` disponibilizado no repositório).
4. Configure as credenciais do seu banco de dados no arquivo `includes/conexao.php`.
5. Acesse o projeto no seu navegador em `http://localhost/nome-da-pasta`.

---
**Desenvolvido por Victor Aires** 🔗 [Conecte-se comigo no LinkedIn](https://www.linkedin.com/in/victor-aires-93621636a)
