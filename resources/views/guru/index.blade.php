@extends('layouts.app')

@section('title','Teacher')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Guru</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Guru
    </button>
  </div>

  <div class="card">
    <div class="card-header">Daftar Guru</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th width="90">Foto</th>
              <th>Nama</th>
              <th width="140">Tgl Lahir</th>
              <th>Email</th>
              <th>Telepon</th>
              <th width="60" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($teachers as $t)
              <tr>
                <td class="text-center">
                  <img src="{{ $t->photo_url }}" alt="photo" style="max-height:60px; max-width:70px;">
                </td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->birth_date ? $t->birth_date->format('Y-m-d') : '-' }}</td>
                <td>{{ $t->email ?? '-' }}</td>
                <td>{{ $t->phone ?? '-' }}</td>

                {{-- AKSI TITIK 3 --}}
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="showTeacher({{ $t->id }})">
                          <i class="bx bx-show me-1"></i> Show
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editTeacher({{ $t->id }})">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteTeacher({{ $t->id }})">
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

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('guru.store') }}" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Foto (Upload)</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
          <small class="text-muted">JPG/PNG/WEBP max 2MB</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Biografi</label>
          <textarea name="biography" id="biography_create" class="form-control" rows="6">{{ old('biography') }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">No Telepon</label>
          <input name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="formEdit" class="modal-content" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input id="edit_name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Lahir</label>
          <input id="edit_birth_date" type="date" name="birth_date" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Foto (Upload baru jika ingin ganti)</label>
          <input name="photo" type="file" class="form-control" accept="image/*">

          <div class="mt-2" id="currentPhotoWrap" style="display:none;">
            <small class="text-muted d-block">Foto saat ini:</small>
            <img id="currentPhotoImg" src="" style="max-height:80px; max-width:140px;">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Biografi</label>
          <textarea id="biography_edit" name="biography" class="form-control" rows="6"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input id="edit_email" name="email" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">No Telepon</label>
          <input id="edit_phone" name="phone" class="form-control">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
        <button class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Show -->
<div class="modal fade" id="modalShow" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
      <div class="d-flex gap-3 align-items-start">
        <img id="show_photo" src="" style="max-height:120px; max-width:120px;">
        <div>
          <h5 id="show_name" class="mb-2"></h5>
          <p class="mb-1"><strong>Tanggal Lahir:</strong> <span id="show_birth_date"></span></p>
          <p class="mb-1"><strong>Email:</strong> <span id="show_email"></span></p>
          <p class="mb-1"><strong>Telepon:</strong> <span id="show_phone"></span></p>
        </div>
      </div>
      <hr>
      <div id="show_biography"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>

<script>
  let editorCreate = null;
  let editorEdit = null;

  function initCreateEditor() {
    const el = document.querySelector('#biography_create');
    if (!el || el.dataset.ckeditorInitialized) return;
    el.dataset.ckeditorInitialized = "1";
    ClassicEditor.create(el).then(ed => editorCreate = ed).catch(console.error);
  }

  function initEditEditor() {
    const el = document.querySelector('#biography_edit');
    if (!el || el.dataset.ckeditorInitialized) return;
    el.dataset.ckeditorInitialized = "1";
    ClassicEditor.create(el).then(ed => editorEdit = ed).catch(console.error);
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('modalCreate')?.addEventListener('shown.bs.modal', initCreateEditor);
    document.getElementById('modalEdit')?.addEventListener('shown.bs.modal', initEditEditor);
  });

  function showTeacher(id){
    fetch('/guru/' + id)
      .then(r => r.json())
      .then(d => {
        document.getElementById('show_photo').src = d.photo_url ?? '';
        document.getElementById('show_name').innerText = d.name ?? '-';
        document.getElementById('show_birth_date').innerText = d.birth_date ?? '-';
        document.getElementById('show_email').innerText = d.email ?? '-';
        document.getElementById('show_phone').innerText = d.phone ?? '-';
        document.getElementById('show_biography').innerHTML = d.biography ?? '-';
        new bootstrap.Modal(document.getElementById('modalShow')).show();
      });
  }

  function editTeacher(id){
    fetch('/guru/' + id)
      .then(r => r.json())
      .then(d => {
        document.getElementById('formEdit').action = '/guru/' + d.id;
        document.getElementById('edit_name').value = d.name ?? '';
        document.getElementById('edit_birth_date').value = d.birth_date ?? '';
        document.getElementById('edit_email').value = d.email ?? '';
        document.getElementById('edit_phone').value = d.phone ?? '';

        const wrap = document.getElementById('currentPhotoWrap');
        const img  = document.getElementById('currentPhotoImg');
        if (d.photo_url) { img.src = d.photo_url; wrap.style.display='block'; }
        else { img.src=''; wrap.style.display='none'; }

        new bootstrap.Modal(document.getElementById('modalEdit')).show();

        const wait = setInterval(() => {
          if (editorEdit) { editorEdit.setData(d.biography ?? ''); clearInterval(wait); }
        }, 50);
      });
  }

  function deleteTeacher(id){
    Swal.fire({
      title:'Hapus data guru?',
      text:'Data yang dihapus tidak bisa dikembalikan',
      icon:'warning',
      showCancelButton:true,
      confirmButtonColor:'#696cff',
      cancelButtonColor:'#8592a3',
      confirmButtonText:'Ya, hapus',
      cancelButtonText:'Batal'
    }).then(res=>{
      if(!res.isConfirmed) return;

      fetch('/guru/' + id,{
        method:'DELETE',
        headers:{
          'X-CSRF-TOKEN':'{{ csrf_token() }}',
          'Accept':'application/json'
        }
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({ icon:'success', title:'Terhapus', text:data.message, timer:1500, showConfirmButton:false })
            .then(()=>location.reload());
        } else {
          Swal.fire('Gagal','Data guru gagal dihapus','error');
        }
      })
      .catch(()=> Swal.fire('Error','Terjadi kesalahan server','error'));
    });
  }
</script>

@if(session('success'))
<script>
  Swal.fire({ icon:'success', title:'Berhasil', text:"{{ session('success') }}", timer:2000, showConfirmButton:false });
</script>
@endif

@if($errors->any())
<script>
  Swal.fire({ icon:'error', title:'Oops...', html:`{!! implode('<br>', $errors->all()) !!}` });
  document.addEventListener('DOMContentLoaded', function(){
    new bootstrap.Modal(document.getElementById('modalCreate')).show();
  });
</script>
@endif
@endpush
