<?php

namespace App\Repository;

use App\Entity\Chirurgie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chirurgie>
 */
class ChirurgieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chirurgie::class);
    }

    /**
     * Recherche les chirurgies par le nom du patient.
     *
     * @param string $patientName
     * @return Chirurgie[]
     */
    public function findByPatientName(string $patientName): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.patient', 'p') // Jointure avec l'entité Patient
            ->where('p.prename LIKE :patientName') // Filtre par le prénom du patient
            ->setParameter('patientName', '%' . $patientName . '%')
            ->getQuery()
            ->getResult();
    }
}