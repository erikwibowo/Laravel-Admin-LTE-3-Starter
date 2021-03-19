<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(){
        $x['title']     = "Menu";
        $x['data']      = Menu::get();
        return view('admin.menu', $x);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required',
            'type' => 'required',
            'urut' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/menu')
            ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'menu' => $request->menu,
            'uri' => Str::slug($request->menu, '-'),
            'icon' => $request->icon,
            'route' => $request->route,
            'type' => $request->type,
            'urut' => $request->urut,
            'created_at' => now()
        ];
        try {
            Menu::insert($data);
            session()->flash('type', 'success');
            session()->flash('notif', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            session()->flash('type', 'error');
            session()->flash('notif', $th->getMessage());
        }
        return redirect('admin/menu');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required',
            'type' => 'required',
            'urut' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/menu')
            ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'menu' => $request->menu,
            'uri' => Str::slug($request->menu, '-'),
            'icon' => $request->icon,
            'route' => $request->route,
            'type' => $request->type,
            'urut' => $request->urut
        ];
        try {
            Menu::where(['id' => $request->id])->update($data);
            session()->flash('type', 'success');
            session()->flash('notif', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            session()->flash('type', 'error');
            session()->flash('notif', $th->getMessage());
        }
        return redirect('admin/menu');
    }

    public function data(Request $request)
    {
        echo json_encode(Menu::where(['id' => $request->input('id')])->first());
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        try {
            Menu::where(['id' => $id])->delete();
            session()->flash('notif', 'Data berhasil dihapus');
            session()->flash('type', 'success');
        } catch (\Throwable $th) {
            session()->flash('type', 'error');
            session()->flash('notif', $th->getMessage());
        }
        return redirect('admin/menu');
    }
}
