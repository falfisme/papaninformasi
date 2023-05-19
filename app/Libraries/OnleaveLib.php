<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class OnleaveLib
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->qh = new QueryHelper;
    }


    public function index($filter = [], $page = 1, $limit = false, $order = "id DESC")
    {
        $allowed = ['id', 'terdaftar', 'username', 'email', 'type', 'last_login'];
        $allowed_map = [];
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM onleave WHERE 1";
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

    public function index2()
    {
        $this->db = \Config\Database::connect();
        $model = $this->db->query("SELECT onleave.date_created, onleave.date_start,  onleave.date_end, onleave.task, onleave.active, onleave.id, onleave.id_createdby, account.nama, account.jabatan, (SELECT nama from `account` where account.id = onleave.id_createdby) as 'namapembuat' FROM `onleave` LEFT JOIN `account` ON account.id = onleave.id_user ORDER BY onleave.date_created DESC; ")
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
    
        $model = $this->db->query("SELECT onleave.date_start, onleave.task, onleave.active, account.nama, account.jabatan FROM `onleave` LEFT JOIN `account` ON account.id = onleave.id_user ORDER BY onleave.date_start DESC; ")
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
            "SELECT * FROM onleave WHERE LOWER(username) = ? AND deleted = 0",
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
            "SELECT * FROM onleave WHERE LOWER(id) = ? AND deleted = 0",
            [$id]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function insert($data)
    {
        $allowed = ['id','id_user','date_start','date_end','task','active','deleted','date_created','date_updated','id_createdby'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('onleave')->insert($model);
        // $this->db->table('onleave')->insert($data);
        $iid = $this->db->insertID();
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Add onleave success', 'id' => $iid];
    }

    public function update($data)
    {
        $allowed = ['id','id_user','date_start','date_end','task','active','deleted','date_created','date_updated','id_createdby'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('onleave')->update($model, ['id' => $data['id']]);
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
        $this->db->table('onleave')->delete(['id' => $id]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Delete success.', 'affected' => $affected];
    }


}
