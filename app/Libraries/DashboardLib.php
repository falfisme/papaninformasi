<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class DashboardLib
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->qh = new QueryHelper;
    }

    public function indextv()
    {
        $this->db = \Config\Database::connect();
        $model = $this->db->query("SELECT caption, image, active FROM `dashboard` WHERE active = 1")
            ->getResult();
        $result = [
            'success' => 'Get Index success',
            'model' => $model,
        ];
        return $result;
    }

    public function selectWithId($id)
    {
        $model = $this->db->query(
            "SELECT * FROM dashboard WHERE LOWER(id) = ?",
            [$id]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }


    public function update($data)
    {
        $allowed = ['id', 'url', 'title', 'company_name', 'alamat', 'email', 'telp', 'logo', 'id_user', 'last_update'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('dashboard')->update($model, ['id' => $data['id']]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }

        return ['success' => 'Update success.', 'affected' => $affected];
    }

}
