# Documento de Requisitos do Produto (PRD)

> **Produto entregue:** Destyn Trips (`destyntrips.com.br`) — PHP + MySQL + Tailwind.  
> **Setup em outro PC:** ver [SETUP_WINDOWS_XAMPP.md](SETUP_WINDOWS_XAMPP.md).  
> Este PRD registra o escopo acadêmico original; a UI pública usa linguagem de reserva/compra (sem textos de “simulação”).

## Visão geral

O produto será um site acadêmico que simula uma agência de viagens. A landing page apresentará destinos turísticos e roteiros disponíveis para que visitantes conheçam as opções e clientes cadastrados possam simular a aquisição de um pacote de viagem.

Após a simulação, o cliente poderá consultar os roteiros associados ao seu perfil em uma área de clientes. Um dashboard administrativo permitirá gerenciar clientes e acompanhar indicadores simples, como o total de pacotes adquiridos. O produto não realizará transações financeiras reais; seu objetivo é demonstrar, de forma simples e navegável, a descoberta de roteiros, o cadastro, o fluxo de compra simulada e o acompanhamento de viagens.

## Objetivos

- Atender aos requisitos acadêmicos definidos pelo professor para uma aplicação que simule uma agência de viagens.
- Disponibilizar uma landing page com destinos e roteiros compreensíveis para visitantes.
- Permitir que um cliente válido conclua uma simulação de aquisição de roteiro sem pagamento real.
- Registrar cada roteiro adquirido no perfil do cliente e exibir seu histórico de viagens.
- Oferecer ao administrador uma visão dos clientes cadastrados e do total de pacotes adquiridos.
- Aplicar recomendações mínimas de acessibilidade digital previstas na legislação brasileira.
- Garantir uma experiência responsiva em pelo menos cinco breakpoints.

## Histórias de usuário

- US1: Como visitante, quero visualizar destinos e roteiros turísticos para conhecer as opções oferecidas pela agência.
- US2: Como visitante, quero consultar os detalhes de um roteiro para decidir se ele atende às minhas necessidades.
- US3: Como visitante, quero realizar meu cadastro para obter acesso à área de clientes.
- US4: Como cliente cadastrado e válido, quero escolher um roteiro e confirmar uma compra simulada para registrar a viagem no meu perfil.
- US5: Como cliente, quero visualizar os roteiros que já adquiri para acompanhar meu histórico de viagens.
- US6: Como cliente, quero consultar meus dados cadastrais e o resumo das minhas viagens em um dashboard.
- US7: Como administrador, quero visualizar e gerenciar os clientes cadastrados para manter a base da agência organizada.
- US8: Como administrador, quero consultar o total de pacotes adquiridos para acompanhar os resultados simulados da agência.
- US9: Como usuário, quero navegar pelo site com teclado, leitores de tela e diferentes tamanhos de tela para utilizar a aplicação com autonomia.
- US10: Como usuário não autenticado, quero receber orientação ao tentar acessar uma área restrita para entender como prosseguir.

## Principais funcionalidades

### 1. Landing page e catálogo de roteiros

Apresenta a proposta da agência, destinos em destaque e roteiros disponíveis, funcionando como ponto inicial para visitantes e clientes.

- RF1: O sistema deve apresentar uma landing page com a identidade e a proposta da agência.
- RF2: O sistema deve listar os roteiros disponíveis com, no mínimo, destino, título, resumo e informações principais do pacote.
- RF3: O sistema deve permitir abrir os detalhes de um roteiro selecionado.
- RF4: A página deve indicar claramente as ações disponíveis, como consultar detalhes, cadastrar-se ou iniciar a simulação de aquisição.

### 2. Cadastro e acesso de clientes

Permite criar uma conta e controlar o acesso à área de clientes.

- RF5: O sistema deve disponibilizar formulário de cadastro de cliente com os dados necessários ao projeto.
- RF6: O sistema deve validar os campos obrigatórios e informar erros de preenchimento de forma compreensível.
- RF7: O sistema deve permitir que um cliente cadastrado e válido acesse sua área restrita.
- RF8: O sistema deve impedir o acesso de usuários não autenticados às funcionalidades exclusivas da área de clientes.
- RF9: O sistema deve permitir encerrar a sessão do cliente.

### 3. Compra simulada e histórico de viagens

Permite selecionar um roteiro e registrá-lo no perfil do cliente sem cobrança financeira real.

