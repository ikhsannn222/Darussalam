@extends('layouts.app')

@section('title','Fasilitas')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Fasilitas</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Fasilitas
    </button>
  </div>

  <div class="card">
    <div class="card-header">Daftar Fasilitas</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th width="90">Gambar</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th width="60" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($fasilitas as $f)
              <tr>
                <td class="text-center">
                  <img src="{{ $f->image_url }}" alt="image" style="max-height:60px; max-width:70px;">
                </td>
                <td>{{ $f->name }}</td>
                <td>{!! Str::limit($f->description, 80) !!}</td>

                {{-- AKSI --}}
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="showFasilitas({{ $f->id }})">
                          <i class="bx bx-show me-1"></i> Show
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editFasilitas({{ $f->id }})">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteFasilitas({{ $f->id }})">
                          <i class="bx bx-trash me-1"></i> Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">Belum ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- MODAL CREATE -->
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('fasilitas.store') }}" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Fasilitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Gambar</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" id="description_create" class="form-control" rows="6"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="formEdit" class="modal-content" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Edit Fasilitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input id="edit_name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Gambar (opsional)</label>
          <input type="file" name="image" class="form-control" accept="image/*">

          <div class="mt-2" id="currentImageWrap" style="display:none;">
            <small class="text-muted d-block">Gambar saat ini:</small>
            <img id="currentImageImg" style="max-height:80px;">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea id="description_edit" name="description" class="form-control" rows="6"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
        <button class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL SHOW -->
<div class="modal fade" id="modalShow" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
      <div class="d-flex gap-3">
        <img id="show_image" style="max-height:120px;">
        <div>
          <h5 id="show_name"></h5>
        </div>
      </div>
      <hr>
      <div id="show_description"></div>
    </div>
  </div>
</div>
@endsection
@push('scripts')

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- CKEditor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>

<script>
let editorCreate, editorEdit;

// CKEDITOR CREATE
ClassicEditor.create(document.querySelector('#description_create'))
  .then(e => editorCreate = e);

// CKEDITOR EDIT
ClassicEditor.create(document.querySelector('#description_edit'))
  .then(e => editorEdit = e);

// ================= SHOW =================
function showFasilitas(id){
  fetch('/fasilitas/' + id)
    .then(r => r.json())
    .then(d => {
      document.getElementById('show_image').src = d.image_url;
      document.getElementById('show_name').innerText = d.name;
      document.getElementById('show_description').innerHTML = d.description;

      const modal = new bootstrap.Modal(
        document.getElementById('modalShow')
      );
      modal.show();
    });
}

// ================= EDIT =================
function editFasilitas(id){
  fetch('/fasilitas/' + id)
    .then(r => r.json())
    .then(d => {
      document.getElementById('formEdit').action = '/fasilitas/' + d.id;
      document.getElementById('edit_name').value = d.name;
      editorEdit.setData(d.description ?? '');

      if(d.image_url){
        document.getElementById('currentImageImg').src = d.image_url;
        document.getElementById('currentImageWrap').style.display = 'block';
      }

      const modal = new bootstrap.Modal(
        document.getElementById('modalEdit')
      );
      modal.show();
    });
}

// ================= DELETE =================
function deleteFasilitas(id){
  Swal.fire({
    title:'Hapus fasilitas?',
    text:'Data tidak bisa dikembalikan',
    icon:'warning',
    showCancelButton:true,
    confirmButtonText:'Ya, hapus',
    cancelButtonText:'Batal'
  }).then(res=>{
    if(!res.isConfirmed) return;

    fetch('/fasilitas/' + id,{
      method:'DELETE',
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}',
        'Accept':'application/json'
      }
    }).then(() => {
      Swal.fire({
        icon:'success',
        title:'Terhapus',
        timer:1500,
        showConfirmButton:false
      }).then(() => location.reload());
    });
  });
}
</script>

{{-- SUCCESS ALERT --}}
@if(session('success'))
<script>
Swal.fire({
  icon:'success',
  title:'Berhasil',
  text:'{{ session('success') }}',
  timer:2000,
  showConfirmButton:false
});
</script>
@endif

{{-- ERROR ALERT --}}
@if($errors->any())
<script>
Swal.fire({
  icon:'error',
  title:'Oops...',
  html:`{!! implode('<br>', $errors->all()) !!}`
});

document.addEventListener('DOMContentLoaded', function(){
  new bootstrap.Modal(
    document.getElementById('modalCreate')
  ).show();
});
</script>
@endif

@endpush
