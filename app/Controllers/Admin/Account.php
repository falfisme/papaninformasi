<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\AccountLib;
use App\Libraries\EmailingLib;
use App\Libraries\DashboardLib;


class Account extends Controller
{
    public function __construct()
    {
        $this->account = new AccountLib;

        $this->dasbor = new Dashboard;
        $this->webdata = (object) $this->dasbor->actselect2();
    }

    private function loginError()
    {
    }

    public function index()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $data = [
            'title' => 'Account',
            'webdata' => $this->webdata->model,
        ];
        return view('common/header', $data) . view('account/index') . view('common/footer');
    }

    public function username()
    {
        if(isset($_COOKIE['nama_petugas'])){
            $data = array(
                'username' => $_COOKIE['nama_petugas'],
                'type'  => 2
            );
            echo json_encode($data);

        }else{
            $data = $this->account->selectAccount();
            $data['acc'] = $this->account->signCheck()['model'];
            echo json_encode($data);
        }
    }

    public function form()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }
        $data = [
            'title' => 'Home',
            'webdata' => $this->webdata->model,
        ];
        return view('common/header', $data) . view('account/form') . view('common/footer');
    }

    public function login()
    {
        if (empty($this->account->signCheck()['error'])) {
            die('<script>location.href="/admin/dashboard";</script>');
        }
        return view('account/login');
    }

    public function actSignIn()
    {
        $input = $this->request->getJSON();
        // var_dump($input);
        if (empty($this->account->signCheck()['error'])) {
            die(json_encode(['error' => 'already signed in.']));
        }
        if (empty($input->username) || empty($input->password)) {
            die(json_encode(['error' => 'username and password required.']));
        }
        $result = $this->account->signIn($input->username, $input->password);
        // var_dump($result);
        echo json_encode($result);
    }

    public function actSignOut()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die(json_encode(['error' => 'already signed out.']));
        }
        
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
        $result = $this->account->signOut($this->account->token);
        echo json_encode($result);
    }

    public function actPasswordReset()
    {
        $input = $this->request->getJSON();
        if (empty($input->username)) {
            die(json_encode(['error' => 'username required.']));
        }
        $result = $this->account->resetPassword($input->username, $input->password);
        if (!empty($result['error'])) {
            die(json_encode($result));
        }
        $email = new EmailingLib;
        $email_text = 'Hi ' . $result['model']->nama .
            ',<br/>This is your temporary password: ' . $result['model']->reset_pass .
            '<br/>Will expired on: ' . $result['model']->reset_exp;
        $email_result = $email->send($result['model']->email, 'PIM Password Reset', $email_text);
        if (!empty($email_result['error'])) {
            die(json_encode($email_result));
        }
        echo json_encode(['success' => 'Temporary Password has been sent to your email. Thank you.']);
    }

    public function actIndex()
    {
        $account = $this->account->signCheck();
        if (!empty($account['error'])) {
            die(json_encode(['error' => 'please login']));
        }
        $this->account_detail = $account['model'];

        $input = $this->request->getJSON();
        $model = $this->account->index((array) @$input->filter, @$input->page, @$input->limit);
        $result = [
            'account' => $this->account_detail,
            'model' => $model['model'],
            'pagination' => $model['pagination']
        ];
        echo json_encode($result);
    }

    public function index2()
    {
        $this->db = \Config\Database::connect();
        //$this->qh = new QueryHelper;
        $model = $this->db->query("SELECT * FROM `account`")
            ->getResult();
        echo json_encode(['data' => $model]);
    }


    public function actSelect()
    {
        $input = $this->request->getVar();  
        // echo json_encode($input);
        if (empty($input['id'])) {
            die(json_encode(['error' => 'Id required']));
        }
        echo json_encode($this->account->selectWithId($input['id']));
    }

    public function actUpdate()
    {
        $input = $this->request->getJSON();
        if (empty($input->data->username)) {
            die(json_encode(['error' => 'Username required']));
        }
        if (empty($input->data->email)) {
            die(json_encode(['error' => 'Email required']));
        }

        if (empty($input->data->id)) {
            $result = $this->account->insert((array) $input->data);
        } else {
            $result = $this->account->update((array) $input->data);
        }
        echo json_encode($result);
    }

    public function actUploadImage(){
        $id = $this->request->getVar()['id']; 
        if(!empty($_FILES))  
            {  
                $image = ['id' => $id, 'image' => $_FILES['file']['name']];
                $path = 'assets/upload/' . $_FILES['file']['name'];  
                if(move_uploaded_file($_FILES['file']['tmp_name'], $path))  
                {  
                    if (empty($id)) {
                        die(json_encode(['error' => 'Submit Biodata Dulu']));
                        // $result = $this->account->insert($image);
                    } else {
                        $querydata = $this->account->selectWithId($id);
                        $select = $querydata['model']->image;
                        if($select == $_FILES['file']['name']){
                            die(json_encode(['error' => 'No Changes']));
                        };
                        if($select){
                            $result = $this->account->update($image);
                            if(@unlink('assets/upload/' . $select)){
                               $result['deleteold'] = 'sucesss'; 
                            }
                        }else{
                            $result = $this->account->update($image);
                        }
                    }
                    $result['image'] = $_FILES['file']['name'];
                    echo json_encode($result);
                }  
            }  
            else  
            {  
                die(json_encode(['error' => 'Error pokonya']));
            }  
    }

    public function actDelete()
    {
        $input = $this->request->getJSON();
        if (empty($input->id)) {
            die(json_encode(['error' => 'id required']));
        }
        $result = $this->account->delete($input->id);
        echo json_encode($result);
    }
}
