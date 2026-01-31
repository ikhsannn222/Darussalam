@extends('layouts.app')

@section('title','Teacher')

@section('content')
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
  Tambah Guru
</button>

<table class="table table-bordered">
  <tr>
    <th>Nama</th>
    <th>Spesialisasi</th>
    <th>Email</th>
    <th>Telepon</th>
    <th>Aksi</th>
  </tr>

  @foreach($teachers as $t)
  <tr>
    <td>{{ $t->name }}</td>
    <td>{{ $t->specialization }}</td>
    <td>{{ $t->email ?? '-' }}</td>
    <td>{{ $t->phone ?? '-' }}</td>
    <td>
      <button class="btn btn-info btn-sm btn-show" data-id="{{ $t->id }}">Show</button>
      <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $t->id }}">Edit</button>
      <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $t->id }}">Delete</button>
    </td>
  </tr>
  @endforeach
</table>

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
    <form method="POST" action="{{ route('guru.store') }}" class="modal-content">
      @csrf
      <div class="modal-header"><h5>Tambah Guru</h5></div>
      <div class="modal-body">
        <input name="name" class="form-control mb-2" placeholder="Nama Guru" required>
        <input name="specialization" class="form-control mb-2" placeholder="Spesialisasi" required>
        <textarea name="biography" class="form-control mb-2" placeholder="Biografi"></textarea>
        <input name="email" class="form-control mb-2" placeholder="Email">
        <input name="phone" class="form-control mb-2" placeholder="No Telepon">
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
      <div class="modal-header"><h5>Edit Guru</h5></div>
      <div class="modal-body">
        <input id="edit_name" name="name" class="form-control mb-2" required>
        <input id="edit_specialization" name="specialization" class="form-control mb-2" required>
        <textarea id="edit_biography" name="biography" class="form-control mb-2"></textarea>
        <input id="edit_email" name="email" class="form-control mb-2">
        <input id="edit_phone" name="phone" class="form-control mb-2">
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
      <p><strong>Spesialisasi:</strong> <span id="show_specialization"></span></p>
      <p id="show_biography"></p>
      <p><strong>Email:</strong> <span id="show_email"></span></p>
      <p><strong>Telepon:</strong> <span id="show_phone"></span></p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<<script>
const modalShow = document.getElementById('modalShow');
const modalEdit = document.getElementById('modalEdit');
const formEdit = document.getElementById('formEdit');

document.querySelectorAll('.btn-show').forEach(btn=>{
  btn.onclick=()=>{
    fetch('/guru/' + btn.dataset.id)
    .then(r => r.json())
    .then(d => {
      document.getElementById('show_name').innerText = d.name;
      document.getElementById('show_specialization').innerText = d.specialization;
      document.getElementById('show_biography').innerText = d.biography ?? '-';
      document.getElementById('show_email').innerText = d.email ?? '-';
      document.getElementById('show_phone').innerText = d.phone ?? '-';

      new bootstrap.Modal(modalShow).show();
    });
  }
});

document.querySelectorAll('.btn-edit').forEach(btn=>{
  btn.onclick=()=>{
    fetch('/guru/' + btn.dataset.id)
    .then(r => r.json())
    .then(d => {
      formEdit.action = '/guru/' + d.id;
      document.getElementById('edit_name').value = d.name;
      document.getElementById('edit_specialization').value = d.specialization;
      document.getElementById('edit_biography').value = d.biography ?? '';
      document.getElementById('edit_email').value = d.email ?? '';
      document.getElementById('edit_phone').value = d.phone ?? '';

      new bootstrap.Modal(modalEdit).show();
    });
  }
});

document.querySelectorAll('.btn-delete').forEach(btn=>{
  btn.onclick=()=>{
    Swal.fire({
      title:'Hapus data guru?',
      text:'Data yang dihapus tidak bisa dikembalikan',
      icon:'warning',
      showCancelButton:true,
      confirmButtonColor:'#d33',
      confirmButtonText:'Ya, hapus!'
    }).then(res=>{
      if(res.isConfirmed){
        fetch('/guru/' + btn.dataset.id,{
          method:'DELETE',
          headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Accept':'application/json'
          }
        })
        .then(r => r.json())
        .then(() => {
          Swal.fire('Terhapus','Data guru berhasil dihapus','success')
            .then(()=>location.reload());
        });
      }
    });
  }
});
</script>

@endpush
