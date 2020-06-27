<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();
$alunoRepository = $entityManager->getRepository(Aluno::class);


$alunoClasse = Aluno::class;
$dql = "SELECT aluno, telefones, cursos FROM $alunoClasse aluno JOIN aluno.telefones telefones JOIN aluno.cursos cursos";
$query = $entityManager->createQuery($dql);

/**  @var Aluno[] $alunos */
$alunos = $query->getResult();

foreach($alunos as $aluno){

    $telefones = $aluno->getTelefones()->map(function (Telefone $telefone) {
        return $telefone->getNumero();
    })->toArray();

    echo "ID: {$aluno->getId()}\n";
    echo "Nome: {$aluno->getNome()}\n";
    echo "Telefones: " . implode(",", $telefones) . "\n";
    $cursos = $aluno->getCursos();

    foreach($cursos as $curso){
        echo "\tId Curso: {$curso->getId()}\n";
        echo "\tNome Curso: {$curso->getNome()}\n";
        echo "\n";
    }
    echo "\n";
}

