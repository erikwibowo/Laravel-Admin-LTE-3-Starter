@extends('admin.layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-tambah" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> Tambah</a>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped datatable yajra-datatable">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Uri</th>
                                <th>Type</th>
                                <th>Route</th>
                                <th>Urut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i)
                                <tr style="{{ $i->type == 'title' ? 'font-weight: bold':'' }}">
                                    <td><i class="{{ $i->icon }}"></i> {{ $i->menu }}</td>
                                    <td>{{ $i->uri }}</td>
                                    <td>{{ $i->type }}</td>
                                    <td>{{ $i->route }}</td>
                                    <td>{{ $i->urut }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" data-id="{{ $i->id }}" class="btn btn-primary btn-sm btn-edit"><i class="fa fa-eye"></i></button>
                                            <button type="button" data-id="{{ $i->id }}" data-name="{{ $i->menu }}" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
        </div>
    </div>
<script>
    $(document).ready(function() {
        $(document).on("click", '.btn-edit', function() {
            let id = $(this).attr("data-id");
            $('#modal-loading').modal({backdrop: 'static', keyboard: false, show: true});
            $.ajax({
                url: "{{ route('admin.menu.data') }}",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $("#menu").val(data.menu);
                    $("#icon").val(data.icon);
                    $("#route").val(data.route);
                    $("#type").val(data.type);
                    $("#urut").val(data.urut);
                    $("#id").val(data.id);
                    $('#modal-loading').modal('hide');
                    $('#modal-edit').modal({backdrop: 'static', keyboard: false, show: true});
                },
            });
        });
        $(document).on("click", '.btn-delete', function() {
            let id = $(this).attr("data-id");
            let name = $(this).attr("data-name");
            $("#did").val(id);
            $("#delete-data").html(name);
            $('#modal-delete').modal({backdrop: 'static', keyboard: false, show: true});
        });
    });
</script>
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.menu.create') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label>Menu</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('menu') is-invalid @enderror" placeholder="Type menu..." name="menu" value="{{ old('menu') }}" required>
                        @error('menu')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Icon</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" placeholder="Type icon..." name="icon" value="{{ old('icon') }}">
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Route</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('route') is-invalid @enderror" placeholder="Type route..." name="route" value="{{ old('route') }}">
                        @error('route')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Type</label>
                    <div class="input-group">
                        <select class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" required>
                            <option value="title">Title</option>
                            <option value="menu">Menu</option>
                            <option value="parent">Parent</option>
                            <option value="child">Child</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Urut</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('urut') is-invalid @enderror" placeholder="Type urut..." name="urut" value="{{ old('urut') }}">
                        @error('urut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.menu.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="input-group">
                    <label>Menu</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('menu') is-invalid @enderror" placeholder="Type menu..." name="menu" id="menu" value="{{ old('menu') }}" required>
                        @error('menu')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Icon</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" placeholder="Type icon..." name="icon" id="icon" value="{{ old('icon') }}">
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Route</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('route') is-invalid @enderror" placeholder="Type route..." name="route" id="route" value="{{ old('route') }}">
                        @error('route')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Type</label>
                    <div class="input-group">
                        <select class="form-control @error('type') is-invalid @enderror" name="type" id="type" value="{{ old('type') }}" required>
                            <option value="title">Title</option>
                            <option value="menu">Menu</option>
                            <option value="parent">Parent</option>
                            <option value="child">Child</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label>Urut</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('urut') is-invalid @enderror" placeholder="Type urut..." name="urut" id="urut" value="{{ old('urut') }}">
                        @error('urut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <input type="hidden" name="id" id="id">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Hapus Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.menu.delete') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <p class="modal-text">Apakah anda yakin akan menghapus? <b id="delete-data"></b></p>
                <input type="hidden" name="id" id="did">
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
        </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection