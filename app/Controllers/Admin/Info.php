<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\AccountLib;
use App\Libraries\InfoLib;
use App\Libraries\EmailingLib;

class Info extends Controller
{
    public function __construct()
    {
        $this->account = new AccountLib;
        $this->info = new InfoLib;
        
    }

    public function index()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $data = [
            'title' => 'Home',
            'id_user' => $this->account->signCheck()['model']->id,
        ];
        return view('common/header', $data) . view('info/index', $data) . view('common/footer');
    }


    public function form()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }
        $data = [
            'title' => 'Form',
            'id_user' => $this->account->signCheck()['model']->id,
        ];
        return view('common/header', $data) . view('info/form', $data) . view('common/footer');
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
        $model = $this->db->query("SELECT info.date_created, info.image, info.title, info.active, account.nama, info.id FROM `info` LEFT JOIN `account` ON account.id = info.id_user ORDER BY info.date_created DESC; ")
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
        if (empty($input->data->title)) {
            die(json_encode(['error' => 'Title required']));
        }
        if (empty($input->data->caption)) {
            die(json_encode(['error' => 'Caption required']));
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
                    $result = $this->info->update($image);
                    echo json_encode(['success' => 'Gambar Berhasil diubah']);
                    if($id == false){
                        echo json_encode(['error' => 'Submit yang kiri dulu']);  
                    }
                }  
            }  
            else  
            {  
                echo json_encode(['error' => 'Tidak ada gambar']);  
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
