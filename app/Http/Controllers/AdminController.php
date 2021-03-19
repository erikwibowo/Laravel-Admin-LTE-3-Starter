<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

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
                    return '<img src="'.asset("storage/admins/thumbnail/".$row->thumb).'" class="img-circle" style="width: 3rem; height:auto">';
                })
                ->rawColumns(['action', 'status','photo'])
                ->make(true);
        }
        $x['title'] = "Data Admin";
        return view('admin.admin', $x);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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

        $filenametostore = "admin_".time() . '.' . $request->photo->extension();
        $thumbnailstore = "admin" . '_thumb_' . time() . '.' . $request->photo->extension();

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'photo' => $filenametostore,
            'thumb' => $thumbnailstore,
            'address' => $request->input('address'),
            'password' => Hash::make($request->input('password')),
            'level' => $request->input('level'),
            'created_at' => now()
        ];
        if (Admin::insert($data)) {
            //Upload File
            $request->photo->storeAs('public/admins', $filenametostore);
            $request->photo->storeAs('public/admins/thumbnail', $thumbnailstore);
            //create thumbnail
            $thumbnail = public_path('storage/admins/thumbnail/' . $thumbnailstore);
            $this->createThumbnail($thumbnail, 300, 185);

            session()->flash('type', 'success');
            session()->flash('notif', 'Data berhasil ditambah');
        }else{
            session()->flash('type', 'error');
            session()->flash('notif', 'Data gagal ditambah');
        }
        return redirect('admin/admin');
    }

    public function createThumbnail($path, $width, $height)
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
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

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'level' => $request->input('level'),
            'status' => $request->input('status')
        ];

        if ($request->hasFile('photo')) { //Jika ada foto
            $filenametostore = "admin_" . time() . '.' . $request->photo->extension();
            $thumbnailstore = "admin" . '_thumb_' . time() . '.' . $request->photo->extension();
            if (empty($request->password)) {
                $data = [
                    'photo' => $filenametostore,
                    'thumb' => $thumbnailstore,
                ];
            }else{
                $data = [
                    'photo' => $filenametostore,
                    'thumb' => $thumbnailstore,
                    'password' => Hash::make($request->input('password'))
                ];
            }
            $old = Admin::where(['id' => $request->id])->first();
            try {
                Admin::where('id', $request->input('id'))->update($data);
                //delete old photo
                File::delete('storage/admins/' . $old->photo);
                File::delete('storage/admins/thumbnail/' . $old->thumb);
                //Upload File
                $request->photo->storeAs('public/admins', $filenametostore);
                $request->photo->storeAs('public/admins/thumbnail', $thumbnailstore);
                //create thumbnail
                $thumbnail = public_path('storage/admins/thumbnail/' . $thumbnailstore);
                $this->createThumbnail($thumbnail, 300, 185);
                session()->flash('type', 'success');
                session()->flash('notif', 'Data berhasil disimpan');
            } catch (\Throwable $th) {
                session()->flash('type', 'error');
                session()->flash('notif', $th->getMessage());
            }
        }else{
            if (!empty($request->password)) {
                $data = [
                    'password' => Hash::make($request->input('password'))
                ];
            }
            try {
                Admin::where('id', $request->input('id'))->update($data);
                session()->flash('type', 'success');
                session()->flash('notif', 'Data berhasil disimpan');
            } catch (\Throwable $th) {
                session()->flash('type', 'error');
                session()->flash('notif', $th->getMessage());
            }
        }
        return redirect('admin/admin');
    }

    public function data(Request $request)
    {
        echo json_encode(Admin::where(['id' => $request->input('id')])->first());
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $old = Admin::where(['id' => $id])->first();
        try {
            Admin::where(['id' => $id])->delete();
            File::delete('storage/admins/' . $old->photo);
            File::delete('storage/admins/thumbnail/' . $old->thumb);
            session()->flash('notif', 'Data berhasil dihapus');
            session()->flash('type', 'success');
        } catch (\Throwable $th) {
            session()->flash('notif', $th->getMessage());
            session()->flash('type', 'error');
        }
        return redirect('admin/admin');
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/login')
                ->withErrors($validator)
                ->withInput();
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $response_key = $request->input('g-recaptcha-response');
        $secret_key = env('GOOGLE_RECHATPTCHA_SECRETKEY');

        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response_key);
        $response = json_decode($verify);

        $data = Admin::where(['email' => $email]);
        if ($response->success) {
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
                    }
                } else {
                    session()->flash('type', 'error');
                    session()->flash('notif', 'Akun anda nonaktif. Silahkan hubungi administrator');
                }
            } else {
                session()->flash('type', 'error');
                session()->flash('notif', 'Email atau password anda tidak sesuai');
            }
        }else{
            session()->flash('type', 'error');
            session()->flash('notif', 'Ups! Sepertinya ada yang salah');
        }
        return redirect('admin/login');
    }

    public function logout()
    {
        session()->flash('type', 'info');
        session()->flash('notif', 'Sampai jumpa ' . session('name'));
        session()->forget(['id', 'name', 'level', 'email', 'login_status']);
        return redirect('admin/login');
    }
}
