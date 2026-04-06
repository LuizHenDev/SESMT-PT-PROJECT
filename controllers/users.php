<?php
/**
 * Users Controller (Admin Only)
 * Gestão de usuários do sistema
 */

require_once MODELS_PATH . '/User.php';

class UsersController {
    
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
        $userModel = new User();
        $total = $userModel->count();
        $pagination = getPagination($page, $total);
        $users = $userModel->getAll($pagination['items_per_page'], $pagination['offset']);
        
        include VIEWS_PATH . '/users/list.php';
    }
    
    public static function create() {
        $error = '';
        include VIEWS_PATH . '/users/form.php';
    }
    
    public static function store() {
        try {
            $userModel = new User();
            
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = sanitize($_POST['role'] ?? 'comum');
            
            // Validações
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("Nome, email e senha são obrigatórios.");
            }
            
            if ($password !== $confirm_password) {
                throw new Exception("Senhas não correspondem.");
            }
            
            $userModel->create($name, $email, $password, $role);
            
            setMessage("Usuário criado com sucesso!", 'success');
            redirectTo('users');
            
        } catch (Exception $e) {
            setMessage("Erro ao criar usuário: " . $e->getMessage(), 'error');
            redirectTo('users', 'create');
        }
    }
    
    public static function edit() {
        $id = intval($_GET['id'] ?? 0);
        if ($id === 0) {
            redirectTo('users');
        }
        
        $userModel = new User();
        $user = $userModel->findById($id);
        
        if (!$user) {
            setMessage("Usuário não encontrado.", 'error');
            redirectTo('users');
        }
        
        $error = '';
        include VIEWS_PATH . '/users/form.php';
    }
    
    public static function update() {
        try {
            $id = intval($_POST['id'] ?? 0);
            if ($id === 0) {
                throw new Exception("ID inválido.");
            }
            
            $userModel = new User();
            $user = $userModel->findById($id);
            
            if (!$user) {
                throw new Exception("Usuário não encontrado.");
            }
            
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $role = sanitize($_POST['role'] ?? 'comum');
            
            // Validações
            if (empty($name) || empty($email)) {
                throw new Exception("Nome e email são obrigatórios.");
            }
            
            // Verificar email único
            if ($email !== $user['email'] && !$userModel->isEmailUnique($email, $id)) {
                throw new Exception("Email já cadastrado.");
            }
            
            // Atualizar dados
            $userModel->update($id, [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ]);
            
            // Se houver nova senha, atualizar
            $newPassword = $_POST['password'] ?? '';
            if (!empty($newPassword)) {
                $confirmPassword = $_POST['confirm_password'] ?? '';
                if ($newPassword !== $confirmPassword) {
                    throw new Exception("Senhas não correspondem.");
                }
                $userModel->updatePassword($id, $newPassword);
            }
            
            setMessage("Usuário atualizado com sucesso!", 'success');
            redirectTo('users');
            
        } catch (Exception $e) {
            setMessage("Erro ao atualizar usuário: " . $e->getMessage(), 'error');
            redirectTo('users', 'edit', ['id' => $_POST['id']]);
        }
    }
    
    public static function delete() {
        try {
            $id = intval($_GET['id'] ?? 0);
            if ($id === 0) {
                throw new Exception("ID inválido.");
            }
            
            // Não permitir deletar o próprio usuário
            if ($id === getCurrentUser()['id']) {
                throw new Exception("Você não pode deletar sua própria conta.");
            }
            
            $userModel = new User();
            $userModel->delete($id);
            
            setMessage("Usuário deletado com sucesso!", 'success');
            redirectTo('users');
            
        } catch (Exception $e) {
            setMessage("Erro ao deletar usuário: " . $e->getMessage(), 'error');
            redirectTo('users');
        }
    }
}
?>
