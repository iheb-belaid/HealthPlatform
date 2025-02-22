<?php 
// src/Service/StatistiquesService.php
namespace App\Service;

use App\Repository\SuiviMedicalRepository;
use App\Repository\ChirurgieRepository;

class StatistiquesService
{
    private $suiviMedicalRepository;
    private $chirurgieRepository;

    public function __construct(
        SuiviMedicalRepository $suiviMedicalRepository,
        ChirurgieRepository $chirurgieRepository
    ) {
        $this->suiviMedicalRepository = $suiviMedicalRepository;
        $this->chirurgieRepository = $chirurgieRepository;
    }

    /**
     * Récupère les statistiques par patient avec les prénoms des patients.
     *
     * @return array
     */
    public function getStatistiquesParPatient(): array
    {
        $suivisParPatient = $this->suiviMedicalRepository->countByPatient();
        $chirurgiesParPatient = $this->chirurgieRepository->countByPatient();

        // Combine les résultats
        $statistiques = [];
        foreach ($suivisParPatient as $suivi) {
            $statistiques[$suivi['patient_id']] = [
                'patient_prename' => $suivi['patient_prename'],
                'suivi_count' => $suivi['suivi_count'],
                'chirurgie_count' => 0,
            ];
        }

        foreach ($chirurgiesParPatient as $chirurgie) {
            if (isset($statistiques[$chirurgie['patient_id']])) {
                $statistiques[$chirurgie['patient_id']]['chirurgie_count'] = $chirurgie['chirurgie_count'];
            } else {
                $statistiques[$chirurgie['patient_id']] = [
                    'patient_prename' => $chirurgie['patient_prename'],
                    'suivi_count' => 0,
                    'chirurgie_count' => $chirurgie['chirurgie_count'],
                ];
            }
        }

        return $statistiques;
    }
}