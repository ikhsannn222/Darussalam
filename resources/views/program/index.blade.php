@extends('layouts.app')

@section('title','Program')

@section('content')
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
  Tambah Program
</button>

<table class="table table-bordered">
  <tr>
    <th>Nama</th>
    <th>Deskripsi</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>
  @foreach($programs as $p)
  <tr>
    <td>{{ $p->name }}</td>
    <td>{{ $p->description }}</td>
    <td>{{ $p->is_open_registration ? 'Buka' : 'Tutup' }}</td>
    <td>
      <button class="btn btn-info btn-sm btn-show" data-id="{{ $p->id }}">Show</button>
      <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $p->id }}">Edit</button>
      <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $p->id }}">Delete</button>
    </td>
  </tr>
  @endforeach
</table>

{{-- SweetAlert sukses tambah / edit --}}
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
    <form method="POST" action="{{ route('program.store') }}" class="modal-content">
      @csrf
      <div class="modal-header"><h5>Tambah Program</h5></div>
      <div class="modal-body">
        <input name="name" class="form-control mb-2" placeholder="Nama" required>
        <textarea name="description" class="form-control mb-2" placeholder="Deskripsi" required></textarea>
        <input name="icon" class="form-control mb-2" placeholder="Icon (opsional)">
        <label><input type="checkbox" name="is_open_registration"> Buka Pendaftaran</label>
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
      @method('PUT') {{-- penting untuk update --}}
      <div class="modal-header"><h5>Edit Program</h5></div>
      <div class="modal-body">
        <input id="edit_name" name="name" class="form-control mb-2" required>
        <textarea id="edit_description" name="description" class="form-control mb-2" required></textarea>
        <input id="edit_icon" name="icon" class="form-control mb-2">
        <label><input type="checkbox" id="edit_open" name="is_open_registration"> Buka</label>
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
      <span id="show_status"></span>
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
    fetch('/program/'+btn.dataset.id)
    .then(r=>r.json()).then(d=>{
      document.getElementById('show_name').innerText=d.name;
      document.getElementById('show_desc').innerText=d.description;
      document.getElementById('show_status').innerText=d.is_open_registration?'Buka':'Tutup';
      new bootstrap.Modal(modalShow).show();
    });
  }
});

document.querySelectorAll('.btn-edit').forEach(btn=>{
  btn.onclick=()=>{
    fetch('/program/'+btn.dataset.id)
    .then(r=>r.json()).then(d=>{
      const form = document.getElementById('formEdit');
      form.action = '/program/'+d.id; // route update resource
      document.getElementById('edit_name').value=d.name;
      document.getElementById('edit_description').value=d.description;
      document.getElementById('edit_icon').value=d.icon ?? '';
      document.getElementById('edit_open').checked=!!d.is_open_registration;
      new bootstrap.Modal(modalEdit).show();
    });
  }
});

document.querySelectorAll('.btn-delete').forEach(btn=>{
  btn.onclick=()=>{
    Swal.fire({
      title:'Hapus program?',
      icon:'warning',
      showCancelButton:true,
      confirmButtonText:'Ya hapus'
    }).then(res=>{
      if(res.isConfirmed){
        fetch('/program/'+btn.dataset.id,{
          method:'DELETE',
          headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Accept':'application/json'
          }
        })
        .then(r=>r.json())
        .then(()=>{
          Swal.fire('Terhapus','Program berhasil dihapus','success')
            .then(()=>location.reload());
        });
      }
    });
  }
});
</script>
@endpush
