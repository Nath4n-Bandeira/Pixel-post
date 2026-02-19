pegue está parte 

# Pixel.Post 

> Rede social minimalista com estética retro pixel-art construída em Laravel para fins educacionais.

## Descrição

Pixel.Post é uma aplicação web de estudo em PHP/Laravel que implementa uma rede social tipo feed. Desenvolvida durante estudo de conceitos fundamentais do framework Laravel, autenticação, CRUD, upload de arquivos, relacionamentos entre modelos e design responsivo com Tailwind CSS 

### Pré-requisitos

- PHP 8.1+
- Composer
- Node.js 16+ (para Tailwind CSS)
- Git

### Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/pixel-post.git
cd pixel-post
```

2. Instale as dependências PHP
```bash
composer install
```

3. Instale as dependências Node
```bash
npm install
```

4. Configure o arquivo .env
```bash
cp .env.example .env
php artisan key:generate
```

5. Execute as migrações
```bash
php artisan migrate
```

6. Crie o link simbólico para storage público
```bash
php artisan storage:link
```

7. Inicie o servidor
```bash
php artisan serve
```

8. Em outro terminal, compile os assets (opcional)
```bash
npm run dev
```

Acesse o a porta do ip correspondente que o seu CLI mostrar no seu navegador.


