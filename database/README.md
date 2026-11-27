# Banco de Dados

> Documente nesta pasta a estrutura MySQL do projeto e a forma correta de versioná-la.

## Para que serve

Use esta pasta como referência para o modelo de dados da equipe, para a exportação do SQL e para a evolução do banco ao longo da disciplina.

Utilize esta pasta para versionar a estrutura do banco MySQL do projeto.

## Arquivo principal

```text
database.sql
```

Ele permite criar a estrutura do banco utilizando o phpMyAdmin. O schema usa
`utf8mb4`, chaves estrangeiras restritivas e desativação lógica por meio de
`is_active` para destinos e pacotes.

## Seeds demonstrativas

Após importar `database.sql`, execute `database/seeds/seed.php` pela linha de
comando. As senhas são obrigatoriamente fornecidas por variáveis de ambiente e
transformadas em hash com `password_hash()` durante a execução; nenhuma senha
fica versionada no projeto.

Exemplo no Linux/macOS:

```bash
DB_HOST=127.0.0.1 DB_PORT=3306 DB_NAME=projeto_integrador \
DB_USER=root DB_PASSWORD="$DB_PASSWORD" \
SEED_ADMIN_PASSWORD="$SEED_ADMIN_PASSWORD" \
SEED_CUSTOMER_PASSWORD="$SEED_CUSTOMER_PASSWORD" php database/seeds/seed.php
```

Substitua os valores de conexão conforme o ambiente local e nunca use senhas
reais nos dados demonstrativos.

## Regras

- Não exporte dados pessoais reais;
- Não adicione senhas em texto puro;
- Utilize chaves primárias;
- Utilize chaves estrangeiras nos relacionamentos quando aplicável;
- Mantenha o SQL atualizado quando a estrutura do banco mudar.
