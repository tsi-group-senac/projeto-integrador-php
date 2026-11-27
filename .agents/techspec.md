# Especificação técnica

> **Implementação atual:** Destyn Trips — i18n (`pt_BR` / `en` / `es`), catálogo de destinos, página de roteiro, compra com validação de cartão por formato/tamanho, status `confirmed`, admin com clientes/reservas paginados.  
> **Setup:** [SETUP_WINDOWS_XAMPP.md](SETUP_WINDOWS_XAMPP.md) · backup em `database/backups/projeto_integrador_backup.sql`.

## Resumo

Esta solução será implementada em PHP puro, com páginas renderizadas no servidor, MySQL administrado pelo phpMyAdmin, Tailwind CSS e JavaScript apenas quando necessário. A organização seguirá o fluxo definido no README do projeto: `frontend/` para páginas e componentes, `backend/` para configuração, controladores, serviços e modelos, e `database/` para o esquema e seeds.

O domínio será composto por usuários, destinos, pacotes/roteiros e aquisições simuladas. A autenticação usará sessão PHP e senhas com `password_hash()`/`password_verify()`. O CRUD completo obrigatório será o de pacotes, enquanto clientes poderão consultar o próprio histórico e administradores terão acesso à gestão de pacotes, clientes e indicadores. Não haverá API financeira, pagamento, estoque ou integração externa.

## Arquitetura do sistema

### Visão dos componentes

- **Páginas públicas em `frontend/`**: landing page, catálogo, detalhes do pacote, cadastro e login.
- **Área do cliente em `frontend/pages/client/`**: resumo do perfil, histórico de aquisições e saída da sessão.
- **Área administrativa em `frontend/pages/admin/`**: dashboard, listagem de clientes e CRUD de pacotes.
- **Componentes compartilhados em `frontend/components/`**: cabeçalho, navegação, mensagens, cartões de roteiro e rodapé.
- **Tailwind CSS, CSS customizado e JavaScript em `frontend/css/` e `frontend/js/`**: identidade visual, responsividade, validações e interações não essenciais.
- **Configuração em `backend/config/`**: conexão PDO, sessão e constantes da aplicação. Credenciais locais ficarão em `database.php`, ignorado pelo Git.
- **Autenticação e autorização em `backend/services/`**: login, logout, proteção de sessão, verificação de papel e token CSRF.
- **Controladores em `backend/controllers/`**: recebem ações de páginas, validam entrada e delegam regras aos serviços.
- **Modelos em `backend/models/`**: consultas parametrizadas para `users`, `destinations`, `packages` e `purchases`.
- **Banco em `database/database.sql`, `database/migrations/` e `database/seeds/`**: tabelas, índices, chaves estrangeiras e dados demonstrativos.

Fluxo de dados:

```text
Navegador
  ↓ GET/POST
Página PHP / controlador
  ↓
Serviço de autenticação ou domínio
  ↓
Modelo PDO com prepared statements
  ↓
MySQL
```

As páginas renderizadas devem escapar qualquer dado textual com `htmlspecialchars()`. Operações que alteram estado usarão POST, CSRF e redirecionamento após sucesso (PRG).

## Design de implementação

### Principais interfaces

```text
AuthService
  login(email, password) -> AuthenticatedUser | ValidationError
  logout() -> void
  requireRole(role) -> void | AuthorizationError
```

```text
PackageService
  listActive() -> Package[]
  findById(id) -> Package | NotFoundError
  create(input, actorId) -> Package | ValidationError
  update(id, input, actorId) -> Package | ValidationError | NotFoundError
  delete(id, actorId) -> void | ConflictError | NotFoundError
```

```text
PurchaseService
  simulate(userId, packageId, csrfToken) -> Purchase | ValidationError | NotFoundError
  listByUser(userId) -> PurchaseWithPackage[]
  totals() -> PurchaseTotals
```

```text
CsrfService
  token() -> string
  validate(token) -> void | ValidationError
```

### Modelos de dados

