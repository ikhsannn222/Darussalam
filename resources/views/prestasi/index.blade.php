@extends('layouts.app')

@section('title','Prestasi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Prestasi</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Prestasi
    </button>
  </div>

  <div class="card">
    <div class="card-header">Daftar Prestasi</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th width="80">Gambar</th>
              <th>Nama</th>
              <th width="120">Tanggal</th>
              <th width="150">Tingkat</th>
              <th>Deskripsi</th>
              <th width="60" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($prestasi as $p)
              <tr>
                <td class="text-center">
                  @if($p->image_url)
                    <img src="{{ $p->image_url }}" style="max-height:60px;">
                  @endif
                </td>
                <td>{{ $p->name }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td>{{ $p->tingkat_kejuaraan }}</td>
                <td>{!! Str::limit($p->description, 80) !!}</td>

                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="showPrestasi({{ $p->id }})">
                          <i class="bx bx-show me-1"></i> Show
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editPrestasi({{ $p->id }})">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deletePrestasi({{ $p->id }})">
                          <i class="bx bx-trash me-1"></i> Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('prestasi.store') }}" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Prestasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tingkat Kejuaraan</label>
          <input name="tingkat_kejuaraan" class="form-control" required>
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

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="formEdit" class="modal-content" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Edit Prestasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input id="edit_name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tingkat Kejuaraan</label>
          <input id="edit_tingkat" name="tingkat_kejuaraan" class="form-control" required>
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

{{-- ================= MODAL SHOW ================= --}}
<div class="modal fade" id="modalShow" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
      <div class="d-flex gap-3">
        <img id="show_image" style="max-height:120px;">
        <div>
          <h5 id="show_name"></h5>
          <p class="mb-1"><strong>Tanggal:</strong> <span id="show_tanggal"></span></p>
          <p><strong>Tingkat:</strong> <span id="show_tingkat"></span></p>
        </div>
      </div>
      <hr>
      <div id="show_description"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>

<script>
let editorCreate, editorEdit;

ClassicEditor.create(document.querySelector('#description_create'))
  .then(e => editorCreate = e);

ClassicEditor.create(document.querySelector('#description_edit'))
  .then(e => editorEdit = e);

// SHOW
function showPrestasi(id){
  fetch('/prestasi/' + id)
    .then(r => r.json())
    .then(d => {
      document.getElementById('show_image').src = d.image_url ?? '';
      document.getElementById('show_name').innerText = d.name;
      document.getElementById('show_tanggal').innerText = d.tanggal;
      document.getElementById('show_tingkat').innerText = d.tingkat_kejuaraan;
      document.getElementById('show_description').innerHTML = d.description;

      new bootstrap.Modal(document.getElementById('modalShow')).show();
    });
}

// EDIT
function editPrestasi(id){
  fetch('/prestasi/' + id)
    .then(r => r.json())
    .then(d => {
      document.getElementById('formEdit').action = '/prestasi/' + d.id;
      document.getElementById('edit_name').value = d.name;
      document.getElementById('edit_tanggal').value = d.tanggal;
      document.getElementById('edit_tingkat').value = d.tingkat_kejuaraan;
      editorEdit.setData(d.description ?? '');

      if(d.image_url){
        document.getElementById('currentImageImg').src = d.image_url;
        document.getElementById('currentImageWrap').style.display = 'block';
      }

      new bootstrap.Modal(document.getElementById('modalEdit')).show();
    });
}

// DELETE
function deletePrestasi(id){
  Swal.fire({
    title:'Hapus prestasi?',
    text:'Data tidak bisa dikembalikan',
    icon:'warning',
    showCancelButton:true,
    confirmButtonText:'Ya, hapus',
    cancelButtonText:'Batal'
  }).then(res=>{
    if(!res.isConfirmed) return;

    fetch('/prestasi/' + id,{
      method:'DELETE',
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}',
        'Accept':'application/json'
      }
    }).then(() => location.reload());
  });
}
</script>

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

@endpush
