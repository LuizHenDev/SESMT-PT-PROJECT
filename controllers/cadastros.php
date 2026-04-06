<?php
/**
 * Cadastros Controller
 * Gerencia cadastros dinâmicos de tipos e status do sistema.
 */

require_once MODELS_PATH . '/Setting.php';

class CadastrosController {

    public static function handle($action) {
        requireAdmin();

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
        global $SETTING_TYPES;

        $type = sanitize($_GET['type'] ?? 'pt_type');
        if (!isset($SETTING_TYPES[$type])) {
            $type = array_key_first($SETTING_TYPES);
        }

        $model = new Setting();
        $items = $model->getAll($type, false);

        include VIEWS_PATH . '/cadastros/list.php';
    }

    public static function create() {
        global $SETTING_TYPES;

        $type = sanitize($_GET['type'] ?? 'pt_type');
        if (!isset($SETTING_TYPES[$type])) {
            $type = array_key_first($SETTING_TYPES);
        }

        $item = null;
        $error = '';
        include VIEWS_PATH . '/cadastros/form.php';
    }

    public static function store() {
        global $SETTING_TYPES;

        $type = sanitize($_POST['type'] ?? 'pt_type');
        if (!isset($SETTING_TYPES[$type])) {
            throw new Exception('Tipo de cadastro inválido.');
        }

        $code = sanitize($_POST['code'] ?? '');
        $label = sanitize($_POST['label'] ?? '');
        $active = isset($_POST['active']) ? 1 : 0;
        $sortOrder = intval($_POST['sort_order'] ?? 0);

        if (empty($code) || empty($label)) {
            throw new Exception('Código e descrição são obrigatórios.');
        }

        $model = new Setting();
        $model->create([
            'type' => $type,
            'code' => $code,
            'label' => $label,
            'active' => $active,
            'sort_order' => $sortOrder
        ]);

        setMessage('Cadastro salvo com sucesso!', 'success');
        redirectTo('cadastros', '', ['type' => $type]);
    }

    public static function edit() {
        global $SETTING_TYPES;

        $id = intval($_GET['id'] ?? 0);
        $model = new Setting();
        $item = $model->findById($id);

        if (!$item) {
            setMessage('Cadastro não encontrado.', 'error');
            redirectTo('cadastros');
        }

        $type = $item['type'];
        if (!isset($SETTING_TYPES[$type])) {
            $type = array_key_first($SETTING_TYPES);
        }

        $error = '';
        include VIEWS_PATH . '/cadastros/form.php';
    }

    public static function update() {
        global $SETTING_TYPES;

        $id = intval($_POST['id'] ?? 0);
        $type = sanitize($_POST['type'] ?? 'pt_type');
        if (!isset($SETTING_TYPES[$type])) {
            throw new Exception('Tipo de cadastro inválido.');
        }

        $code = sanitize($_POST['code'] ?? '');
        $label = sanitize($_POST['label'] ?? '');
        $active = isset($_POST['active']) ? 1 : 0;
        $sortOrder = intval($_POST['sort_order'] ?? 0);

        if (empty($code) || empty($label)) {
            throw new Exception('Código e descrição são obrigatórios.');
        }

        $model = new Setting();
        $model->update($id, [
            'code' => $code,
            'label' => $label,
            'active' => $active,
            'sort_order' => $sortOrder
        ]);

        setMessage('Cadastro atualizado com sucesso!', 'success');
        redirectTo('cadastros', '', ['type' => $type]);
    }

    public static function delete() {
        $id = intval($_GET['id'] ?? 0);

        $model = new Setting();
        $item = $model->findById($id);

        if (!$item) {
            setMessage('Cadastro não encontrado.', 'error');
            redirectTo('cadastros');
        }

        $model->delete($id);
        setMessage('Cadastro removido com sucesso!', 'success');
        redirectTo('cadastros', '', ['type' => $item['type']]);
    }
}
