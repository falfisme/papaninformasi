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

    
    public function selectInfo()
    {
        $token = @$_COOKIE['token'];
        $info_id = $this->db->query(
            "SELECT info_id FROM token WHERE token = ?",
            [$token]
        )->getResult();
        $info_id_asli = $info_id[0]->info_id;
        $username = $this->db->query(
            "SELECT * FROM info WHERE id = ?",
            [$info_id_asli]
        )->getRow();

        $data = array(
            'username' => $username->username,
            'type'  => $username->type
        );
        return $data;
    }

    public function index($filter = [], $page = 1, $limit = false, $order = "id DESC")
    {
        $allowed = ['id', 'terdaftar', 'username', 'email', 'type', 'last_login'];
        $allowed_map = [];
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM info WHERE 1";
        $condition = $this->qh->setFilter($query, $filter, $allowed, $allowed_map);
        $this->qh->setOrder($query, $order);
        $this->qh->setLimit($query, $page, $limit);
        $model = $this->db->query($query, $condition)->getResult();
        $total = $this->db->query("SELECT FOUND_ROWS() as total")->getRow();
        $result = [
            'success' => 'Get index success.',
            'model' => $model,
            'pagination' => ['page' => $page, 'limit' => $limit, 'total' => $total->total]
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
        // $this->db->table('info')->insert($data);
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

        // var_dump($affected);
        // die();

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
