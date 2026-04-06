<?php
/**
 * Dashboard Controller
 * Painel principal com estatísticas
 */

require_once MODELS_PATH . '/Accident.php';
require_once MODELS_PATH . '/Employee.php';
require_once MODELS_PATH . '/EPI.php';
require_once MODELS_PATH . '/Risk.php';
require_once MODELS_PATH . '/Training.php';

class DashboardController {
    
    public static function index() {
        try {
            // Coleta dados para o dashboard
            $stats = self::getStats();
        } catch (Exception $e) {
            error_log("Dashboard Error: " . $e->getMessage());
            error_log("Stack: " . $e->getTraceAsString());
            
            $stats = [
                'total_accidents' => 0,
                'recent_accidents_30d' => 0,
                'accidents_by_status' => [],
                'last_accidents' => [],
                'total_employees' => 0,
                'total_epis' => 0,
                'total_risks' => 0,
                'risks_by_level' => [],
                'total_trainings' => 0
            ];
        }
        
        // Incluir view
        include VIEWS_PATH . '/dashboard/index.php';
    }
    
    public static function getStats() {
        try {
            // Acidentes
            $accidentModel = new Accident();
            $totalAccidents = $accidentModel->count() ?? 0;
            $recentAccidents = $accidentModel->getRecentCount(30) ?? 0;
            $accidentsByStatus = $accidentModel->countByStatus() ?? [];
            $lastAccidents = $accidentModel->getAll(5, 0) ?? [];
        } catch (Exception $e) {
            $totalAccidents = 0;
            $recentAccidents = 0;
            $accidentsByStatus = [];
            $lastAccidents = [];
        }
        
        try {
            // Colaboradores
            $empModel = new Employee();
            $totalEmployees = $empModel->count() ?? 0;
        } catch (Exception $e) {
            $totalEmployees = 0;
        }
        
        try {
            // EPIs
            $epiModel = new EPI();
            $totalEPIs = $epiModel->count() ?? 0;
        } catch (Exception $e) {
            $totalEPIs = 0;
        }
        
        try {
            // Riscos
            $riskModel = new Risk();
            $totalRisks = $riskModel->count() ?? 0;
            $risksByLevel = $riskModel->getCountByLevel() ?? [];
        } catch (Exception $e) {
            $totalRisks = 0;
            $risksByLevel = [];
        }
        
        try {
            // Treinamentos
            $trainingModel = new Training();
            $totalTrainings = $trainingModel->count() ?? 0;
        } catch (Exception $e) {
            $totalTrainings = 0;
        }
        
        return [
            'total_accidents' => $totalAccidents,
            'recent_accidents_30d' => $recentAccidents,
            'accidents_by_status' => $accidentsByStatus,
            'last_accidents' => $lastAccidents,
            'total_employees' => $totalEmployees,
            'total_epis' => $totalEPIs,
            'total_risks' => $totalRisks,
            'risks_by_level' => $risksByLevel,
            'total_trainings' => $totalTrainings
        ];
    }
}
?>