#### `User` — usuário autenticável e proprietário do histórico

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `id` | `int` | sim | Identificador único. |
| `name` | `string` | sim | Nome exibido do usuário. |
| `email` | `string` | sim | E-mail único usado no login. |
| `password_hash` | `string` | sim | Hash gerado por `password_hash()`. Nunca é exibido. |
| `role` | `enum(admin, customer)` | sim | Papel de autorização. |
| `created_at` | `datetime` | sim | Data de criação. |
| `updated_at` | `datetime` | sim | Data da última atualização. |

```text
{
  "id": 7,
  "name": "Ana Souza",
  "email": "ana@example.test",
  "role": "customer",
  "created_at": "2026-11-07 10:00:00"
}
```

#### `Destination` — destino turístico do catálogo

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `id` | `int` | sim | Identificador único. |
| `name` | `varchar(120)` | sim | Nome do destino. |
| `description` | `text` | sim | Descrição apresentada ao visitante. |
| `image_path` | `varchar(255)` | não | Caminho local de imagem; `null` quando ausente. |
| `is_active` | `boolean` | sim | Indica se aparece no catálogo. |
| `created_at` | `datetime` | sim | Data de criação. |

```text
{
  "id": 1,
  "name": "Rio de Janeiro",
  "description": "Praias, cultura e paisagens urbanas.",
  "image_path": "/frontend/assets/images/rio.jpg",
  "is_active": true
}
```

#### `Package` — roteiro/pacote adquirido na simulação

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `id` | `int` | sim | Identificador único. |
| `destination_id` | `int` | sim | FK para `destinations.id`. |
| `title` | `varchar(160)` | sim | Nome comercial do roteiro. |
| `summary` | `varchar(255)` | sim | Resumo para cards e listagens. |
| `description` | `text` | sim | Detalhes do roteiro. |
| `duration_days` | `smallint` | sim | Duração positiva em dias. |
| `price` | `decimal(10,2)` | sim | Valor ilustrativo, sem cobrança. |
| `is_active` | `boolean` | sim | Disponibilidade no catálogo. |
| `created_at` | `datetime` | sim | Data de criação. |
| `updated_at` | `datetime` | sim | Data de atualização. |

```text
{
  "id": 12,
  "destination_id": 1,
  "title": "Rio Essencial",
  "summary": "Cinco dias entre praia e cultura.",
  "description": "Roteiro demonstrativo para a disciplina.",
  "duration_days": 5,
  "price": "1890.00",
  "is_active": true
}
```

#### `Purchase` — registro de aquisição simulada

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `id` | `int` | sim | Identificador único. |
| `user_id` | `int` | sim | FK para `users.id`; deve ser cliente. |
| `package_id` | `int` | sim | FK para `packages.id`. |
| `status` | `enum(confirmed)` | sim | Reserva confirmada (sem gateway real). |
| `purchased_at` | `datetime` | sim | Momento do registro. |

```text
{
  "id": 31,
  "user_id": 7,
  "package_id": 12,
  "status": "confirmed",
  "purchased_at": "2026-11-07 10:30:00"
}
```

> **Regra de histórico:** cada confirmação válida cria uma nova aquisição simulada. Não existe cobrança, reserva, estoque, cancelamento ou reembolso.

#### `ApiError` — formato interno de erro de formulário e operação

| Código | HTTP | Significado |
| --- | --- | --- |
| `validation_error` | `422` | Dados ausentes ou inválidos. |
| `unauthenticated` | `401` | Sessão ausente. |
| `forbidden` | `403` | Papel sem permissão. |
| `not_found` | `404` | Registro inexistente ou inativo. |
| `conflict` | `409` | Operação incompatível com o estado atual. |
| `server_error` | `500` | Falha inesperada registrada no servidor. |

```text
{
  "error": {
    "code": "validation_error",
    "message": "Revise os campos informados.",
    "fields": {
      "email": "Informe um e-mail válido."
    }
  }
}
```

#### Esquema relacional

```text
users (1) -------- (N) purchases (N) -------- (1) packages (N) -------- (1) destinations
```

