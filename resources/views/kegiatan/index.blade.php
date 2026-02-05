@extends('layouts.app')

@section('title','Kegiatan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kegiatan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Kegiatan
    </button>
  </div>

  <div class="card">
    <div class="card-header">Daftar Kegiatan</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th width="90">Gambar</th>
              <th>Judul</th>
              <th width="140">Tanggal</th>
              <th width="60" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kegiatans as $k)
              <tr>
                <td class="text-center">
                  <img src="{{ $k->image_url }}" style="max-height:60px; max-width:70px;">
                </td>
                <td>{{ $k->title }}</td>
                <td>{{ $k->date ?? '-' }}</td>

                {{-- AKSI TITIK 3 --}}
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="showKegiatan({{ $k->id }})">
                          <i class="bx bx-show me-1"></i> Show
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editKegiatan({{ $k->id }})">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteKegiatan({{ $k->id }})">
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

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('kegiatan.store') }}" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kegiatan</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Judul</label>
          <input name="title" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="date" class="form-control">
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
        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
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
        <h5 class="modal-title">Edit Kegiatan</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Judul</label>
          <input id="edit_title" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input id="edit_date" type="date" name="date" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Gambar Baru</label>
          <input type="file" name="image" class="form-control">

          <div class="mt-2" id="currentImageWrap" style="display:none;">
            <small class="text-muted">Gambar saat ini:</small><br>
            <img id="currentImageImg" style="max-height:80px;">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea id="description_edit" name="description" class="form-control" rows="6"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
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
          <h5 id="show_title"></h5>
          <p><strong>Tanggal:</strong> <span id="show_date"></span></p>
        </div>
      </div>
      <hr>
      <div id="show_description"></div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>

<script>
let editorCreate=null, editorEdit=null;

ClassicEditor.create(document.querySelector('#description_create'))
  .then(e=>editorCreate=e);

ClassicEditor.create(document.querySelector('#description_edit'))
  .then(e=>editorEdit=e);

// ================= SHOW =================
function showKegiatan(id){
  fetch('/kegiatan/'+id)
    .then(r=>r.json())
    .then(d=>{
      show_image.src = d.image_url ?? '';
      show_title.innerText = d.title;
      show_date.innerText = d.date ?? '-';
      show_description.innerHTML = d.description ?? '-';
      new bootstrap.Modal(modalShow).show();
    });
}

// ================= EDIT =================
function editKegiatan(id){
  fetch('/kegiatan/'+id)
    .then(r=>r.json())
    .then(d=>{
      formEdit.action = '/kegiatan/'+d.id;
      edit_title.value = d.title;
      edit_date.value = d.date ?? '';

      if(d.image_url){
        currentImageImg.src = d.image_url;
        currentImageWrap.style.display = 'block';
      }

      editorEdit.setData(d.description ?? '');
      new bootstrap.Modal(modalEdit).show();
    });
}

// ================= DELETE (SWEET ALERT) =================
function deleteKegiatan(id){
  Swal.fire({
    title: 'Hapus kegiatan?',
    text: 'Data yang dihapus tidak bisa dikembalikan',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch('/kegiatan/' + id, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Kegiatan berhasil dihapus',
        timer: 1500,
        showConfirmButton: false
      }).then(() => location.reload());
    })
    .catch(() => {
      Swal.fire('Error', 'Gagal menghapus data', 'error');
    });
  });
}
</script>

{{-- SWEET ALERT DARI SESSION --}}
@if(session('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Sukses',
  text: '{{ session('success') }}',
  timer: 2000,
  showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops',
  text: '{{ session('error') }}'
});
</script>
@endif
@endpush
