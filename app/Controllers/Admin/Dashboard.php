<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\AccountLib;
use App\Libraries\DashboardLib;
use App\Libraries\EmailingLib;

class Dashboard extends Controller
{
    public function __construct()
    {
        $this->account = new AccountLib;
        $this->dashboard = new DashboardLib;
        
    }

    public function index()
    {
        $webdata = (object) $this->actselect2();
        $data = [
            'title' => 'home',
            'webdata' => $webdata->model,
        ];
        return view('common/header', $data) . view('dashboard/index') . view('common/footer');
    }

    public function setting()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }
        
        $webdata = (object) $this->actselect2();
        $data = [
            'title' => 'Settings',
            'id_user' => $this->account->signCheck()['model']->id,
            'webdata' => $webdata->model,
        ];
        return view('common/header', $data) . view('dashboard/form', $data) . view('common/footer');
    }

    // 
    public function actIndex()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $model = $this->dashboard->index2();
        if($model['success']){
            echo json_encode(['data' => $model['model']]);
        }
    }

    public function actIndexTv()
    {
        $model = $this->dashboard->indextv();
        if($model['success']){
            echo json_encode(['data' => $model['model']]);
        }
    }


    public function actSelect()
    {
        echo json_encode($this->dashboard->selectWithId(1));
    }

    public function actSelect2()
    {
        return $this->dashboard->selectWithId(1);
    }

    public function actUpdate()
    {
        $input = $this->request->getJSON();

        if (empty($input->data->id)) {
            $result = $this->dashboard->insert((array) $input->data);
            die(json_encode(['error' => 'Title required']));

        } else {
            $result = $this->dashboard->update((array) $input->data);
        }
        
        echo json_encode($result);
    }

    public function actUploadImage(){
        $id = 1; 
        if(!empty($_FILES))  
            {  
                $image = ['id' => $id, 'logo' => $_FILES['file']['name']];
                $path = 'assets/upload/' . $_FILES['file']['name'];  
                if(move_uploaded_file($_FILES['file']['tmp_name'], $path))  
                {  
                    $result = $this->dashboard->update($image);
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
        $result = $this->dashboard->delete($input->id);
        echo json_encode($result);
    }

}