- `users.email` é `UNIQUE`.
- `packages.destination_id`, `purchases.user_id` e `purchases.package_id` são chaves estrangeiras.
- Exclusão de destino ou pacote referenciado por uma aquisição deve ser bloqueada; o administrador deve desativar o registro (`is_active = 0`).
- Índices devem existir em `packages.destination_id`, `packages.is_active`, `purchases.user_id` e `purchases.package_id`.
- O schema deve usar `utf8mb4` e `utf8mb4_unicode_ci`, conforme o banco inicial do projeto.

### Endpoints da API

A aplicação não terá API REST pública. As rotas abaixo representam ações HTTP internas das páginas PHP e seus controladores, mantendo a arquitetura server-rendered exigida pelo README.

#### Visão geral

| Método | Rota | Descrição |
| --- | --- | --- |
| `GET` | `/frontend/index.php` | Landing page e pacotes ativos. |
| `GET` | `/frontend/pages/package.php?id={id}` | Detalhes de um pacote. |
| `GET/POST` | `/frontend/pages/register.php` | Cadastro de cliente. |
| `GET/POST` | `/frontend/pages/login.php` | Autenticação. |
| `POST` | `/frontend/pages/logout.php` | Encerramento da sessão. |
| `GET` | `/frontend/pages/client/dashboard.php` | Dashboard e histórico do cliente. |
| `POST` | `/frontend/pages/client/purchase.php` | Criação da aquisição simulada. |
| `GET` | `/frontend/pages/admin/index.php` | Indicadores administrativos. |
| `GET/POST` | `/frontend/pages/admin/packages.php` | Listagem e criação de pacotes. |
| `GET/POST` | `/frontend/pages/admin/package-form.php` | Criação/edição de pacote. |
| `POST` | `/frontend/pages/admin/package-delete.php` | Desativação de pacote. |
| `GET` | `/frontend/pages/admin/users.php` | Listagem e gerenciamento básico de clientes. |

---

#### `POST /frontend/pages/login.php`

Autentica um usuário existente.

**Corpo**

| Parâmetro | Tipo | Regras |
| --- | --- | --- |
| `email` | `string` | Obrigatório, formato de e-mail, normalizado para minúsculas. |
| `password` | `string` | Obrigatório; comparado somente com `password_verify()`. |
| `csrf_token` | `string` | Obrigatório e válido para a sessão. |

**Respostas**

| Status | Corpo | Quando |
| --- | --- | --- |
| `302` | — | Login válido; redireciona conforme o papel. |
| `422` | `ApiError` | Campos inválidos ou credenciais inválidas. |
| `500` | `ApiError` | Falha de banco ou inesperada. |

> Mensagens de credenciais inválidas não devem indicar se o e-mail existe. Após sucesso, regenerar o ID da sessão.

---

#### `POST /frontend/pages/register.php`

Cria um cliente, nunca um administrador.

**Corpo**

| Parâmetro | Tipo | Regras |
| --- | --- | --- |
| `name` | `string` | Obrigatório, 2 a 120 caracteres. |
| `email` | `string` | Obrigatório, válido e único. |
| `password` | `string` | Obrigatório, mínimo de 8 caracteres. |
| `password_confirmation` | `string` | Deve coincidir com `password`. |
| `csrf_token` | `string` | Obrigatório. |

**Respostas**

| Status | Corpo | Quando |
| --- | --- | --- |
| `302` | — | Cliente criado; redireciona ao login. |
| `422` | `ApiError` | Validação ou e-mail já cadastrado. |
| `500` | `ApiError` | Falha inesperada. |

---

#### `POST /frontend/pages/client/purchase.php`

Registra a aquisição simulada do pacote selecionado.

**Corpo**

| Parâmetro | Tipo | Regras |
| --- | --- | --- |
| `package_id` | `int` | Obrigatório, pacote existente e ativo. |
| `csrf_token` | `string` | Obrigatório e válido. |

**Respostas**

| Status | Corpo | Quando |
| --- | --- | --- |
| `302` | — | Registro criado; redireciona ao dashboard com sucesso. |
| `401` | `ApiError` | Sem sessão de cliente. |
| `403` | `ApiError` | Usuário não é cliente. |
| `404` | `ApiError` | Pacote inexistente ou inativo. |
| `422` | `ApiError` | Entrada inválida ou token inválido. |
| `500` | `ApiError` | Falha inesperada. |

