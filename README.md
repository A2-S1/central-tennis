# CentralTennis

Aplicação Laravel para comunidade de Tênis: rankings, torneios, quadras, notícias, Loja, Classificados e painel Admin.

## Requisitos

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Extensões PHP comuns (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo)

## Setup rápido

1. Instale dependências
```
composer install
```

2. Copie o `.env` e gere a chave
```
cp .env.example .env
php artisan key:generate
```

3. Configure o banco no `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=central_tennis
DB_USERNAME=root
DB_PASSWORD=secret
```

4. Rode migrações e seeds
```
php artisan migrate
php artisan db:seed --class=StoreDemoSeeder
php artisan db:seed --class=ClassifiedsDemoSeeder
```

5. Suba o servidor
```
php artisan serve
```

## Principais rotas

- Landing pública: `/`
- Loja pública: `/store`
- Classificados públicos: `/classifieds`
- Criar Classificado: `/classifieds/create` (autenticado)
- Meus anúncios: `/my-classifieds` (autenticado)
- Carrinho: `/cart`
- Checkout: `/checkout`
- Admin: `/admin` (usuário com `is_admin=1`)

## Integrações

- Mercado Pago (a configurar):
  - `MERCADOPAGO_ACCESS_TOKEN` e `MERCADOPAGO_PUBLIC_KEY` no `.env`
- Armazenamento local de imagens (disk `public`):
  - Rode `php artisan storage:link` se necessário

## Desenvolvimento Frontend (opcional)

Este projeto funciona com CDN do Bootstrap, mas você pode usar Vite:
```
npm install
npm run dev
```

## Testes

```
php artisan test
```

## CI (GitHub Actions)

Arquivo em `.github/workflows/laravel.yml` executa `composer install`, `php artisan test` e valida o build.

## Segurança

Nunca commite `.env` ou chaves sensíveis. O `.gitignore` já ignora envs comuns.
