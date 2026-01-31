@extends('layouts.app')

@section('title','Fasilitas')

@section('content')
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
  Tambah Fasilitas
</button>

<table class="table table-bordered">
  <tr>
    <th>Nama</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
  </tr>
  @foreach($fasilitas as $f)
  <tr>
    <td>{{ $f->name }}</td>
    <td>{{ $f->description }}</td>
    <td>
      <button class="btn btn-info btn-sm btn-show" data-id="{{ $f->id }}">Show</button>
      <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $f->id }}">Edit</button>
      <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $f->id }}">Delete</button>
    </td>
  </tr>
  @endforeach
</table>

{{-- SweetAlert sukses --}}
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function(){
  Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'{{ session('success') }}'
  });
});
</script>
@endif

<!-- Modal Create -->
<div class="modal fade" id="modalCreate">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('fasilitas.store') }}" class="modal-content">
      @csrf
      <div class="modal-header"><h5>Tambah Fasilitas</h5></div>
      <div class="modal-body">
        <input name="name" class="form-control mb-2" placeholder="Nama" required>
        <textarea name="description" class="form-control mb-2" placeholder="Deskripsi" required></textarea>
        <input name="icon" class="form-control mb-2" placeholder="Icon (opsional)">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
  <div class="modal-dialog">
    <form method="POST" id="formEdit" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header"><h5>Edit Fasilitas</h5></div>
      <div class="modal-body">
        <input id="edit_name" name="name" class="form-control mb-2" required>
        <textarea id="edit_description" name="description" class="form-control mb-2" required></textarea>
        <input id="edit_icon" name="icon" class="form-control mb-2">
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Show -->
<div class="modal fade" id="modalShow">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <h5 id="show_name"></h5>
      <p id="show_desc"></p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const modalShow = document.getElementById('modalShow');
const modalEdit = document.getElementById('modalEdit');

document.querySelectorAll('.btn-show').forEach(btn=>{
  btn.onclick=()=>{
    fetch('/fasilitas/'+btn.dataset.id)
    .then(r=>r.json()).then(d=>{
      document.getElementById('show_name').innerText=d.name;
      document.getElementById('show_desc').innerText=d.description;
      new bootstrap.Modal(modalShow).show();
    });
  }
});

document.querySelectorAll('.btn-edit').forEach(btn=>{
  btn.onclick=()=>{
    fetch('/fasilitas/'+btn.dataset.id)
    .then(r=>r.json()).then(d=>{
      const form = document.getElementById('formEdit');
      form.action = '/fasilitas/'+d.id;
      document.getElementById('edit_name').value=d.name;
      document.getElementById('edit_description').value=d.description;
      document.getElementById('edit_icon').value=d.icon ?? '';
      new bootstrap.Modal(modalEdit).show();
    });
  }
});

document.querySelectorAll('.btn-delete').forEach(btn=>{
  btn.onclick=()=>{
    Swal.fire({
      title:'Hapus fasilitas?',
      icon:'warning',
      showCancelButton:true,
      confirmButtonText:'Ya hapus'
    }).then(res=>{
      if(res.isConfirmed){
        fetch('/fasilitas/'+btn.dataset.id,{
          method:'DELETE',
          headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Accept':'application/json'
          }
        })
        .then(r=>r.json())
        .then(()=>{
          Swal.fire('Terhapus','Fasilitas berhasil dihapus','success')
            .then(()=>location.reload());
        });
      }
    });
  }
});
</script>
@endpush
