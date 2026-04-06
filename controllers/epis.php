<?php
/**
 * EPIs Controller
 * Equipamentos de Proteção Individual
 */

require_once MODELS_PATH . '/EPI.php';
require_once MODELS_PATH . '/Employee.php';
require_once MODELS_PATH . '/Setting.php';

class EpisController {
    
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
            case 'delivery':
                self::deliveryForm();
                break;
            case 'storeDelivery':
                self::storeDelivery();
                break;
            default:
                self::list();
                break;
        }
    }
    
    public static function list() {
        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        
        $model = new EPI();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $epis = $model->getAll($pagination['items_per_page'], $pagination['offset']);
        
        include VIEWS_PATH . '/epis/list.php';
    }
    
    public static function create() {
        $error = '';
        include VIEWS_PATH . '/epis/form.php';
    }
    
    public static function store() {
        try {
            $model = new EPI();
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'type' => sanitize($_POST['type'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'quantity' => intval($_POST['quantity'] ?? 0),
                'unit_cost' => floatval($_POST['unit_cost'] ?? 0)
            ];
            
            $model->create($data);
            
            setMessage("EPI criado com sucesso!", 'success');
            redirectTo('epis');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('epis', 'create');
        }
    }
    
    public static function edit() {
        $id = intval($_GET['id'] ?? 0);
        $model = new EPI();
        $epi = $model->findById($id);
        
        if (!$epi) {
            setMessage("EPI não encontrado.", 'error');
            redirectTo('epis');
        }
        
        $error = '';
        include VIEWS_PATH . '/epis/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new EPI();
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'type' => sanitize($_POST['type'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'quantity' => intval($_POST['quantity'] ?? 0),
                'unit_cost' => floatval($_POST['unit_cost'] ?? 0)
            ];
            
            $model->update($id, $data);
            
            setMessage("EPI atualizado com sucesso!", 'success');
            redirectTo('epis');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('epis', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new EPI();
            $model->delete($id);
            
            setMessage("EPI deletado com sucesso!", 'success');
            redirectTo('epis');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('epis');
        }
    }
    
    public static function deliveryForm() {
        global $EPI_CONDITIONS;
        
        $EPI_CONDITIONS = Setting::getOptionsByType('epi_condition', $EPI_CONDITIONS);
        $id = intval($_GET['id'] ?? 0);
        $epiModel = new EPI();
        $epi = $epiModel->findById($id);
        
        if (!$epi) {
            setMessage("EPI não encontrado.", 'error');
            redirectTo('epis');
        }
        
        $empModel = new Employee();
        $employees = $empModel->getAll();
        
        include VIEWS_PATH . '/epis/delivery.php';
    }
    
    public static function storeDelivery() {
        try {
            $deliveryModel = new EPIDelivery();
            
            $data = [
                'epi_id' => intval($_POST['epi_id'] ?? 0),
                'employee_id' => intval($_POST['employee_id'] ?? 0),
                'delivery_date' => $_POST['delivery_date'] ?? '',
                'quantity' => intval($_POST['quantity'] ?? 1),
                'condition' => sanitize($_POST['condition'] ?? 'novo'),
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'notes' => sanitize($_POST['notes'] ?? '')
            ];
            
            $deliveryModel->create($data);
            
            setMessage("Entrega de EPI registrada com sucesso!", 'success');
            redirectTo('epis');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('epis', 'delivery', ['id' => $_POST['epi_id']]);
        }
    }
}
?>