- RF10: O sistema deve permitir que um cliente válido inicie a aquisição simulada de um roteiro.
- RF11: O sistema deve apresentar um resumo do roteiro antes da confirmação.
- RF12: O sistema deve deixar explícito que a aquisição é uma simulação e não gera pagamento ou reserva real.
- RF13: Ao confirmar a simulação, o sistema deve associar o roteiro ao perfil do cliente.
- RF14: O sistema deve informar visualmente o resultado da operação simulada.
- RF15: O sistema deve listar no dashboard do cliente todos os roteiros associados ao seu perfil, com informações suficientes para identificar cada viagem.

### 4. Dashboard do cliente

Centraliza os dados do cliente e seu histórico de viagens.

- RF16: O dashboard deve exibir um resumo do perfil do cliente autenticado.
- RF17: O dashboard deve exibir a quantidade e a lista de roteiros adquiridos pelo cliente.
- RF18: O dashboard deve apresentar um estado vazio orientando o cliente quando ainda não houver viagens registradas.
- RF19: O dashboard deve oferecer navegação clara entre perfil, histórico e saída da conta.

### 5. Dashboard administrativo

Fornece ao administrador uma visão operacional básica da simulação.

- RF20: O sistema deve disponibilizar uma área administrativa protegida por autenticação e autorização.
- RF21: O administrador deve conseguir consultar a lista de clientes cadastrados.
- RF22: O administrador deve conseguir consultar informações resumidas dos clientes e seus roteiros adquiridos.
- RF23: O dashboard administrativo deve exibir o total de clientes e o total de pacotes adquiridos.
- RF24: O sistema deve diferenciar permissões de administrador e cliente, impedindo que clientes acessem funções administrativas.
- RF25: O sistema deve permitir ao administrador gerenciar os registros de clientes conforme os limites definidos na TechSpec.

### 6. Acessibilidade e responsividade

Garante que as jornadas principais sejam utilizáveis por diferentes pessoas e dispositivos.

- RF26: O sistema deve permitir navegação por teclado em todas as jornadas principais.
- RF27: O sistema deve fornecer rótulos, mensagens e estados compreensíveis para componentes interativos e formulários.
- RF28: O sistema deve manter contraste, foco visível, hierarquia semântica e alternativas textuais adequadas.
- RF29: O sistema deve adaptar landing page, catálogos, formulários e dashboards a pelo menos cinco breakpoints.
- RF30: O sistema deve preservar as informações e ações essenciais em telas pequenas, médias e grandes.

## Critérios de aceitação

- CA-01 (US1, RF1, RF2): Dado um visitante na página inicial, quando a página for carregada, então ele verá a proposta da agência e uma lista legível de roteiros com seus dados principais.
- CA-02 (US2, RF3): Dado um roteiro listado, quando o visitante selecionar seus detalhes, então serão exibidas informações suficientes para compreender o destino e o pacote.
- CA-03 (US3, RF5, RF6): Dado um visitante no cadastro, quando enviar um formulário com campos obrigatórios ausentes ou inválidos, então o sistema indicará os campos que precisam ser corrigidos sem concluir o cadastro.
- CA-04 (US3, RF5, RF7): Dado um visitante que preencheu um cadastro válido, quando concluir o cadastro e acessar a conta, então o sistema permitirá a entrada na área de clientes.
- CA-05 (US4, RF8, RF10): Dado um usuário não autenticado, quando tentar iniciar ou confirmar uma aquisição, então o sistema solicitará autenticação ou cadastro e não registrará uma viagem.
- CA-06 (US4, RF11, RF12): Dado um cliente autenticado, quando iniciar a aquisição de um roteiro, então verá um resumo do pacote e um aviso explícito de que não haverá pagamento real.
- CA-07 (US4, RF13, RF14): Dado um cliente autenticado que confirma a simulação, quando a operação for concluída, então o roteiro será associado ao seu perfil e uma mensagem de sucesso será exibida.
- CA-08 (US5, RF15, RF17): Dado um cliente com aquisições simuladas, quando acessar o dashboard, então verá a lista de todas as viagens registradas e a quantidade correspondente.
- CA-09 (US6, RF18): Dado um cliente sem aquisições, quando acessar o histórico, então verá um estado vazio informativo com orientação para consultar os roteiros disponíveis.
- CA-10 (US7, US8, RF20, RF21, RF22, RF23): Dado um administrador autenticado, quando acessar o dashboard administrativo, então verá clientes cadastrados, totais de clientes e pacotes adquiridos.
- CA-11 (US7, RF24): Dado um cliente autenticado, quando tentar acessar uma função administrativa, então o sistema negará o acesso e não exibirá dados administrativos.
- CA-12 (US9, RF26, RF27, RF28): Dado um usuário que navega apenas com teclado ou tecnologia assistiva, quando percorrer as jornadas principais, então poderá identificar o foco, preencher formulários, acionar controles e compreender mensagens e estados.
- CA-13 (US9, RF29, RF30): Dado um dispositivo em qualquer um dos cinco ou mais breakpoints definidos, quando o usuário acessar a aplicação, então o conteúdo não ficará ilegível ou inacessível e as ações principais continuarão disponíveis.
- CA-14 (US10, RF8): Dado um usuário sem sessão, quando acessar uma rota restrita, então verá uma orientação clara para entrar ou cadastrar-se.

