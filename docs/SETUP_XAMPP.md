# Setup com XAMPP — Agência Horizonte

> Este documento orienta a execução local do projeto com Apache, PHP, MySQL e phpMyAdmin.

## 1. Instalação

Instale o XAMPP e confirme que o **Apache** e o **MySQL** iniciam normalmente.

## 2. Local do projeto

No Windows, mantenha o repositório dentro de:

```text
C:\xampp\htdocs\projeto-integrador-php\
```

## 3. Abrindo o projeto

Com Apache ativo:

```text
http://localhost/projeto-integrador-php/
```

A entrada redireciona para `frontend/`.

## 4. Banco de dados

1. Abra `http://localhost/phpmyadmin`
2. Importe `database/database.sql` (cria o banco `projeto_integrador`)
3. Copie `backend/config/database.example.php` para `backend/config/database.php`
4. Ajuste host, porta, usuário e senha do MySQL local
5. Execute as seeds (senhas apenas por variáveis de ambiente):

```bash
cd C:\xampp\htdocs\projeto-integrador-php
set SEED_ADMIN_PASSWORD=SenhaAdminDemo1
set SEED_CUSTOMER_PASSWORD=SenhaClienteDemo1
php database/seeds/seed.php
```

Contas geradas:

- Admins: `thiago.barbosa@destyntrips.com`, `arthur.inacio@destyntrips.com`, `pedro.gomes@destyntrips.com`
- Cliente: `marina.costa@destyntrips.com` (e outros 19 clientes seed)

## 5. Tailwind CSS

No ambiente acadêmico local, o frontend carrega o **Tailwind Play CDN**. A aplicação permanece utilizável sem JavaScript para ações essenciais (formulários POST, navegação e logout).

## 6. Testes

Unidade (não exige MySQL):

```bash
php tests/unit/run.php
```

Integração (exige MySQL e `database.php`):

```bash
php tests/integration/run.php
```

E2E manual: seguir `tests/e2e/CHECKLIST.md` nos breakpoints 360, 576, 768, 992 e 1200px.

## 7. Rotas principais

| Área | URL |
| --- | --- |
| Catálogo | `/frontend/index.php` |
| Login | `/frontend/pages/login.php` |
| Cadastro | `/frontend/pages/register.php` |
| Cliente | `/frontend/pages/client/dashboard.php` |
| Admin | `/frontend/pages/admin/index.php` |
| CRUD pacotes | `/frontend/pages/admin/packages.php` |
