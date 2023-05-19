<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Libraries\AccountLib;
use App\Libraries\EventLib;
use App\Libraries\EmailingLib;

class Event extends Controller
{
    public function __construct()
    {
        $this->account = new AccountLib;
        $this->event = new EventLib;
        
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
        return view('common/header', $data) . view('event/index', $data) . view('common/footer');
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
        return view('common/header', $data) . view('event/form', $data) . view('common/footer');
    }

    // 
    public function actIndex()
    {
        if (!empty($this->account->signCheck()['error'])) {
            die('please <a href="/admin/account/login">login</a>.');
        }

        $model = $this->event->index2();
        if($model['success']){
            echo json_encode(['data' => $model['model']]);
        }
    }

    public function actIndexTv()
    {
        $model = $this->event->indextv();
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
        echo json_encode($this->event->selectWithId($input['id']));
    }

    public function actUpdate()
    {
        $input = $this->request->getJSON();
        if (empty($input->data->keterangan)) {
            die(json_encode(['error' => 'Keterangan required']));
        }
        if (empty($input->data->date_start)) {
            die(json_encode(['error' => 'Tanggal required']));
        }

        if (empty($input->data->id)) {
            $result = $this->event->insert((array) $input->data);
        } else {
            $result = $this->event->update((array) $input->data);
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
                    $result = $this->event->update($image);
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
        $result = $this->event->delete($input->id);
        echo json_encode($result);
    }

}
