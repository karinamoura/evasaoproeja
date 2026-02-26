# Evasão PROEJA

Sistema de acompanhamento de evasão no contexto do **PROEJA** (Programa Nacional de Integração da Educação Profissional com a Educação Básica na Modalidade de Educação de Jovens e Adultos). Permite o cadastro de instituições, ofertas, estudantes, disciplinas, registro de frequência, aplicação de questionários e geração de relatórios de evasão e risco.

---

## Requisitos

- **PHP** 8.1 ou superior  
- **Composer**  
- **Node.js** 18+ e **npm** (ou yarn/pnpm)  
- **Banco de dados**: SQLite (padrão) ou MySQL/MariaDB  

---

## Instalação

### 1. Clonar e entrar no projeto

```bash
git clone <url-do-repositorio> evasaoproeja
cd evasaoproeja
```

### 2. Dependências PHP

```bash
composer install
```

### 3. Variáveis de ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` e configure:

- **`APP_NAME`** – Nome do sistema (ex.: `"Sistema Evasão PROEJA"`)
- **`APP_URL`** – URL de acesso (ex.: `http://localhost:8000` ou `http://evasaoproeja.test`)
- **Banco de dados**  
  - SQLite (padrão): deixe `DB_CONNECTION=sqlite` e garanta que o arquivo `database/database.sqlite` exista:
    ```bash
    touch database/database.sqlite
    ```
  - MySQL: preencha `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` e altere `DB_CONNECTION=mysql`

### 4. Banco de dados

```bash
php artisan migrate
php artisan db:seed
```

Os seeders criam permissões, perfis, um usuário inicial, instituições, escolas, ofertas e questionários de exemplo.

### 5. Frontend (Vite)

```bash
npm install
npm run build
```

Para desenvolvimento com recarregamento automático:

```bash
npm run dev
```

### 6. Servidor

Em um terminal, suba o servidor Laravel:

```bash
php artisan serve
```

Acesse a URL informada (ex.: `http://127.0.0.1:8000`). A raiz redireciona para o login.

---

## Módulos e funcionalidades

O painel administrativo fica em **`/admin`** (após login). O acesso a cada módulo depende das **permissões** do perfil do usuário.

### Autenticação e perfil

- Login (e-mail/senha), registro  
- Perfil: edição de dados e senha  

Rotas principais: `/login`, `/register`, `/admin/profile`.

---

### Usuários e acesso

| Módulo       | Rota base   | Descrição                                      |
|-------------|-------------|-------------------------------------------------|
| **Usuários** | `/admin/user` | CRUD de usuários do sistema                     |
| **Perfis**   | `/admin/role` | Perfis (roles) e atribuição de permissões       |
| **Permissões** | `/admin/permission` | Permissões granulares por recurso (view, create, edit, delete, etc.) |

Permissões controlam o que cada perfil pode acessar (usuários, instituições, escolas, ofertas, questionários, disciplinas, estudantes, frequências, relatórios, termos de uso, etc.).

---

### Cadastros básicos

| Módulo          | Rota base      | Descrição                                      |
|-----------------|----------------|-------------------------------------------------|
| **Instituições (Campi)** | `/admin/campi`   | Instituições/campus que ofertam cursos          |
| **Escolas**     | `/admin/escola` | Escolas vinculadas às instituições             |
| **Ofertas**     | `/admin/oferta` | Ofertas/cursos (turmas), vinculadas a escola e instituição; turno, coordenador, transporte, auxílio financeiro |

---

### Questionários

| Recurso | Rota base | Descrição |
|--------|-----------|-----------|
| **Questionários** | `/admin/questionario` | CRUD de questionários: seções, perguntas, opções, formato de validação (ex.: número para matrícula). Ativar/desativar. |
| **Questionário × Oferta** | `/admin/questionario-oferta` | Vincula questionário a uma oferta, define URL pública, termo de uso, cor. Lista respostas e exporta CSV. |
| **Termos e condições** | `/admin/termo-condicao` | Textos de termo de uso para exibição antes do questionário. |

**Público:** o respondente acessa o questionário pela **URL pública** (ex.: `/questionario/{urlPublica}`), sem precisar estar logado. As respostas ficam vinculadas ao questionário-oferta e podem ser visualizadas e exportadas no admin.

---

### Disciplinas e estudantes

| Recurso | Rota base | Descrição |
|--------|-----------|-----------|
| **Disciplinas** | `/admin/disciplina` | CRUD de disciplinas por oferta. Vincular estudantes à disciplina. |
| **Estudantes** | `/admin/estudante` | CRUD de estudantes. **Upload em massa** via planilha (processamento assíncrono; resultado por token). |

---

### Frequências

| Recurso | Rota base | Descrição |
|--------|-----------|-----------|
| **Frequência** | `/admin/frequencia` | Registrar frequência por disciplina (data, presentes/ausentes). Histórico de frequência por estudante. |

---

### Relatórios

Todas sob **`/admin/relatorio`**, com permissão `relatorios.view`:

| Relatório | Descrição |
|-----------|-----------|
| **Evasão por oferta** | Indicadores de evasão por oferta. |
| **Frequência por disciplina** | Frequência agregada por disciplina. |
| **Estudantes em risco** | Listagem de estudantes com indicadores de risco de evasão. |
| **Frequência por período** | Frequência em um intervalo de datas. |
| **Questionários respondidos** | Quantidade e resumo de respostas por questionário-oferta. |
| **Comparativo de ofertas** | Comparação entre ofertas. |

---

### Outras páginas

- **Sobre** (público): `/sobre`  
- **Sobre** (admin): `/admin/sobre`  
- **Dashboard**: `/admin/dashboard` (após login)

---

## Estrutura técnica resumida

- **Backend:** Laravel 10, PHP 8.1+  
- **Autenticação:** Laravel Breeze, Spatie Laravel Permission, Laravel Socialite
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite  
- **Planilhas:** PHPSpreadsheet (importação de estudantes)  
- **Banco:** migrations e seeders em `database/`

---

## Comandos úteis

```bash
# Limpar e rodar migrations de novo
php artisan migrate:fresh --seed

# Rodar apenas seeders
php artisan db:seed

# Compilar assets para produção
npm run build

# Desenvolvimento (assets + servidor)
npm run dev
php artisan serve
```

---

## Licença

Consulte o arquivo [LICENSE](LICENSE) do repositório.
