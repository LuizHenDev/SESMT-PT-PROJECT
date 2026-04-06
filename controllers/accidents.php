<?php
/**
 * Accidents Controller
 * Registro de Acidentes
 */

require_once MODELS_PATH . '/Accident.php';
require_once MODELS_PATH . '/Employee.php';
require_once MODELS_PATH . '/Setting.php';

class AccidentsController {
    
    public static function handle($action) {
        switch ($action) {
            case 'create':
                self::create();
                break;
            case 'store':
                self::store();
                break;
            case 'edit':
                self::edit();
                break;
            case 'update':
                self::update();
                break;
            case 'delete':
                self::delete();
                break;
            default:
                self::list();
                break;
        }
    }
    
    public static function list() {
        global $ACCIDENT_STATUS;
        
        $ACCIDENT_STATUS = Setting::getOptionsByType('accident_status', $ACCIDENT_STATUS);
        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        
        $model = new Accident();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $accidents = $model->getAll($pagination['items_per_page'], $pagination['offset']);
        
        include VIEWS_PATH . '/accidents/list.php';
    }
    
    public static function create() {
        global $ACCIDENT_STATUS;
        
        $ACCIDENT_STATUS = Setting::getOptionsByType('accident_status', $ACCIDENT_STATUS);
        $empModel = new Employee();
        $employees = $empModel->getAll();
        $error = '';
        
        include VIEWS_PATH . '/accidents/form.php';
    }
    
    public static function store() {
        try {
            $model = new Accident();
            
            $data = [
                'employee_id' => intval($_POST['employee_id'] ?? 0),
                'accident_date' => $_POST['accident_date'] ?? '',
                'accident_time' => $_POST['accident_time'] ?? '',
                'description' => sanitize($_POST['description'] ?? ''),
                'injury_type' => sanitize($_POST['injury_type'] ?? ''),
                'body_part' => sanitize($_POST['body_part'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'aberto'),
                'action_plan' => sanitize($_POST['action_plan'] ?? ''),
                'investigation_responsible' => sanitize($_POST['investigation_responsible'] ?? '')
            ];
            
            $model->create($data);
            
            setMessage("Acidente registrado com sucesso!", 'success');
            redirectTo('accidents');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('accidents', 'create');
        }
    }
    
    public static function edit() {
        global $ACCIDENT_STATUS;
        
        $ACCIDENT_STATUS = Setting::getOptionsByType('accident_status', $ACCIDENT_STATUS);
        $id = intval($_GET['id'] ?? 0);
        $model = new Accident();
        $accident = $model->findById($id);
        
        if (!$accident) {
            setMessage("Acidente não encontrado.", 'error');
            redirectTo('accidents');
        }
        
        $empModel = new Employee();
        $employees = $empModel->getAll();
        $error = '';
        
        include VIEWS_PATH . '/accidents/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new Accident();
            
            $data = [
                'employee_id' => intval($_POST['employee_id'] ?? 0),
                'accident_date' => $_POST['accident_date'] ?? '',
                'accident_time' => $_POST['accident_time'] ?? '',
                'description' => sanitize($_POST['description'] ?? ''),
                'injury_type' => sanitize($_POST['injury_type'] ?? ''),
                'body_part' => sanitize($_POST['body_part'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'aberto'),
                'action_plan' => sanitize($_POST['action_plan'] ?? ''),
                'investigation_responsible' => sanitize($_POST['investigation_responsible'] ?? '')
            ];
            
            $model->update($id, $data);
            
            setMessage("Acidente atualizado com sucesso!", 'success');
            redirectTo('accidents');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('accidents', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new Accident();
            $model->delete($id);
            
            setMessage("Acidente deletado com sucesso!", 'success');
            redirectTo('accidents');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('accidents');
        }
    }
}
?>
