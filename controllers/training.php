<?php
/**
 * Training Controller
 * Gestão de Treinamentos
 */

require_once MODELS_PATH . '/Training.php';
require_once MODELS_PATH . '/Employee.php';

class TrainingController {
    
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
        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        
        $model = new Training();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $trainings = $model->getAll($pagination['items_per_page'], $pagination['offset']);
        
        include VIEWS_PATH . '/training/list.php';
    }
    
    public static function create() {
        $error = '';
        include VIEWS_PATH . '/training/form.php';
    }
    
    public static function store() {
        try {
            $model = new Training();
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'is_mandatory' => isset($_POST['is_mandatory']) ? 1 : 0,
                'duration_hours' => intval($_POST['duration_hours'] ?? 0),
                'content' => sanitize($_POST['content'] ?? '')
            ];
            
            $model->create($data);
            
            setMessage("Treinamento criado com sucesso!", 'success');
            redirectTo('training');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('training', 'create');
        }
    }
    
    public static function edit() {
        $id = intval($_GET['id'] ?? 0);
        $model = new Training();
        $training = $model->findById($id);
        
        if (!$training) {
            setMessage("Treinamento não encontrado.", 'error');
            redirectTo('training');
        }
        
        $error = '';
        include VIEWS_PATH . '/training/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new Training();
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'is_mandatory' => isset($_POST['is_mandatory']) ? 1 : 0,
                'duration_hours' => intval($_POST['duration_hours'] ?? 0),
                'content' => sanitize($_POST['content'] ?? '')
            ];
            
            $model->update($id, $data);
            
            setMessage("Treinamento atualizado com sucesso!", 'success');
            redirectTo('training');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('training', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new Training();
            $model->delete($id);
            
            setMessage("Treinamento deletado com sucesso!", 'success');
            redirectTo('training');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('training');
        }
    }
}
?>
