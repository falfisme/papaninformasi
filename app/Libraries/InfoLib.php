<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class InfoLib
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->qh = new QueryHelper;
    }


    public function index2()
    {
        $this->db = \Config\Database::connect();
        $model = $this->db->query("SELECT info.date_created, info.image, info.title, info.active, account.nama, info.id FROM `info` LEFT JOIN `account` ON account.id = info.id_user ORDER BY info.date_created DESC; ")
            ->getResult();
        $result = [
            'success' => 'Get Index success',
            'model' => $model,
        ];
        return $result;
    }

    public function indextv()
    {
        $this->db = \Config\Database::connect();
        $model = $this->db->query("SELECT caption, image, active FROM `info` WHERE active = 1")
            ->getResult();
        $result = [
            'success' => 'Get Index success',
            'model' => $model,
        ];
        return $result;
    }

    public function select($username)
    {
        $model = $this->db->query(
            "SELECT * FROM info WHERE LOWER(username) = ? AND deleted = 0",
            [strtolower($username)]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function selectWithId($id)
    {
        $model = $this->db->query(
            "SELECT * FROM info WHERE LOWER(id) = ? AND deleted = 0",
            [$id]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function insert($data)
    {
        $allowed = ['id', 'image', 'title', 'caption', 'data_created', 'data_updated', 'active', 'id_user', 'deleted'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('info')->insert($model);
        $iid = $this->db->insertID();
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Add info success', 'id' => $iid];
    }

    public function update($data)
    {
        $allowed = ['id', 'image', 'title', 'caption', 'data_created', 'data_updated', 'active', 'id_user', 'deleted'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('info')->update($model, ['id' => $data['id']]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }

        return ['success' => 'Update success.', 'affected' => $affected];
    }

    public function delete($id)
    {
        $this->db->table('info')->delete(['id' => $id]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Delete success.', 'affected' => $affected];
    }


}
