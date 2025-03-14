# 📌 Projeto Laravel AutoGestor

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.2**
- **Laravel 12**
- **Blade**
- **MySQL/MariaDB**
- **Docker**

---

## 🛠 Instalação e Configuração

1. Clone o repositório:

   ```sh
   git clone https://github.com/Vowkaz/avaliacao-laravel.git
   cd avaliacao-laravel
   ```

2. Configure a env:

   ```sh
   cp .env.example .env
   ```

    - Atualize as configurações do banco de dados no `.env`.

3. Gere o container do docker:

   ```sh
   docker compose up -d
   ```

4. Entre no container da aplicacao para executar as migrações:

   ```sh
   docker exec -it autogestor-app bash
   php artisan migrate
   ```

5. Acesse localhost para visualizar aplicacao:

   ```sh
   http://localhost/
   ```

---

## 🔒 Regras de Autenticação e Autorização

- **Autenticação via Laravel Breeze**.
- **Perfis de usuários:**
    - `Administrador`: pode gerenciar somente usuários e permissões de usuários Comum.
    - `Comum`: pode visualizar e gerenciar produtos conforme as permissões dadas pelo Administrador.

---

## 📂 Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
├── Models/
├── Policies/
├── Repositories/
├── Services/
```

---
