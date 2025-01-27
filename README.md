# Projeto de Viagens

## Configuração do Ambiente

### Passo 1: Configurar o Composer com Docker

Para configurar o Composer utilizando Docker, siga os passos abaixo:

1. Certifique-se de que você tem o Docker e o Docker Compose instalados em sua máquina.
2. No diretório raiz do projeto, execute o comando abaixo para construir os containers:

    ```sh
    docker-compose build
    ```

3. Após a construção dos containers, inicie-os em segundo plano com o comando:

    ```sh
    docker-compose up -d
    ```

### Passo 2: Rodar as Migrations do Laravel

Com os containers em execução, você precisa rodar as migrations do Laravel para configurar o banco de dados. Execute o comando abaixo dentro do container da aplicação:

```sh
php artisan migrate
```

## Permissões de Usuário

Somente o usuário com a função de `admin` pode alterar o status de outros pedidos. Certifique-se de que o usuário está autenticado e possui a função adequada antes de tentar realizar essa operação.

```