# PHP Doctrine ORM

## O que é Doctrine?

- É um conjunto de bibliotecas PHP focadas principalmente no fornecimento de serviços de persistência e funcionalidade relacionada.

- O ORM transforma nosso modelo relacional (Banco de dados) em um modelo orientado a objetos, nos ajuda a escrever comandos SQL mais facilmente e também facilita a migração de SGBDs.

- É instalado através do composer (composer require doctrine/orm).

## Gerenciador de Entidades

- É a classe do doctrine responsável por gerenciar o estado das entidades e realizar as escritas no banco de dados. 

```
    public function getEntityManager(): EntityManagerInterface
    {

        $rootDir = Diretorio Raiz da Aplicação;
        $config = Setup::createAnnotationMetadataConfiguration([$rootDir . '/src'], true);

        //aqui configuramos a conexão com banco de dados
        $connection = [
            'driver' => 'pdo_sqlite',
            'path' => $rootDir . '/var/data/banco.sqlite'
        ];

        // Obter a instância da classe Entity Manager 
        return EntityManager::create($connection, $config);
    }

```
