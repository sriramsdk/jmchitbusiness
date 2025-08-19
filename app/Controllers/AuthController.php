<?php

namespace App\Controllers;
use App\Models\Admin;

class AuthController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->admin = new Admin();
    }

    public function index(): string
    {
        return view('layout/header').view('admin/login').view('layout/footer');
    }

    public function login(){

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $hash_password = md5($password);

        $admin = $this->admin->where('username',$username)->first();
        if($admin){
            if($hash_password == $admin['password']){

                session()->set([
                    'isLoggedIn' => true,
                    'is_admin' => true,
                    'username' => $admin['username']
                ]);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Login successfull',
                    'redirect' => '/dashboard' 
                ]);
            }else{
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid Password'
                ]);
            }
        }else{
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid Username'
            ]);
        }
    }
}
