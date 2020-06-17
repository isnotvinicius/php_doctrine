<?php

use Alura\Doctrine\Helper\EntityManagerFactory;
use Alura\Doctrine\Entity\Aluno;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$alunoRepository = $entityManager->getRepository(Aluno::class);

/** @var Aluno[] $alunoList */
$alunoList = $alunoRepository->findAll();

foreach($alunoList as $aluno){
    echo "Id: {$aluno->getId()}\nNome: {$aluno->getNome()}\n\n";
}

$teste = $alunoRepository->find(2);
echo $teste->getNome() . PHP_EOL;

$teste2 = $alunoRepository->findOneBy([
    'nome' => 'Yasmin'
]);

var_dump($teste2);