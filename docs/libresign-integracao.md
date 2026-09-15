# Integração e-cidade ↔ LibreSign — Investigação e Proposta Técnica

> Status: **proposta para validação** (nenhum código do core do e-cidade foi alterado).
> Data da investigação: ambiente local.

---

## 1. Sumário executivo

**A integração e-cidade ↔ LibreSign já existe no repositório** e é feita de forma
**direta (sem middleware)**: a classe `model/protocolo/AssinaturaDigital.model.php`
chama a API REST do LibreSign (namespace OCS) via Guzzle/cURL, envia o PDF em
base64 e registra o UUID retornado nas tabelas do módulo Financeiro.

O ponto central não é "criar uma integração do zero", e sim **atualizar a
integração existente**, que hoje usa um formato de payload **antigo** da API do
LibreSign e não usa webhook (faz *polling*). A API do LibreSign mudou de forma
incompatível (campo `users` → `signers`; webhook `/webhook/register` → campo
`callback` no próprio request).

### Descobertas-chave

| # | Descoberta |
|---|-----------|
| 1 | Já existe integração direta em `model/protocolo/AssinaturaDigital.model.php` (~814 linhas). |
| 2 | Cobre o módulo **Financeiro**: Empenho, Liquidação, Ordem de Pagamento, Anulação de Empenho e Slip (Pagamento Extra). |
| 3 | Configuração em `configuracoes.assinatura_digital_parametro` (URL/token/ativo) e `configuracoes.assinatura_digital_assinante` (signatários por órgão/unidade/documento/cargo/período). |
| 4 | O payload enviado usa `users` + `identify.account` — formato **antigo** do LibreSign. |
| 5 | O LibreSign atual (v15) usa `signers` + `identifyMethods`; webhook virou o campo `callback` (não existe mais `/webhook/register`). |
| 6 | O mapeamento de status do e-cidade está correto (status 3 = `SIGNED`). |
| 7 | O ambiente LibreSign (Nextcloud 31) foi levantado localmente; o app LibreSign ainda precisa ser instalado no branch `stable31`. |
| 8 | O build Docker do e-cidade exige mais disco do que o disponível (98% em uso) e depende de PHP 7.4 (EOL) no Ubuntu 22.04. |

---

## 2. Estado atual da integração (o que já existe)

### 2.1 Arquitetura atual (direta, sem middleware)

```
[e-cidade] --(HTTP Basic + REST OCS)--> [Nextcloud + LibreSign]
   AssinaturaDigital.model.php            /ocs/v2.php/apps/libresign/api/v1/*
```

Não há serviço intermediário, fila ou webhook. O fluxo é síncrono e disparado
pelo próprio e-cidade após gerar o PDF do relatório.

### 2.2 Arquivos e tabelas

**Classe principal**
- `model/protocolo/AssinaturaDigital.model.php` — orquestra tudo (envio, validação, download, assinatura, exclusão).

**Modelos/Repositórios (Laravel)**
- `app/Models/Configuracao/AssinaturaDigitalParametro.php` → tabela `configuracoes.assinatura_digital_parametro` (`db242_*`).
- `app/Models/Configuracao/AssinaturaDigitalAssinante.php` → tabela `configuracoes.assinatura_digital_assinante` (`db243_*`).
- `app/Repositories/Configuracao/AssinaturaDigitalAssinatesRepository.php` — resolve signatários por **dotação** ou **contador/gestor**.

**Migrações**
- `db/migrations/20240308133913_assantura_digital_parametro.php`
- `db/migrations/20240320200702_add_assinatura_digital_assinantes.php`
- `db/migrations/20240307082835_add_menu_assinatura_digital.php` e `20240409151722_add_menu_lista_documentos_assinatura.php`

**Telas (legacy)**
- `resources/legacy/contabilidade/con1_assinaturadigitalparametros.php` (parâmetros)
- `resources/legacy/contabilidade/con1_assinaturadigitalassinantes.php` (signatários)
- `resources/legacy/contabilidade/con1_assinaturadigitaldocumentos.php` (listagem)
- `resources/legacy/contabilidade/con1_assinaturadigital.RPC.php` / `con1_assinaturaDigitalDocumentos.RPC.php`

**Formulários (geração de PDF → assinatura)**
- `model/empenho/relatorio/FormularioEmpenho.model.php` (`solitarAssinaturaEmpenho`)
- `model/empenho/relatorio/FormularioLiquidacao.model.php` (`solitarAssinaturaLiquidacao`)
- `FormularioOrdemPagamento` (`solitarAssinatura`)
- `assinarAnulacaoEmpenho`, `assinarSlip`, `assinarOrdemPagamento`

### 2.3 Tipos de documento e cargos

