<?php

namespace Alura\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Alura\Doctrine\Entity\Aluno;

class AlunoRepository extends EntityRepository
{
    public function buscarCursosPorAluno()
    {
        $query = $this->createQueryBuilder('a')
            ->join('a.telefones', 't')
            ->join('a.cursos', 'c')
            ->addSelect('t')
            ->addSelect('c')
            ->getQuery();
        return $query->getResult();
    }
}

