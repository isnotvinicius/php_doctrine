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

## Entidade

- É uma instância de uma classe JavaScript que pode ser mapeada para o banco de dados.

- Criamos uma classe como qualquer outra e, como utilizamos a configuração de anotações no EntityManager, colocamos a anotação na classe para informar ao doctrine que aquela classe deve ser mapeada como entidade (@Entity).

- Informamos também que nosso atributo id será o identificador único do banco.

```
/**
* @Id
* @GeneratedValue
* @Column(type="integer")
*/
```

- Adicionamos anotações para todos os atributos restantes informando o tipo do dado e outras informações se necessário.


## Comandos Doctrine

- Para rodar os comandos do Doctrine precisamos ter o arquivo cli-config.php no nosso projeto.

```
<?php

use Alura\Doctrine\Helper\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->GetEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
```

- Com este arquivo criado corretamente é possível executar no terminal Linux o comando "php vendor/bin/doctrine list" para obter os possíveis comandos do Doctrine. no terminar Windows o comando é "vendor\bin\doctrine.bat list".


## Inserir dados

- Para inserir um dado criamos o objeto da classe que mapeamos como entidade e chamamos o EntityManager com o método get que criamos.

- Após isso precisamos persistir, ou seja, fazer com que o objeto criado fique em observação pelo doctrine com $entityManager->persist($object); 

- O método flush do entityManager nos permite enviar os dados para o banco. $entityManager->flush();


## Buscar dados

- Para buscar dados precisaremos do nosso entity manager e através dele chamamos o método repository. $entityManager->getRepository(Nome_Classe::class);
com isso nós criamos um repositório da classe passada e através da váriavel podemos chamar alguns métodos de busca.

- Para buscar todos os dados da tabela utilizimanos $variavel->findAll(); e podemos utilizar um foreach para visualizar os dados.

- Para buscar um dado específico podemos usar o método find() passando o Id do dado ou o método findBy([]) informando dentro do array por qual dado buscar. Caso queria buscar apenas um só dado o método findOneBy([]) retorna um objeto e não um array como no caso anterior.

Assinatura do método findBy:
```
public function findBy(
    array $criteria,
    ?array $orderBy = null,
    ?int $limit = null,
    ?int $offset = null
)
```

- $criteria: Critério de busca. Array vazio significa sem critério, ou seja, sem filtro, buscando todos os registros;
- $orderBy: Critério de ordenação. Um array onde as chaves são os campos, e os valores são 'ASC' para ordem crescente e 'DESC' para decrescente;
- $limit: Numéro de resultados para trazer do banco;
- $offset: A partir de qual dado buscar do banco. Muito utilizado para realizar paginação de dados.


## Atualizar dados

- Criamos o entityManager.

- Pegamos o Id que queremos alterar e o valor a ser alterado pela linha de comando (argv[]).

- Fazemos um find usando o entityManager e passamos a entidade (Class::class) que vamos buscar os dados e o id digitado. O find do entityManager busca apenas UM registro, para buscar todos precisamos do repositório da classe.

- Fazemos um set com o dado a ser alterado.

- Por último fazemos o flush(). Note que não precisamos fazer o persist pois a entidade que estamos trabalhando já está sendo manipulada pelo doctrine.


## Remover dados

- Criamos o entityManager.

- Informamos o id a ser removido através do $argv[];

- Aqui chamamos o método getReference() passando a entidade (Class::class) e o id informado na linha de comando. O método getReference pega uma referência dessa entidade passada e cria uma nova entidade que possuí apenas o id passado sem ir até o banco de dados. Isso evita que sejam feitas duas querys (SELECT e DELETE).

- Chamamos o método remove() do entityManager passando como parâmetro o objeto que recebe o find do entityManager.

- Fazemos o flush() para finalizar.


## Relacionamento OneToMany

- Quando uma entidade tem um atributo de outra entidade e esse atributo está no plural podemos utilizar um ArrayCollection para definir ele. (UM aluno pode ter VÁRIOS telefones).

- Podemos informar essa relação através da anotação @OneToMany(targetEntity=""), onde targetEntity é a Entidade que este atributo está relacionado.

- Na entidade que representa o Many temos que adicionar o atributo que representa o One (Aluno neste exemplo) e colocar uma anotação informando que este atributo é ManyToOne(targetEntity="").

- Depois de adicionar o atributo e a anotação na entidade Many, devemos informar dentro do OneToMany o campo mappedBy que é o atributo colocado na outra entidade. OneToMany(targetEntity="Telefone", mappedBy="aluno").

- No construtor informamos que o atributo em questão é um array collection, ou seja, $this->atributo = new ArrayCollection().

- No método get deste atributo podemos informar que o retorno será um Collection, pois quando os dados vierem do banco eles podem ou não ser um ArrayCollection, como este método implementa a interface Collection podemos ter o retorno como Collection.


## Migrations

- É um meio de versionar o banco de dados para que quando outras pessoas forem fazer alterações ou testar o projeto, tudo esteja atualizado e pronto para usar com apenas um comando como no git. Também é possível voltar versões com as migrations.

- Para instalar Migrations utilizamos composer require doctrine/migrations.

- Precisamos criar o arquivo de configuração das Migrations no diretório raiz do projeto.

```
<?php

return [
    'name' => 'My Project Migrations',
    'migrations_namespace' => 'MyProject\Migrations',
    'table_name' => 'doctrine_migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => '/data/doctrine/migrations-docs-example/lib/MyProject/Migrations',
    'all_or_nothing' => true,
    'check_database_platform' => true,
];
```

- O comando migrations:diff gera uma migration comparando nosso banco de dados atual com as informações que temos mapeadas.

- O comando migrations:migrate executa todos os arquivos de migração que o projeto possuí.


## Relacionamento ManyToMany

- A anotação do relacionamento ManyToMany é parecida com a OneToMany, colocamos a entidade alvo em ambas as entidades e em uma delas colocamos o mappedBy que aponta para o atributo mapeado na outra entidade.

- Como mapeamos os atributos em ambas as classes fazendo uma relação bidirecional colocamos a anotação inversedBy e o atributo definido na outra entidade. Isso deixa mais explícito para o Doctrine que temos uma relação nos dois lados.

```
class Curso
{
    /**
    * @ManyToMany(targetEntity="Aluno", inversedBy="cursos")
    */
    private $alunos;
}

class Aluno
{
    /**
    * @ManyToMany(targetEntity="Cursos", mappedBy="alunos")
    */
    private $cursos;
}
```

- Como na hora de adicionar um aluno no curso já adicionamos este curso na entidade aluno também, podemos criar um loop infinito de chamadas de método add o que ocasionalmente leva ao preenchimento total da memória. Para evitar isso nós verificamos se aquele elemento já foi adicionado a nossa lista graças a coleção do doctrine.

```
if ($this->atributo_entidade->contains($atributo_recebido))
{
    return $this;
}
```
Com este if nós verificamos se aquele atributo recebido já existe na nossa coleção e não adicionamos ele novamente evitando o loop infinito.





