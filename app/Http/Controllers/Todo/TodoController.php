<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_data = 4;

        // Pengecekan apa ada parameter pencarian
        if (request('search')) {
            // Query untuk pencarian berdasarkan task
            $data = Todo::where('task', 'like', '%' . request('search') . '%')->paginate($max_data);
        } else {
            // Query untuk menampilkan semua data dengan urutan task secara ascending
            $data = Todo::orderBy('task', 'asc')->paginate($max_data);
        }

        // Mengarahkan ke view dengan data yang dipaginasi
        return view("todo.app", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Mengarahkan ke halaman pembuatan task
        return view("todo.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input task
        $request->validate([
            'task' => 'required|min:3|max:25'
        ], [           
            'task.required' => 'Isian task wajib diisi',
            'task.min' => 'Minimal isian untuk task adalah 3 karakter',
            'task.max' => 'Maksimum isian untuk task adalah 25 karakter',
        ]);

        // Membuat data baru berdasarkan input dari form
        $data = [
            'task' => $request->input('task')
        ];
    
        // Menyimpan task ke database
        Todo::create($data);
        return redirect()->route('todo')->with('success', 'Berhasil menyimpan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Menampilkan detail task tertentu berdasarkan id
        $task = Todo::find($id);
        return view('todo.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Mengambil task berdasarkan id dan menampilkan form edit
        $task = Todo::find($id);
        return view('todo.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input untuk task
        $request->validate([
            'task' => 'required|min:3|max:25'
        ], [           
            'task.required' => 'Isian task wajib diisi',
            'task.min' => 'Minimal isian untuk task adalah 3 karakter',
            'task.max' => 'Maksimum isian untuk task adalah 25 karakter',
        ]);

        // Mengambil data dari request dan mengupdate task
        $data = [
            'task' => $request->input('task'),
            'is_done' => $request->has('is_done') ? 1 : 0 // Centang jika task selesai
        ];

        Todo::where('id', $id)->update($data);
        return redirect()->route('todo')->with('success', 'Berhasil memperbarui data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Menghapus task berdasarkan id
        Todo::where('id', $id)->delete();
        return redirect()->route('todo')->with('success', 'Berhasil menghapus data');
    }
}
