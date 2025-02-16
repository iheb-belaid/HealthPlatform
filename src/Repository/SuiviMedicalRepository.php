<?php

namespace App\Repository;

use App\Entity\SuiviMedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SuiviMedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviMedical::class);
    }

    /**
     * Récupérer tous les suivis médicaux avec leur patient associé, filtrés par nom du patient.
     *
     * @param string|null $patientName
     * @return SuiviMedical[]
     */
    public function findByPatientName(?string $patientName): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.patient', 'p')
            ->addSelect('p');

        if ($patientName) {
            $qb->andWhere('p.prename LIKE :patientName')
               ->setParameter('patientName', '%' . $patientName . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
