<?php
/**
 * Risks Controller
 * Gestão de Riscos
 */

require_once MODELS_PATH . '/Risk.php';

class RisksController {
    
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
        global $RISK_LEVELS;
        
        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        $level = isset($_GET['level']) ? sanitize($_GET['level']) : null;
        
        $model = new Risk();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $risks = $model->getAll($pagination['items_per_page'], $pagination['offset'], $level);
        
        include VIEWS_PATH . '/risks/list.php';
    }
    
    public static function create() {
        global $RISK_LEVELS;
        
        $error = '';
        include VIEWS_PATH . '/risks/form.php';
    }
    
    public static function store() {
        try {
            $model = new Risk();
            
            $data = [
                'description' => sanitize($_POST['description'] ?? ''),
                'level' => sanitize($_POST['level'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'control_measures' => sanitize($_POST['control_measures'] ?? ''),
                'responsible_person' => sanitize($_POST['responsible_person'] ?? '')
            ];
            
            $model->create($data);
            
            setMessage("Risco criado com sucesso!", 'success');
            redirectTo('risks');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('risks', 'create');
        }
    }
    
    public static function edit() {
        global $RISK_LEVELS;
        
        $id = intval($_GET['id'] ?? 0);
        $model = new Risk();
        $risk = $model->findById($id);
        
        if (!$risk) {
            setMessage("Risco não encontrado.", 'error');
            redirectTo('risks');
        }
        
        $error = '';
        include VIEWS_PATH . '/risks/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new Risk();
            
            $data = [
                'description' => sanitize($_POST['description'] ?? ''),
                'level' => sanitize($_POST['level'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'control_measures' => sanitize($_POST['control_measures'] ?? ''),
                'responsible_person' => sanitize($_POST['responsible_person'] ?? '')
            ];
            
            $model->update($id, $data);
            
            setMessage("Risco atualizado com sucesso!", 'success');
            redirectTo('risks');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('risks', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new Risk();
            $model->delete($id);
            
            setMessage("Risco deletado com sucesso!", 'success');
            redirectTo('risks');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('risks');
        }
    }
}
?>
