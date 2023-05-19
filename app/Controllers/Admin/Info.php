<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\AccountLib;
use App\Libraries\InfoLib;
use App\Libraries\EmailingLib;
use App\Libraries\DashboardLib;


class Info extends Controller
{
    public function __construct()
    {
        $this->account = new AccountLib;
        $this->info = new InfoLib;

        $this->dasbor = new Dashboard;
        $this->webdata = (object) $this->dasbor->actselect2();
        
    }

    public function index()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $data = [
            'title' => 'Home',
            'id_user' => $this->account->signCheck()['model']->id,
            'webdata' => $this->webdata->model,
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
            'webdata' => $this->webdata->model,
        ];
        return view('common/header', $data) . view('info/form', $data) . view('common/footer');
    }

    // 
    public function actIndex()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $model = $this->info->index2();
        if($model['success']){
            echo json_encode(['data' => $model['model']]);
        }
    }

    public function actIndexTv()
    {
        $model = $this->info->indextv();
        if($model['success']){
            echo json_encode(['data' => $model['model']]);
        }
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
