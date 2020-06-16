<?php

use Alura\Doctrine\Helper\EntityManagerFactory;

require __DIR__ . 'vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();