> O servidor deve obter o `user_id` da sessão, nunca de um campo enviado pelo navegador. O preço exibido é apenas informativo e não deve iniciar integração financeira.

---

#### `POST /frontend/pages/admin/package-form.php`

Cria ou atualiza um pacote e constitui o CRUD principal.

**Corpo**

| Parâmetro | Tipo | Regras |
| --- | --- | --- |
| `id` | `int` | Obrigatório apenas na edição. |
| `destination_id` | `int` | Destino existente. |
| `title` | `string` | Obrigatório, 3 a 160 caracteres. |
| `summary` | `string` | Obrigatório, até 255 caracteres. |
| `description` | `string` | Obrigatório. |
| `duration_days` | `int` | Inteiro entre 1 e 365. |
| `price` | `decimal` | Zero ou positivo; valor ilustrativo. |
| `is_active` | `bool` | Controla exibição no catálogo. |
| `csrf_token` | `string` | Obrigatório. |

**Respostas**

| Status | Corpo | Quando |
| --- | --- | --- |
| `302` | — | Criado ou atualizado com sucesso. |
| `403` | `ApiError` | Usuário não é administrador. |
| `404` | `ApiError` | Pacote ou destino inexistente. |
| `422` | `ApiError` | Dados inválidos. |
| `409` | `ApiError` | Exclusão solicitada para pacote com histórico. |

> A operação “Delete” do CRUD será uma desativação lógica quando o pacote possuir histórico, preservando o histórico dos clientes.

## Pontos de integração

Não há integrações externas obrigatórias. O único ambiente de execução é Apache + PHP + MySQL via XAMPP, conforme o README e `docs/SETUP_XAMPP.md`.

- **Banco:** PDO com DSN configurável, `ERRMODE_EXCEPTION`, `utf8mb4` e prepared statements.
- **Tailwind CSS:** será carregado pelo mecanismo definido no setup do projeto e usado para utilitários de layout, tipografia, cores, estados de foco e breakpoints. A aplicação deve continuar utilizável sem JavaScript para ações essenciais.
- **Autenticação:** sessão PHP local; em ambiente real seriam necessários HTTPS e cookies seguros, mas o escopo acadêmico permanece local.
- **Falha de banco:** registrar detalhes no log do servidor e exibir mensagem genérica ao usuário; não expor SQL, credenciais ou stack trace.

## Abordagem de testes

Não existe framework de testes ou CI no projeto-base. A primeira entrega deve combinar scripts PHP básicos para regras puras, testes de integração manuais/automatizáveis contra MySQL local e roteiros E2E manuais no navegador. Os testes devem ser executados em ambiente XAMPP com banco de teste separado ou dados de seed restauráveis.

### Testes de unidade

| ID | Nome do caso de teste | Critérios de aceitação | Resultado esperado |
| --- | --- | --- | --- |
| TU-01 | Validar cadastro obrigatório | CA-03 | Campos ausentes e e-mail inválido retornam erros sem persistência. |
| TU-02 | Validar senha e confirmação | CA-03, CA-04 | Senhas diferentes falham; senha válida gera hash não reversível. |
| TU-03 | Validar pacote ativo | CA-06, CA-07 | Pacote inativo ou inexistente não pode ser adquirido. |
| TU-04 | Validar regras do CRUD de pacote | CA-10, CA-11 | Dados fora dos limites são rejeitados; papel de cliente não passa na autorização. |
| TU-05 | Validar token CSRF | CA-05, CA-07, CA-11 | Token ausente ou incorreto impede mutações. |

Componentes cobertos: validadores, `AuthService`, `CsrfService`, autorização e regras de `PackageService`/`PurchaseService`. O acesso ao MySQL deve ser substituído por doubles apenas nesses testes de unidade; não usar mocks para mascarar regras de domínio.

### Testes de integração