- **Tipos**: `TIPO_DOC_EMPENHO=0`, `TIPO_DOC_LIQUIDACAO=1`, `TIPO_DOC_PAGAMENTO=2`, `TIPO_DOC_SLIP=3`.
- **Cargos** (`AssinaturaDigitalAssinante::ASSINTAURA_CARGOS`): Outros, Ordenador Despesa, Ordenador Liquidação, Ordenador Pagamento, Contador, Tesoureiro, Gestor, Controle Interno.

### 2.4 Endpoints usados (hoje)

| Método | Endpoint | Uso no e-cidade |
|--------|----------|-----------------|
| POST | `/ocs/v2.php/apps/libresign/api/v1/request-signature` | Enviar PDF + signatários |
| GET | `/ocs/v2.php/apps/libresign/api/v1/file/list` | Listar documentos |
| GET | `/ocs/v2.php/apps/libresign/api/v1/file/validate/uuid/{uuid}` | Verificar status (polling) |
| POST | `/ocs/v2.php/apps/libresign/api/v1/sign/uuid/{uuid}` | Assinar (clickToSign) |
| DELETE | `/ocs/v2.php/apps/libresign/api/v1/sign/file_id/{nodeId}` | Excluir solicitação |
| GET | `/apps/libresign/p/pdf/{uuid}` | Baixar PDF assinado |

Autenticação: **Basic Auth** com usuário fixo `admlibrecode` (constante) e senha
armazenada em `db242_assinador_token`.

---

## 3. Validação da API do LibreSign (v15)

Fonte: OpenAPI oficial (`openapi.json` / `openapi-full.json`) e código-fonte do
app LibreSign (`lib/Enum/FileStatus.php`, `appinfo/routes.php`).

### 3.1 Mudança incompatível no payload de assinatura

**Formato que o e-cidade envia hoje (obsoleto):**

```json
{
  "file": { "base64": "..." },
  "name": "Documento",
  "users": [
    {
      "displayName": "Fulano (Ordenador Despesa)",
      "description": "Assinar este documento",
      "identify": { "account": "fulano.login" },
      "notify": true
    }
  ]
}
```

**Formato atual do LibreSign v15 (`signers` + `identifyMethods`):**

```json
{
  "file": { "base64": "..." },
  "name": "Documento",
  "signers": [
    {
      "displayName": "Fulano (Ordenador Despesa)",
      "description": "Assinar este documento",
      "identifyMethods": [
        { "method": "account", "value": "fulano.login", "mandatory": 0 }
      ],
      "notify": 1
    }
  ],
  "callback": "https://middleware.local/libresign/callback"
}
```

Mudanças: `users` → `signers`; `identify.account` → `identifyMethods[{method,value,mandatory}]`;
`notify` booleano → inteiro; e adição do campo `callback` (webhook).

### 3.2 Webhook

- **Não existe mais** o endpoint `POST .../webhook/register` (citado no prompt).
- No v15, o webhook é o campo **`callback`** (string de URL) enviado no próprio
  `request-signature`. A URL recebe um `POST` quando a assinatura é concluída.

### 3.3 Status do arquivo

`lib/Enum/FileStatus.php` (v15):

| Código | Enum | Label |
|--------|------|-------|
| -1 | NOT_LIBRESIGN_FILE | Not LibreSign file |
| 0 | DRAFT | Draft |
| 1 | ABLE_TO_SIGN | Ready to sign |
| 2 | PARTIAL_SIGNED | Partially signed |
| 3 | SIGNED | Signed |
| 4 | DELETED | Deleted |
| 5 | SIGNING_IN_PROGRESS | Signing in progress |

**O mapeamento do e-cidade está correto**: usa status 1 (pendente), 2 (parcial),
3 (assinado) e valida `status == 3` como "assinado".

### 3.4 Compatibilidade de versão (Nextcloud ↔ LibreSign)

Branches do LibreSign mapeiam 1:1 para a versão do Nextcloud:
`stable31` → Nextcloud 31 (o ambiente local usa `VERSION_NEXTCLOUD=stable31`).

Checkouts locais disponíveis:
- `/home/mohr/local/libresign` → **v15.0.0-dev.1** (Nextcloud 35)
- `/home/mohr/git/libresign/libresign` → **v16.0.0-dev.1** (Nextcloud 36)

Para o ambiente atual (NC 31), deve-se usar o branch **`stable31`** do LibreSign
(≈ v11.x). A validação de payload acima foi feita sobre o v15 (master); é preciso
**revalidar contra o branch `stable31`** antes de codificar, pois campos podem
variar.

---

## 4. Lacunas e riscos da integração atual

1. **Payload desatualizado** (`users`/`identify.account`) — quebra em LibreSign recente.
2. **Sem webhook** — usa `file/validate` (polling), frágil e com `sleep(4)` entre chamadas.
3. **Sem idempotência/retry/fila** — chamadas síncronas com timeout de 600s.
4. **Credencial fixa no código** — usuário `admlibrecode` hardcoded.
5. **Merge de PDFs via `gs` (shell `exec`)** — risco de injeção/portabilidade.
6. **`SalvarAssinaturaDigitalParametroService` é um stub vazio**.
7. **Escopo limitado ao Financeiro** — não cobre protocolo/portarias/contratos (que o prompt cita).

