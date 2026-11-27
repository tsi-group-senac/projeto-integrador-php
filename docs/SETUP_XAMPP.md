# Setup com XAMPP — Destyn Trips

> Guia completo (Windows, backup, contas demo): **[`.agents/SETUP_WINDOWS_XAMPP.md`](../.agents/SETUP_WINDOWS_XAMPP.md)**.

## Resumo rápido

1. Instale o XAMPP e inicie **Apache** + **MySQL**.
2. Coloque o projeto em `C:\xampp\htdocs\projeto-integrador-php\`.
3. Copie `backend/config/database.example.php` → `backend/config/database.php` (banco `projeto_integrador`, user `root`, senha vazia no XAMPP padrão).
4. No phpMyAdmin (`http://localhost/phpmyadmin`), importe:

```text
database/backups/projeto_integrador_backup.sql
```

5. Abra:

```text
http://localhost/projeto-integrador-php/
```

## Contas demo

| Papel | E-mail | Senha |
| --- | --- | --- |
| Admin | `thiago.barbosa@destyntrips.com` | `SenhaAdminDemo1` |
| Cliente | `marina.costa@destyntrips.com` | `SenhaClienteDemo1` |

## Alternativa sem backup

1. Importe `database/database.sql`
2. Rode `database/seeds/seed.php` com as variáveis `SEED_*` (ver `.agents/SETUP_WINDOWS_XAMPP.md`)

## Rotas principais

| Área | URL |
| --- | --- |
| Catálogo | `/frontend/index.php` |
| Login | `/frontend/pages/login.php` |
| Cadastro | `/frontend/pages/register.php` |
| Cliente | `/frontend/pages/client/dashboard.php` |
| Admin | `/frontend/pages/admin/index.php` |
| CRUD pacotes | `/frontend/pages/admin/packages.php` |
