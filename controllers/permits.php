<?php
/**
 * Permits Controller
 * Permissão de Trabalho (PT)
 */

require_once MODELS_PATH . '/WorkPermit.php';
require_once MODELS_PATH . '/Employee.php';
require_once MODELS_PATH . '/Setting.php';

class PermitsController {
    
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
        global $PT_TYPES;
        
        $PT_TYPES = Setting::getOptionsByType('pt_type', $PT_TYPES);
        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        
        $model = new WorkPermit();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $permits = $model->getAll($pagination['items_per_page'], $pagination['offset']);
        
        include VIEWS_PATH . '/permits/list.php';
    }
    
    public static function create() {
        global $PT_TYPES;
        
        $PT_TYPES = Setting::getOptionsByType('pt_type', $PT_TYPES);
        $empModel = new Employee();
        $employees = $empModel->getAll();
        $error = '';
        
        include VIEWS_PATH . '/permits/form.php';
    }
    
    public static function store() {
        try {
            $model = new WorkPermit();
            
            $data = [
                'employee_id' => intval($_POST['employee_id'] ?? 0),
                'type' => sanitize($_POST['type'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'issue_date' => $_POST['issue_date'] ?? '',
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'validated_by' => sanitize($_POST['validated_by'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'ativa')
            ];
            
            $model->create($data);
            
            setMessage("Permissão de Trabalho criada com sucesso!", 'success');
            redirectTo('permits');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('permits', 'create');
        }
    }
    
    public static function edit() {
        global $PT_TYPES;
        
        $PT_TYPES = Setting::getOptionsByType('pt_type', $PT_TYPES);
        $id = intval($_GET['id'] ?? 0);
        $model = new WorkPermit();
        $permit = $model->findById($id);
        
        if (!$permit) {
            setMessage("Permissão não encontrada.", 'error');
            redirectTo('permits');
        }
        
        $empModel = new Employee();
        $employees = $empModel->getAll();
        $error = '';
        
        include VIEWS_PATH . '/permits/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new WorkPermit();
            
            $data = [
                'type' => sanitize($_POST['type'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'issue_date' => $_POST['issue_date'] ?? '',
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'validated_by' => sanitize($_POST['validated_by'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'ativa')
            ];
            
            $model->update($id, $data);
            
            setMessage("Permissão atualizada com sucesso!", 'success');
            redirectTo('permits');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('permits', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new WorkPermit();
            $model->delete($id);
            
            setMessage("Permissão deletada com sucesso!", 'success');
            redirectTo('permits');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('permits');
        }
    }
}
?>
