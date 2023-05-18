<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\InfoLib;
use App\Libraries\EmailingLib;

class Info extends Controller
{
    public function __construct()
    {
        $this->info = new InfoLib;
    }

    public function index()
    {
        if (!empty($this->info->signCheck()['error'])) {
            die('please <a href="/admin/info/login">login</a>.');
        }

        $data = [
            'title' => 'Home',
        ];
        return view('common/header', $data) . view('info/index') . view('common/footer');
    }


    public function form()
    {
        if (!empty($this->info->signCheck()['error'])) {
            die('please <a href="/admin/info/login">login</a>.');
        }
        $data = [
            'title' => 'Home',
        ];
        return view('common/header', $data) . view('info/form') . view('common/footer');
    }

    public function actIndex()
    {
        $info = $this->info->signCheck();
        if (!empty($info['error'])) {
            die(json_encode(['error' => 'please login']));
        }
        $this->info_detail = $info['model'];

        $input = $this->request->getJSON();
        $model = $this->info->index((array) @$input->filter, @$input->page, @$input->limit);
        $result = [
            'info' => $this->info_detail,
            'model' => $model['model'],
            'pagination' => $model['pagination']
        ];
        echo json_encode($result);
    }

    public function index2()
    {
        $this->db = \Config\Database::connect();
        //$this->qh = new QueryHelper;
        $model = $this->db->query("SELECT * FROM `info`")
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
        echo json_encode($this->info->selectWithId($input['id']));
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
            $result = $this->info->insert((array) $input->data);
        } else {
            $result = $this->info->update((array) $input->data);
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
                        $result = $this->info->insert($image);
                    } else {
                        $result = $this->info->update($image);
                    }
                    $result['image'] = $_FILES['file']['name'];
                    // die();
                    echo json_encode($result);
                }  
            }  
            else  
            {  
                echo 'Some Error';  
            }  
    }

    public function actDelete()
    {
        $input = $this->request->getJSON();
        if (empty($input->id)) {
            die(json_encode(['error' => 'id required']));
        }
        $result = $this->info->delete($input->id);
        echo json_encode($result);
    }
}