---

## 5. Recomendação de arquitetura

Duas opções foram consideradas. **Recomenda-se a Opção A** (evoluir a integração
direta), com a Opção B (middleware) como evolução futura se surgirem requisitos de
desacoplamento.

### Opção A — Evoluir a integração direta (recomendada)

1. Atualizar o payload em `AssinaturaDigital.model.php` para `signers` + `identifyMethods`.
2. Adicionar o campo `callback` (webhook) e criar um endpoint no e-cidade para
   receber o callback de "assinado" (grava o status + baixa o PDF assinado).
3. Extrair a URL/token/usuário para variáveis de ambiente (`.env`) em vez de
   constante + tabela.
4. Substituir o polling por callback + `file/list` apenas como fallback.
5. Trocar `gs` (exec) por merge em PHP (mpdf já é dependência do projeto).
6. Adicionar idempotência (guarda o `uuid` antes do envio e evita duplicatas).

**Prós**: menor esforço, reaproveita telas/repositórios/regras de signatário já existentes.
**Contras**: mantém acoplamento no monolito.

### Opção B — Middleware independente (como no prompt)

Um serviço (PHP 8.x) que recebe o gatilho do e-cidade, fala com o LibreSign,
registra `uuid`, expõe o webhook e devolve o PDF assinado.

**Prós**: desacopla, permite retry/fila e múltiplos sistemas.
**Contras**: duplica a lógica de signatários (que já está no e-cidade), exige novo
gatilho no monolito e mais infraestrutura. Só faz sentido se houver outros sistemas
que também precisem assinar no LibreSign.

**Recomendação**: começar pela Opção A. O middleware (Opção B) pode ser introduzido
depois, atrás do mesmo endpoint `callback`, sem reescrever o e-cidade.

---

## 6. Ambiente local — status

### 6.1 LibreSign / Nextcloud (levantado ✔)

- Repo: `nextcloud-docker-development` clonado em `/home/mohr/git/henmohr/nextcloud-libresign`.
- Compose: Nextcloud `stable31` + PostgreSQL 13 + Redis + Mailpit + nginx (`127.0.0.1:8181`).
- **Correção aplicada**: `NEXTCLOUD_ADMIN_EMAIL=integracao@localhost` era rejeitado
  pelo instalador (e-mail inválido) → alterado para `integracao@example.com`.
- Serviços iniciados (database/redis/mailpit estavam parados) e **Nextcloud instalado com sucesso**.
- **Concluído**: app LibreSign **v11.6.0** (branch `stable31`) clonado, submodule
  `3rdparty` inicializado, `composer install` + `npm ci` + `npm run build` executados e
  app habilitado (`occ app:enable libresign`). API verificada: `file/list` responde
  HTTP 200 via OCS em `http://127.0.0.1:8181/ocs/v2.php/apps/libresign/api/v1/file/list`.

### 6.2 e-cidade (levantado ✔)

- Imagens construídas: `ecidadecontass:bd` (PostgreSQL 12) e `e-cidade-web`
  (Ubuntu 22.04 + PHP 7.4 + LibreOffice + MS Core Fonts + Node 20).
- **PHP 7.4 confirmado disponível** no PPA `ondrej/php` para jammy (a hipótese de
  indisponibilidade não se confirmou).
- **Correção necessária aplicada** em `docker/bd/Dockerfile`: o Debian bullseye (base
  do PostgreSQL 12) atingiu EOL e os repositórios `deb.debian.org` quebraram; o
  Dockerfile agora aponta para `archive.debian.org` e remove o repo `debian-security`.
- Serviços ativos: `web` (Apache/PHP em `http://127.0.0.1:8282`), `bd` (PostgreSQL
  `127.0.0.1:5432`, banco `ecidade` em LATIN1) e `adminer` (`http://127.0.0.1:8484`).
- Login do e-cidade responde (HTTP 200 em `/login.php`); seed local cria o usuário
  `admin` (senha padrão do projeto).

---

## 7. Próximos passos (após validação desta proposta)

1. Instalar o app LibreSign `stable31` no ambiente Nextcloud local.
2. Revalidar a API (`request-signature`/`callback`/`file/list`) contra o branch `stable31`.
3. Corrigir o payload em `AssinaturaDigital.model.php` (`signers` + `identifyMethods` + `callback`).
4. Criar o endpoint de webhook/callback no e-cidade (grava status + baixa PDF assinado).
5. Extrair credenciais para `.env` e remover o usuário hardcoded.
6. Testar ponta a ponta (empenho) com a instância LibreSign local.
