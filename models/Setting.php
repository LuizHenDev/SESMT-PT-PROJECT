<?php
/**
 * Setting Model
 * Gerencia valores dinâmicos para listas de seleção do sistema.
 */

require_once __DIR__ . '/../config/database.php';

class Setting {
    public function getAll($type, $activeOnly = true) {
        $query = "SELECT * FROM settings WHERE type = ?";
        $params = [$type];
        $types = 's';

        if ($activeOnly) {
            $query .= " AND active = 1";
        }

        $query .= " ORDER BY sort_order ASC, label ASC";

        return Database::getInstance()->fetchAll($query, $params, $types);
    }

    public function getOptions($type, $fallback = []) {
        $items = $this->getAll($type, true);
        if (empty($items)) {
            return $fallback;
        }

        $options = [];
        foreach ($items as $item) {
            $options[$item['code']] = $item['label'];
        }

        return $options;
    }

    public static function getOptionsByType($type, $fallback = []) {
        $model = new self();
        return $model->getOptions($type, $fallback);
    }

    public function findById($id) {
        $query = "SELECT * FROM settings WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }

    public function create($data) {
        $query = "INSERT INTO settings (type, code, label, active, sort_order) VALUES (?, ?, ?, ?, ?)";
        return Database::getInstance()->query($query, [
            $data['type'],
            $data['code'],
            $data['label'],
            $data['active'],
            $data['sort_order']
        ], 'sssii');
    }

    public function update($id, $data) {
        $query = "UPDATE settings SET code = ?, label = ?, active = ?, sort_order = ? WHERE id = ?";
        return Database::getInstance()->query($query, [
            $data['code'],
            $data['label'],
            $data['active'],
            $data['sort_order'],
            $id
        ], 'ssiii');
    }

    public function delete($id) {
        $query = "DELETE FROM settings WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
