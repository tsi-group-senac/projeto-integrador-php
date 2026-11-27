# Resultados de validação — Agência Horizonte

## Ambiente desta execução automatizada

- Host de desenvolvimento sem PHP/MySQL instalados no PATH no momento da implementação.
- Scripts preparados em `tests/unit/run.php`, `tests/integration/run.php` e checklist E2E em `tests/e2e/CHECKLIST.md`.
- Validação completa deve ser repetida no XAMPP da equipe conforme `docs/SETUP_XAMPP.md`.

## Matriz

| Camada | IDs | Status |
| --- | --- | --- |
| Unidade | TU-01 … TU-05 | Scripts prontos; executar com `php tests/unit/run.php` no XAMPP |
| Integração | TI-01 … TI-06 | Scripts prontos; executar com `php tests/integration/run.php` |
| E2E | E2E-01 … E2E-06 | Checklist manual nos cinco breakpoints |

## Limitações registradas

- Tailwind via Play CDN (ambiente acadêmico).
- Sem HTTPS no XAMPP; sessão acadêmica/local.
- E2E depende de navegador local da equipe.
