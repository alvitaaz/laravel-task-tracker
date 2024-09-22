<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <!-- Bootstrap CSS buat styling biar kece -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS biar tampilannya kece dan nggak biasaa haha-->
    <style>
        /* Navbar Style */
        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Biar navbarnya ada bayangan */
            padding: 15px;
        }

        /* Body Background */
        body {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); /* Background yang eye-catching */
            font-family: 'Arial', sans-serif; /* Font yang simpel dan gampang dibaca */
        }

        /* Card Style */
        .card {
            border-radius: 10px; /* Sudut-sudut yang melengkung biar lebih modern */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); /* Bayangan buat efek 3D */
        }

        /* Input and Button */
        .form-control {
            border-radius: 30px; /* Sudut melengkung buat input */
            padding: 15px; /* Padding biar nyaman */
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1); /* Bayangan pada input */
        }

        .btn-primary {
            border-radius: 30px; /* Tombol yang melengkung juga */
            padding: 10px 20px; /* Padding tombol */
            background-color: #ff7e5f; /* Warna background tombol */
            border: none; /* Nggak ada border biar clean */
            transition: background-color 0.3s ease; /* Animasi saat hover */
        }

        .btn-primary:hover {
            background-color: #feb47b; /* Warna saat hover */
        }

        /* List Group Item */
        .list-group-item {
            border: none; /* Hapus border */
            border-radius: 10px; /* Sudut melengkung */
            margin-bottom: 10px; /* Jarak antar item */
            transition: transform 0.2s, box-shadow 0.2s; /* Animasi saat hover */
        }

        .list-group-item:hover {
            transform: translateY(-3px); /* Naik sedikit saat hover */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Bayangan saat hover */
        }

        .delete-btn {
            background-color: #ff4e50; /* Warna tombol delete */
            border: none; /* Nggak ada border */
            border-radius: 20px; /* Sudut melengkung */
            padding: 5px 10px; /* Padding tombol */
            transition: background-color 0.3s ease; /* Animasi saat hover */
        }

        .delete-btn:hover {
            background-color: #ff6b6b; /* Warna saat hover */
        }

        .edit-btn {
            background-color: #4ecdc4; /* Warna tombol edit */
            border: none; /* Nggak ada border */
            border-radius: 20px; /* Sudut melengkung */
            padding: 5px 10px; /* Padding tombol */
            transition: background-color 0.3s ease; /* Animasi saat hover */
        }

        .edit-btn:hover {
            background-color: #76c7c0; /* Warna saat hover */
        }

        /* Pagination Style */
        .pagination {
            justify-content: center; /* Pusatkan pagination */
        }

        .page-link {
            color: #ff7e5f; /* Warna link pagination */
        }

        .page-link:hover {
            background-color: #feb47b; /* Warna saat hover link pagination */
            color: white; /* Warna teks saat hover */
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid col-md-7">
            <div class="navbar-brand">Daily Task Tracker</div> <!-- Nama aplikasi -->
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Content -->
        <h1 class="text-center text-white mb-4">Task Tracker</h1> <!-- Judul besar -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success')}} <!-- Notifikasi sukses -->
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li> <!-- Tampilkan error kaloo ada -->
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Form input data -->
                        <form id="todo-form" action="{{ route('todo.post') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="task" id="todo-input" placeholder="Tambah task baru" required value="{{ old('task') }}">
                                <button class="btn btn-primary" type="submit">Simpan</button> <!-- Tombol simpan -->
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Searching -->
                        <form id="todo-form" action="{{ route('todo') }}" method="get">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci">
                                <button class="btn btn-secondary" type="submit">Cari</button> <!-- Tombol cari -->
                            </div>
                        </form>

                        <!-- Display Data -->
                        <ul class="list-group mb-4" id="todo-list">
                            @foreach ($data as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="task-text">{!! $item->is_done == '1' ? '<del>' : '' !!}{{ $item->task }}{!! $item->is_done == '1' ? '</del>' : '' !!}</span> <!-- Menampilkan task -->
                                <div class="btn-group">
                                    <form action="{{ route('todo.delete', ['id'=>$item->id]) }}" method="POST" onsubmit="return confirm('Yakin akan menghapus data ini?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm delete-btn">✕</button> <!-- Tombol hapus -->
                                    </form>
                                    <button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="false">✎</button> <!-- Tombol edit -->
                                </div>
                            </li>

                            <!-- Update Data -->
                            <li class="list-group-item collapse" id="collapse-{{ $loop->index }}">
                                <form action="{{ route('todo.update', ['id'=>$item->id]) }}" method="POST">
                                    @csrf
                                    @method('put')

                                    <div>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="task" value="{{ $item->task }}">
                                            <button class="btn btn-outline-primary" type="submit">Update</button> <!-- Tombol update -->
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="radio px-2">
                                            <label>
                                                <input type="radio" value="1" name="is_done"{{ $item->is_done == '1' ? 'checked' : '' }}> Selesai <!-- Pilihan selesai -->
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="0" name="is_done"{{ $item->is_done == '0' ? 'checked' : '' }}> Belum <!-- Pilihan belum -->
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Pagination Links -->
                        {{ $data->links() }} <!-- Navigasi halaman -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
