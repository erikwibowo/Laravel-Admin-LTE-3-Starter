<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::latest();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-edit"><i class="fa fa-eye"></i></button><button type="button" data-id="' . $row->id . '" data-name="' . $row->name . '" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash"></i></button></div>';
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = '<span class="badge badge-danger">Nonaktif</span>';
                    } else {
                        $status = '<span class="badge badge-success">Aktif</span>';
                    }
                    return $status;
                })
                ->addColumn('photo', function($row){
                    return '<img src="'.asset("data_file/".$row->photo).'" class="img-circle" style="width: 3rem; height:auto">';
                })
                ->rawColumns(['action', 'status','photo'])
                ->make(true);
        }
        $x['title'] = "Data Admin";
        return view('admin/admin', $x);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'level' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/admin')
                ->withErrors($validator)
                ->withInput();
        }

        if (empty($request->input('password'))) {
            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'level' => $request->input('level'),
                'status' => $request->input('status')
            ];
        } else {
            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'password' => Hash::make($request->input('password')),
                'level' => $request->input('level'),
                'status' => $request->input('status')
            ];
        }
        Admin::where('id', $request->input('id'))->update($data);
        session()->flash('type', 'success');
        session()->flash('notif', 'Data berhasil disimpan');
        return redirect('admin/admin');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'email' => 'required|email|unique:admins',
            'name' => 'required',
            'address' => 'required',
            'level' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/admin')
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'password' => Hash::make($request->input('password')),
            'level' => $request->input('level'),
            'created_at' => now()
        ];
        Admin::insert($data);
        session()->flash('type', 'success');
        session()->flash('notif', 'Data berhasil ditambah');
        return redirect('admin/admin');
    }

    public function data(Request $request)
    {
        echo json_encode(Admin::where(['id' => $request->input('id')])->first());
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        Admin::where(['id' => $id])->delete();
        session()->flash('notif', 'Data berhasil dihapus');
        session()->flash('type', 'success');
        return redirect('admin/admin');
    }

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
                    session()->flash('type', 'info');
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
