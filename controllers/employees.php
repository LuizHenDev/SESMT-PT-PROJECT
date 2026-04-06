<?php
/**
 * Employees Controller
 * Gestão de Colaboradores
 */

require_once MODELS_PATH . '/Employee.php';
require_once MODELS_PATH . '/Setting.php';

class EmployeesController {
    
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
        global $EMPLOYEE_STATUS;
        $EMPLOYEE_STATUS = Setting::getOptionsByType('employee_status', $EMPLOYEE_STATUS);

        $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
        $status = isset($_GET['status']) ? sanitize($_GET['status']) : null;
        
        $model = new Employee();
        $total = $model->count();
        $pagination = getPagination($page, $total);
        $employees = $model->getAll($pagination['items_per_page'], $pagination['offset'], $status);
        
        include VIEWS_PATH . '/employees/list.php';
    }
    
    public static function create() {
        global $EMPLOYEE_STATUS;
        $EMPLOYEE_STATUS = Setting::getOptionsByType('employee_status', $EMPLOYEE_STATUS);

        $error = '';
        include VIEWS_PATH . '/employees/form.php';
    }
    
    public static function store() {
        try {
            $model = new Employee();
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'cpf' => sanitize($_POST['cpf'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'job_title' => sanitize($_POST['job_title'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'hire_date' => $_POST['hire_date'] ?? '',
                'phone' => sanitize($_POST['phone'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'ativo')
            ];
            
            $model->create($data);
            
            setMessage("Colaborador criado com sucesso!", 'success');
            redirectTo('employees');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('employees', 'create');
        }
    }
    
    public static function edit() {
        global $EMPLOYEE_STATUS;
        $EMPLOYEE_STATUS = Setting::getOptionsByType('employee_status', $EMPLOYEE_STATUS);

        $id = intval($_GET['id'] ?? 0);
        if ($id === 0) {
            redirectTo('employees');
        }
        
        $model = new Employee();
        $employee = $model->findById($id);
        
        if (!$employee) {
            setMessage("Colaborador não encontrado.", 'error');
            redirectTo('employees');
        }
        
        $error = '';
        include VIEWS_PATH . '/employees/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            $model = new Employee();
            $employee = $model->findById($id);
            
            if (!$employee) {
                throw new Exception("Colaborador não encontrado.");
            }
            
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'job_title' => sanitize($_POST['job_title'] ?? ''),
                'department' => sanitize($_POST['department'] ?? ''),
                'phone' => sanitize($_POST['phone'] ?? ''),
                'status' => sanitize($_POST['status'] ?? 'ativo')
            ];
            
            $model->update($id, $data);
            
            setMessage("Colaborador atualizado com sucesso!", 'success');
            redirectTo('employees');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('employees', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            $model = new Employee();
            $model->delete($id);
            
            setMessage("Colaborador deletado com sucesso!", 'success');
            redirectTo('employees');
            
        } catch (Exception $e) {
            setMessage("Erro: " . $e->getMessage(), 'error');
            redirectTo('employees');
        }
    }
}
?>
