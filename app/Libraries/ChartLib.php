<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class ChartLib
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
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM chart WHERE 1";
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
        $model = $this->db->query("SELECT * FROM `penyerapan` ORDER BY id DESC; ")
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
        $model = $this->db->query("SELECT date_start, keterangan, location, pic, active FROM `chart` WHERE active = 1")
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
            "SELECT * FROM chart WHERE LOWER(username) = ? AND deleted = 0",
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
            "SELECT * FROM penyerapan as t1 LEFT JOIN penyerapan_data as t2 on t1.id = t2.id_penyerapan where id = $id"
        )->getResultArray();

        $k = $val = $id_data = [];

        foreach ($model as $key => $value) {
            $k[$key] = $value['k'];
            $val[$key] = $value['val'];
            $id_data[$key] = $value['id_data'];
        };

        $model[0]['k'] = $k;
        $model[0]['val'] = $val;
        $model[0]['id_data'] = $id_data;


        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model[0]];
    }

    public function insert($data)
    {
        $allowed1 = ['id', 'title', 'ket_1', 'ket_2', 'active']; //penyerapan
        $allowed2 = ['id_data', 'id_penyerapan', 'k', 'val']; //penyerapan data

        $penyerapan = array_intersect_key(
            $data,  // the array with all keys
            array_flip($allowed1) // keys to be extracted
        );

        $model1 = $this->qh->setInput($penyerapan, $allowed1);
        $this->db->table('penyerapan')->insert($model1);
        $iid = $this->db->insertID();

        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }

        $penyerapandata_temp = array_intersect_key(
            $data,  // the array with all keys
            array_flip($allowed2) // keys to be extracted
        );

        foreach ($penyerapandata_temp['k'] as $key => $value) {
           $model2 = [
            'k' => $value,
            'val' => $penyerapandata_temp['val']->$key,
            'id_penyerapan' => $iid,
           ];
            $this->db->table('penyerapan_data')->insert($model2);
        };
        
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Add chart success', 'id' => $iid];
    }

    public function update($data)
    {
        $allowed1 = ['id', 'title', 'ket_1', 'ket_2', 'active']; //penyerapan
        $allowed2 = ['id_data', 'id_penyerapan', 'k', 'val']; //penyerapan data

        $penyerapan = array_intersect_key(
            $data,  // the array with all keys
            array_flip($allowed1) // keys to be extracted
        );

        $model1 = $this->qh->setInput($penyerapan, $allowed1);
        $this->db->table('penyerapan')->update($model1, ['id' => $data['id']]);
        
        $penyerapandata_temp = array_intersect_key(
            $data,  // the array with all keys
            array_flip($allowed2) // keys to be extracted
        );

        $idnya = $data['id'];
        $checkcount = $this->db->query(
            "SELECT * FROM penyerapan as t1 LEFT JOIN penyerapan_data as t2 on t1.id = t2.id_penyerapan where id = $idnya"
        )->getResultArray();

        $jumlahdatabaru = count($penyerapandata_temp['k']);
        $jumlahsebelumnya = (count($checkcount));
        foreach ($penyerapandata_temp['k'] as $key => $value) {
            $model2 = [
             'k' => $value,
             'val' => @$penyerapandata_temp['val'][$key],
             'id_penyerapan' => $idnya,
            ];

            if($jumlahsebelumnya >= $key+1){
                $this->db->table('penyerapan_data')->update($model2, ['id_data' => $penyerapandata_temp['id_data'][$key]]);
            }else{
                $this->db->table('penyerapan_data')->insert($model2);
            }
         };
        
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }

        return ['success' => 'Update success.', 'affected' => $affected];
    }

    public function delete($id)
    {
        $this->db->table('penyerapan')->delete(['id' => $id]);
        $this->db->table('penyerapan_data')->delete(['id_penyerapan' => $id]);

        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Delete success.', 'affected' => $affected];
    }


}
