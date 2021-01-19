<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/login')
                ->withErrors($validator)
                ->withInput();
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $data = Admin::where(['email' => $email]);
        if ($data->count() == 1) {
            $data = $data->first();
            if ($data->status == 1) {
                if (Hash::check($password, $data->password)) {
                    session([
                        'id' => $data->id,
                        'name' => $data->name,
                        'level' => $data->level,
                        'email' => $data->email,
                        'login_status' => true
                    ]);
                    Admin::where("id", $data->id)->update(['login_at' => now()]);
                    session()->flash('notif', 'Selamat Datang ' . $data->name);
                    session()->flash('type', 'success');
                    return redirect('admin');
                } else {
                    session()->flash('type', 'error');
                    session()->flash('notif', 'Email atau password anda tidak sesuai');
                    return redirect('admin/login');
                }
            } else {
                session()->flash('type', 'error');
                session()->flash('notif', 'Akun anda nonaktif. Silahkan hubungi administrator');
                return redirect('admin/login');
            }
        } else {
            session()->flash('type', 'error');
            session()->flash('notif', 'Email atau password anda tidak sesuai');
            return redirect('admin/login');
        }
    }

    public function logout()
    {
        session()->flash('type', 'info');
        session()->flash('notif', 'Sampai jumpa ' . session('name'));
        session()->forget(['id', 'name', 'login_status']);
        return redirect('admin/login');
    }
}
