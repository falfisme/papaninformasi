<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class EventLib
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
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM event WHERE 1";
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
        $model = $this->db->query("SELECT event.date_created, event.date_start, event.location, event.keterangan, event.active, event.pic, event.id, account.nama FROM `event` LEFT JOIN `account` ON account.id = event.id_user ORDER BY event.date_created DESC; ")
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
        $model = $this->db->query("SELECT date_start, keterangan, location, pic, active FROM `event` WHERE active = 1")
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
            "SELECT * FROM event WHERE LOWER(username) = ? AND deleted = 0",
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
            "SELECT * FROM event WHERE LOWER(id) = ? AND deleted = 0",
            [$id]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function insert($data)
    {
        $allowed = ['id', 'date_created', 'date_updated', 'date_start', 'date_end', 'location',  'pic', 'status',  'active', 'keterangan', 'id_createdby', 'id_user', 'deleted'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('event')->insert($model);
        // $this->db->table('event')->insert($data);
        $iid = $this->db->insertID();
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Add event success', 'id' => $iid];
    }

    public function update($data)
    {
        $allowed = ['id', 'date_created', 'date_updated', 'date_start', 'date_end', 'location',  'pic', 'status',  'active', 'keterangan', 'id_createdby', 'id_user', 'deleted'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('event')->update($model, ['id' => $data['id']]);
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
        $this->db->table('event')->delete(['id' => $id]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Delete success.', 'affected' => $affected];
    }


}