| ID | Nome do caso de teste | Critérios de aceitação | Resultado esperado |
| --- | --- | --- | --- |
| TI-01 | Criar e autenticar cliente | CA-03, CA-04 | Cliente é persistido com papel `customer` e login usa `password_verify()`. |
| TI-02 | Persistir pacote e destino relacionado | CA-01, CA-02, CA-10 | Pacote válido é lido com o destino correto e aparece no catálogo. |
| TI-03 | Registrar aquisição no usuário da sessão | CA-05, CA-06, CA-07, CA-08 | Aquisição usa o usuário autenticado, persiste e aparece no histórico. |
| TI-04 | Bloquear acesso administrativo | CA-11, CA-14 | Cliente e anônimo recebem redirecionamento/erro sem dados administrativos. |
| TI-05 | Preservar histórico ao desativar pacote | CA-08, CA-10 | Pacote deixa de aparecer no catálogo, mas aquisição anterior permanece consultável. |
| TI-06 | Calcular indicadores administrativos | CA-10 | Totais de clientes e aquisições correspondem ao banco. |

Componentes: PDO, modelos, serviços, sessões e banco MySQL. O banco deve ser recriado a partir de `database/database.sql` e seeds antes de cada execução controlada.

### Testes E2E

| ID | Nome do caso de teste | Critérios de aceitação | Resultado esperado |
| --- | --- | --- | --- |
| E2E-01 | Explorar landing e detalhes | CA-01, CA-02 | Visitante vê catálogo, abre detalhes e encontra ação de cadastro/login. |
| E2E-02 | Cadastrar e entrar como cliente | CA-03, CA-04 | Formulário inválido mostra erros; formulário válido permite login. |
| E2E-03 | Completar compra simulada | CA-05, CA-06, CA-07, CA-08 | Cliente escolhe pacote, vê aviso de simulação, confirma e encontra o histórico. |
| E2E-04 | Exibir estado vazio | CA-09 | Cliente recém-cadastrado vê orientação para consultar roteiros. |
| E2E-05 | Operar dashboard administrativo | CA-10, CA-11 | Administrador gerencia pacote e vê clientes/totais; cliente não acessa a área. |
| E2E-06 | Navegar por teclado e breakpoints | CA-12, CA-13 | Foco é visível, campos e ações são utilizáveis e o layout funciona em pelo menos 5 larguras. |

Os E2E podem ser executados manualmente no navegador disponível no ambiente, registrando navegador, largura testada, dados usados e resultado. O critério mínimo é validar 360px, 576px, 768px, 992px e 1200px.

## Sequenciamento do desenvolvimento

### Ordem de construção

1. Ajustar `database/database.sql`, migrations e seeds para usuários, destinos, pacotes e aquisições.
2. Implementar `database.php`, PDO, sessão, helpers de escape, mensagens e CSRF.
3. Implementar cadastro, login, logout e autorização por papel; é pré-requisito para as áreas protegidas.
4. Implementar modelos e serviços de destinos/pacotes e substituir a home placeholder pelo catálogo.
5. Implementar CRUD administrativo de pacotes e dashboard com métricas.
6. Implementar compra simulada e dashboard do cliente com histórico e estado vazio.
7. Aplicar componentes compartilhados, identidade visual, acessibilidade e cinco breakpoints.
8. Executar testes de unidade, integração e E2E; atualizar README e documentação de setup.

### Dependências técnicas

- XAMPP com Apache, PHP e MySQL ativos.
- phpMyAdmin para importar o schema inicial.
- Tailwind CSS configurado para o frontend. Como o projeto não possui Node.js, Composer ou processo de build no template, a primeira implementação pode usar o Play CDN em ambiente acadêmico local; se o projeto receber um build, deve migrar para o compilador Tailwind e gerar um CSS versionado/minificado.
- Credenciais locais copiadas de `backend/config/database.example.php` para `database.php`; o arquivo não deve ser versionado.
- Nenhuma integração externa de negócio ou pacote Composer obrigatório; o único recurso externo previsto é o carregamento do Tailwind em desenvolvimento, conforme a estratégia de setup.
- Seeds devem fornecer um administrador e dados de catálogo; a senha do seed deve ser definida por variável/localmente e não documentada em texto aberto.

