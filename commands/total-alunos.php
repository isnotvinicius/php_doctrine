<?php

use Alura\Doctrine\Helper\EntityManagerFactory;
use Alura\Doctrine\Entity\Aluno;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$classeAluno = Aluno::class;
$dql = "SELECT COUNT(aluno) FROM $classeAluno aluno";
$query = $entityManager->createQuery($dql);

$totalAlunos = $query->getSingleScalarResult();
echo "Total alunos: " . $totalAlunos . "\n";