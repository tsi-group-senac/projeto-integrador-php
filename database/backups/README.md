# Backups MySQL

## Arquivo pronto para a faculdade

```text
projeto_integrador_backup.sql
```

Contém **estrutura + dados** de demonstração (usuários, destinos, pacotes e reservas).

### Como importar (phpMyAdmin)

1. Abra `http://localhost/phpmyadmin`
2. **Importar** → escolha este arquivo → **Executar**
3. Confira o banco `projeto_integrador`

Passo a passo completo: [`.agents/SETUP_WINDOWS_XAMPP.md`](../../.agents/SETUP_WINDOWS_XAMPP.md).

### Como gerar um backup novo

```bash
# Linux / Docker do projeto
docker compose exec -T db mysqldump -uroot -proot --single-transaction projeto_integrador \
  > database/backups/projeto_integrador_backup.sql
```

No Windows/XAMPP:

```bat
C:\xampp\mysql\bin\mysqldump.exe -u root projeto_integrador > projeto_integrador_backup.sql
```

Não versionar dumps com dados pessoais reais.