## Monitoramento e observabilidade

O projeto acadêmico não possui plataforma de observabilidade. Deve haver:

- Log de falhas de conexão, exceções não tratadas, falhas de autenticação por erro interno e violações de autorização, sem senha ou dados sensíveis.
- Mensagens genéricas para o usuário em falhas internas.
- Indicadores administrativos de clientes e aquisições como métricas funcionais do domínio.
- Um health check manual `GET /frontend/index.php` e verificação de conexão ao banco no fluxo de setup.
- Registro manual dos resultados dos testes e do ambiente XAMPP no Pull Request, conforme os templates do projeto.

## Considerações técnicas

### Principais decisões

- **PHP server-rendered:** segue o README e reduz dependências para uma equipe acadêmica que usa XAMPP. Uma API REST foi descartada por não ser requisito e aumentaria a superfície de autenticação.
- **PDO e prepared statements:** reutiliza o padrão da conexão de exemplo e reduz risco de SQL injection.
- **Pacotes como CRUD principal:** satisfaz o requisito de CRUD completo e representa o catálogo central da agência.
- **Aquisição sem unicidade por usuário/pacote:** permite simular compras repetidas e manter o histórico simples; não existe regra de estoque ou reserva.
- **Desativação lógica:** preserva referências históricas e evita apagar dados exibidos no histórico.
- **Sessão PHP:** atende diretamente ao requisito do README; toda página protegida deve chamar o guard antes de renderizar dados.
- **Sem APIs externas:** mantém o escopo acadêmico e o requisito do PRD de não usar ferramentas externas.

### Riscos conhecidos

- O template não possui testes automatizados, middleware, logs centralizados ou framework; mitigação: helpers compartilhados, scripts de teste pequenos e checklist E2E.
- Credenciais padrão do XAMPP podem ser inseguras fora do ambiente local; mitigação: manter configuração fora do Git e documentar que a solução é acadêmica/local.
- Ausência de HTTPS no XAMPP reduz segurança de sessão; mitigação: não usar dados reais e recomendar HTTPS em qualquer implantação pública.
- O carregamento do Tailwind via CDN depende de rede e o Play CDN é apropriado apenas para desenvolvimento; mitigação: garantir HTML funcional sem JavaScript, avaliar o compilador Tailwind quando houver processo de build e considerar CSS gerado localmente para uso sem rede.
- Exclusão física de pacotes pode quebrar histórico; mitigação: desativação lógica e FK restritiva.
- Requisitos legais de acessibilidade são amplos; mitigação: aplicar checklist de teclado, foco, contraste, semântica, textos alternativos e testar os cinco breakpoints.

### Conformidade com o AGENTS.md e as rules

Foi verificada a raiz do projeto e não foram encontrados `AGENTS.md` nem arquivos em `.agents/rules/`. Portanto, não existem regras adicionais a aplicar. Esta especificação segue a arquitetura e as restrições documentadas em `project/README.md`, `project/docs/ARQUITETURA.md`, `project/docs/SETUP_XAMPP.md` e `project/database/database.sql`.

### Conformidade com skills

Não foram encontrados arquivos de skills aplicáveis em `.agents/skills/`. A especificação foi derivada da skill `processo/02-criar-techspec`, do PRD e das convenções do README do projeto. Não foram introduzidas dependências de skills externas.

### Arquivos relevantes e dependentes

- `project/README.md`
- `project/index.php`
- `project/frontend/index.php`
- `project/frontend/pages/login.php`
- `project/frontend/components/`
- `project/frontend/css/styles.css`
- `project/frontend/js/main.js`
- `project/backend/config/connection.example.php`
- `project/backend/config/database.example.php`
- `project/backend/controllers/`
- `project/backend/models/`
- `project/backend/services/`
- `project/database/database.sql`
- `project/database/migrations/`
- `project/database/seeds/`
- `project/docs/ARQUITETURA.md`
- `project/docs/SETUP_XAMPP.md`
- `project/docs/IDENTIDADE_VISUAL.md`
- `tasks/prd-agencia-de-viagens/prd.md`