## Experiência do usuário

- **Visitante:** precisa entender rapidamente a proposta da agência, explorar destinos e identificar como consultar ou simular a aquisição de um roteiro.
- **Cliente:** precisa realizar cadastro e acesso com baixo atrito, confirmar uma simulação com segurança e localizar seu histórico de viagens no dashboard.
- **Administrador:** precisa visualizar clientes e indicadores principais de forma objetiva, com separação evidente das funções de cliente.

Fluxos principais:

1. Visitante acessa a landing page, explora um roteiro, consulta seus detalhes e decide cadastrar-se ou entrar.
2. Visitante preenche o cadastro válido, acessa a área de clientes, escolhe um roteiro, revisa o resumo e confirma a compra simulada.
3. Cliente retorna ao dashboard e consulta seu perfil, a quantidade de viagens e o histórico de roteiros adquiridos.
4. Administrador acessa sua área protegida, consulta clientes e acompanha os totais de pacotes simulados.
5. Usuário sem permissão tenta acessar uma área restrita e recebe orientação adequada, sem exposição de dados.

Requisitos de UI/UX e acessibilidade:

- Usar hierarquia visual clara, linguagem simples e chamadas para ação identificáveis.
- Exibir estados de carregamento, sucesso, erro, validação e ausência de dados de forma consistente.
- Garantir navegação por teclado, foco visível, ordem de foco lógica e textos associados aos campos.
- Fornecer texto alternativo para imagens relevantes e não depender apenas de cor para comunicar estados.
- Manter contraste adequado, títulos hierárquicos e componentes compatíveis com leitores de tela.
- Atender às recomendações de acessibilidade digital aplicáveis no Brasil, dentro do escopo acadêmico.
- Testar a experiência em pelo menos cinco breakpoints, mantendo leitura e ações essenciais em dispositivos móveis e desktops.

## Restrições técnicas de alto nível

- A aplicação deve funcionar como uma simulação acadêmica de agência de viagens, sem realizar compra, cobrança, reserva ou transação financeira real.
- Não haverá integração com APIs financeiras ou ferramentas externas fora do escopo definido para o projeto.
- A aplicação deve interagir com os cadastros, roteiros, aquisições simuladas e permissões definidos para o projeto existente.
- Dados de clientes e histórico de viagens devem receber proteção compatível com uma aplicação acadêmica, evitando exposição indevida entre clientes e separando a área administrativa.
- A solução deve respeitar as recomendações mínimas de acessibilidade digital e considerar privacidade dos dados cadastrais.
- As metas detalhadas de tecnologia, arquitetura, integrações e dependências serão definidas na TechSpec.

## Fora do escopo

- Integração com gateways de pagamento, bancos, cartões ou qualquer API financeira.
- Compra, cobrança, reserva ou emissão real de passagens, hospedagens ou vouchers.
- Integrações com APIs externas de destinos, mapas, clima, companhias aéreas ou hotéis.
- Ferramentas externas não previstas no escopo acadêmico.
- Sistema completo de atendimento, avaliações, cupons, programa de fidelidade ou recomendações personalizadas.
- Controle operacional real de disponibilidade, estoque, tarifas ou alterações de viagem.
- Aplicativo nativo para dispositivos móveis.
- Recursos administrativos avançados além do gerenciamento básico de clientes e consulta de totais.
- Definição de arquitetura, banco de dados, endpoints, bibliotecas e demais detalhes de implementação, que pertencem à TechSpec.
