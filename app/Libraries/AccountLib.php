<?php

namespace App\Libraries;

use App\Helpers\QueryHelper;


class AccountLib
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->qh = new QueryHelper;
    }

    public function signCheck()
    {
        $this->token = @$_COOKIE['token'];
        $model = $this->tokenSelect($this->token);
        // print_r($model);
        if (!empty($model['error'])) {
            return ['error' => 'Token required'];
        }
        $this->tokenUpdate($this->token);
        return $model;
    }

    public function signIn($username, $password)
    {
        $query = $this->select($username);
    
        if (!empty($query['error'])) {
            return ['error' => 'invalid username.'];
        }
        $pass = password_verify($password, $query['model']->password);

        if (!$pass) {
            return ['error' => 'Wrong password.'];
        }

        // var_dump($query['model']);


        unset($query['model']->password, $query['model']->reset_pass, $query['model']->reset_exp);
        $query['model']->last_login = date('Y-m-d H:i:s');

        $datanew = $query['model'];

        $result = $this->update((array) $datanew);
        if (!empty($result['error'])) {
            return $result;
        }

        $token = $this->tokenInsert($query['model']->id);
        setcookie('token', $token['token'], time() + (86400 * 30), "/"); // 86400 = 1 day
        // die();
        return ['success' => 'Login success.', 'model' => $query['model'], 'token' => $token['token']];
        
    }

    public function signOut($token)
    {
        $this->tokenDelete($token);
    }

    public function selectAccount()
    {
        $token = @$_COOKIE['token'];
        $account_id = $this->db->query(
            "SELECT account_id FROM token WHERE token = ?",
            [$token]
        )->getResult();
        $account_id_asli = $account_id[0]->account_id;
        $username = $this->db->query(
            "SELECT * FROM account WHERE id = ?",
            [$account_id_asli]
        )->getRow();

        $data = array(
            'username' => $username->username,
            'type'  => $username->type
        );
        return $data;
    }
    public function resetPassword($username)
    {
        $query = $this->select($username);
        if (!empty($query['error'])) {
            return ['error' => 'invalid username.'];
        }
        $reset_pass = bin2hex(openssl_random_pseudo_bytes(4));
        $reset_exp = date("Y-m-d", time() + 86400);
        $result = $this->update($id, [
            'reset_pass' => $reset_pass,
            'reset_exp' => $reset_exp,
        ]);
        if (!empty($result['error'])) {
            return ['error' => 'error updating password reset.'];
        }
        unset($query['model']->password, $query['model']->reset_pass, $query['model']->reset_exp);
        $query['model']->reset_pass = $reset_pass;
        $query['model']->reset_exp = $reset_exp;
        return ['success' => 'Reset password success', 'model' => $query['model']];
    }

    public function index($filter = [], $page = 1, $limit = false, $order = "id DESC")
    {
        $allowed = ['id', 'terdaftar', 'username', 'email', 'type', 'last_login'];
        $allowed_map = [];
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM account WHERE 1";
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
            "SELECT * FROM account WHERE LOWER(username) = ? AND deleted = 0",
            [strtolower($username)]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    // nomor hp
    public function selectwithnumber($username)
    {
        $model = $this->db->query(
            "SELECT * FROM v_petugas WHERE nohp = ? AND posisi = 1",
            [$username]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function selectWithId($id)
    {
        $model = $this->db->query(
            "SELECT * FROM account WHERE LOWER(id) = ? AND deleted = 0",
            [$id]
        )->getRow();
        if (empty($model)) {
            return ['error' => 'empty result'];
        }
        return ['success' => 'success', 'model' => $model];
    }

    public function insert($data)
    {
        $data['password'] = $data['passwordx'];
        unset($data['passwordx']);
        if ($data['password'] == $data['confirm']) {
            unset($data['confirm']);
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->table('account')->insert($data);
        } else {
            return ['error' => 'Password tidak sama.'];
        }

        $iid = $this->db->insertID();
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Add account success, can login now.', 'id' => $iid];
    }

    public function update($data)
    {
        if (isset($data['passwordx'])) {
            $data['password'] = $data['passwordx'];
            unset($data['passwordx']);
        }

        if (isset($data['password']) && isset($data['confirm'])) {
            if ($data['password'] == $data['confirm']) {
                unset($data['confirm']);
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        }

        $allowed = ['id', 'username', 'email', 'password', 'type', 'nama', 'telepon', 'alamat', 'last_login', 'reset_exp', 'reset_pass', 'jabatan', 'image'];
        $model = $this->qh->setInput($data, $allowed);
        $this->db->table('account')->update($model, ['id' => $data['id']]);
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
        $this->db->table('account')->delete(['id' => $id]);
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'No changes.'];
        }
        return ['success' => 'Delete success.', 'affected' => $affected];
    }

    public function tokenSelect($token)
    {
        if (empty($token)) {
            return ['error' => 'Empty token.'];
        }
        $header = $_SERVER['HTTP_USER_AGENT'] . " | " . $_SERVER['REMOTE_ADDR'];
        $query = "SELECT a.*, t.token FROM token as t JOIN account as a ON a.id = t.account_id 
            WHERE a.deleted = 0 AND t.token = ? AND t.header = ? 
            AND t.last_access >= (NOW() - INTERVAL 3 DAY)";
        $model = $this->db->query($query, [$token, $header])->getRow();
        if (empty($model)) {
            $this->db->query("DELETE FROM token WHERE last_access < (NOW() - INTERVAL 3 DAY)");
            return ['error' => 'Invalid token.'];
        }
        unset($model->password, $model->reset_pass, $model->reset_exp);
        return ['success' => 'success.', 'model' => $model];
    }

    public function tokenUpdate($token)
    {
        $result = $this->db->table('token')->update(
            ['last_access' => date('Y-m-d H:i:s')],
            ['token' => $token]
        );
    }

    public function tokenInsert($id)
    {
        $header = $_SERVER['HTTP_USER_AGENT'] . " | " . $_SERVER['REMOTE_ADDR'];
        $token = md5($header . date('Y-m-d H:i:s'));
        $model = [
            'header' => $header,
            'token' => $token,
            'account_id' => $id,
            'last_access' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('token')->insert($model);
        $iid = $this->db->insertID();
        if (!$iid) {
            return ['error' => 'Insert token error.'];
        }
        return ['success' => 'Insert token success.', 'token' => $token, 'iid' => $iid];
    }

    public function tokenDelete($token)
    {
        $header = $_SERVER['HTTP_USER_AGENT'] . " | " . $_SERVER['REMOTE_ADDR'];
        $query = "DELETE t FROM token as t JOIN account as a ON a.id = t.account_id 
            WHERE t.token = ? AND t.header = ?";
        $this->db->query($query, [$token, $header]);
        setcookie("token", "", time() - 3600); # delete cookie
        $affected = $this->db->affectedRows();
        if (!$affected) {
            return ['error' => 'Cant delete token.'];
        }
        return ['success' => 'Delete token success.'];
    }
}
