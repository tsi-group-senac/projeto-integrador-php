# Passo a passo — Windows + XAMPP

Marca: **Destyn Trips** (`destyntrips.com.br`). Stack: PHP + MySQL + Tailwind (CDN) no XAMPP.

## O que você precisa

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + phpMyAdmin + PHP 8+)
- Este repositório (clone Git **ou** pasta ZIP copiada para o PC da faculdade)
- Navegador

## 1. Instalar e ligar o XAMPP

1. Instale o XAMPP normalmente.
2. Abra o **XAMPP Control Panel**.
3. Clique em **Start** em **Apache** e em **MySQL**.
4. Confirme que ambos ficam verdes / Running.

## 2. Colocar o projeto no lugar certo

### Opção A — Git

No PowerShell ou Git Bash:

```bat
cd C:\xampp\htdocs
git clone https://github.com/tsi-group-senac/projeto-integrador-php.git
```

### Opção B — ZIP / pen drive

1. Copie a pasta do projeto para:

```text
C:\xampp\htdocs\projeto-integrador-php\
```

2. A estrutura correta tem estas pastas na raiz do projeto:

```text
C:\xampp\htdocs\projeto-integrador-php\
  backend\
  database\
  frontend\
  docs\
  .agents\
  index.php
```

> Se você trouxe a pasta `project/` de um monorepo, use o conteúdo de `project/` como raiz em `htdocs`.

## 3. Configurar a conexão com o MySQL

1. Copie o arquivo de exemplo:

```text
backend\config\database.example.php
  →  backend\config\database.php
```

No Explorador de Arquivos: copie `database.example.php` e renomeie a cópia para `database.php`.

2. Edite `backend\config\database.php` assim (padrão XAMPP sem senha no root):

```php
<?php

return [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'projeto_integrador',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
```

> Se o MySQL do seu XAMPP tiver senha no `root`, preencha `'password'`.  
> **Não** versionar `database.php` com senha real.

## 4. Importar o backup do banco (recomendado)

Este caminho já cria o banco **e** carrega usuários, pacotes e reservas de demonstração.

1. Abra o phpMyAdmin:

```text
http://localhost/phpmyadmin
```

2. No menu superior, clique em **Importar** (Import).
3. Em **Escolher arquivo**, selecione:

```text
C:\xampp\htdocs\projeto-integrador-php\database\backups\projeto_integrador_backup.sql
```

4. Deixe o formato como **SQL**.
5. Clique em **Executar** (Go) e aguarde a mensagem de sucesso.
6. No menu esquerdo, confirme que existe o banco `projeto_integrador` com as tabelas:
   - `users`
   - `destinations`
   - `packages`
   - `purchases`

### Se a importação falhar

- Pare o MySQL e inicie de novo no XAMPP.
- Se o banco já existir pela metade: no phpMyAdmin, selecione `projeto_integrador` → **Operações** → **Eliminar a base de dados**, depois importe o backup de novo.
- Alternativa (só estrutura, sem dados): importe `database\database.sql` e depois rode o seed (seção 5).

## 5. (Opcional) Seed pela linha de comando

Use se importou só o schema (`database.sql`) ou quiser regenerar senhas/datas demo.

Abra o **Prompt de Comando** ou PowerShell:

```bat
cd C:\xampp\htdocs\projeto-integrador-php
set DB_HOST=127.0.0.1
set DB_PORT=3306
set DB_NAME=projeto_integrador
set DB_USER=root
set DB_PASSWORD=
set SEED_ADMIN_PASSWORD=SenhaAdminDemo1
set SEED_CUSTOMER_PASSWORD=SenhaClienteDemo1
C:\xampp\php\php.exe database\seeds\seed.php
```

Mensagem esperada: `Seeds carregadas com sucesso.`

## 6. Abrir o projeto no navegador

Com Apache e MySQL ligados:

```text
http://localhost/projeto-integrador-php/
```

A entrada redireciona para o frontend. Páginas úteis:

| Área | URL |
| --- | --- |
| Home | `http://localhost/projeto-integrador-php/frontend/index.php` |
| Sobre | `.../frontend/pages/about.php` |
| Roteiros | `.../frontend/pages/tours.php` |
| Login | `.../frontend/pages/login.php` |
| Área do cliente | `.../frontend/pages/client/dashboard.php` |
| Admin | `.../frontend/pages/admin/index.php` |

## 7. Contas de demonstração

Após o backup (ou o seed), use:

| Papel | E-mail | Senha |
| --- | --- | --- |
| Admin | `thiago.barbosa@destyntrips.com` | `SenhaAdminDemo1` |
| Admin | `arthur.inacio@destyntrips.com` | `SenhaAdminDemo1` |
| Admin | `pedro.gomes@destyntrips.com` | `SenhaAdminDemo1` |
| Cliente | `marina.costa@destyntrips.com` | `SenhaClienteDemo1` |

Há outros 19 clientes seed com a mesma senha de cliente.

## 8. Exportar um backup novo (para levar para outro PC)

No phpMyAdmin:

1. Selecione o banco `projeto_integrador`.
2. Aba **Exportar**.
3. Método **Rápido**, formato **SQL**.
4. Clique em **Executar** e salve o `.sql`.
5. Leve esse arquivo junto com o código; no outro PC, repita a seção 4.

Pela linha de comando (se `mysqldump` estiver no PATH do XAMPP):

```bat
C:\xampp\mysql\bin\mysqldump.exe -u root projeto_integrador > projeto_integrador_backup.sql
```

## 9. Problemas comuns

| Sintoma | O que checar |
| --- | --- |
| Página em branco / 404 | Pasta está em `C:\xampp\htdocs\projeto-integrador-php\`? Apache ligado? |
| Erro de conexão com banco | `database.php` existe? Nome do banco `projeto_integrador`? MySQL ligado? |
| Login não funciona | Backup/seed foi importado? Senha correta da tabela acima? |
| CSS estranho | Precisa de internet (Tailwind Play CDN) no ambiente da faculdade |
| Porta 80 ocupada | Feche Skype/IIS ou altere a porta do Apache no XAMPP |

## 10. Checklist rápido na faculdade

1. [ ] Apache + MySQL Start  
2. [ ] Projeto em `htdocs\projeto-integrador-php\`  
3. [ ] `database.php` criado a partir do example  
4. [ ] Backup importado no phpMyAdmin  
5. [ ] Abrir `http://localhost/projeto-integrador-php/`  
6. [ ] Login admin ou cliente da tabela  
