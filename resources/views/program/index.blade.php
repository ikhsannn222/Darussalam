@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Program</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
      Tambah Program
    </button>
  </div>

  <div class="card">
    <div class="card-header">Daftar Program</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>Nama</th>
              <th width="140">Logo</th>
              <th width="110">Open Reg</th>
              <th>Deskripsi</th>
              <th width="60" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($programs as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td class="text-center">
                  @if($p->logo)
                    <img src="{{ asset('storage/'.$p->logo) }}" alt="logo" style="max-height:60px; max-width:120px;">
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $p->is_open_registration ? 'Ya' : 'Tidak' }}</td>
                <td>{!! $p->description !!}</td>

                {{-- AKSI TITIK 3 --}}
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="openEdit({{ $p->id }})">
                          <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProgram({{ $p->id }})">
                          <i class="bx bx-trash me-1"></i> Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Belum ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Tambah Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('program.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Logo (Upload)</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" id="description_create" class="form-control" rows="6">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_open_registration" id="openRegCreate"
              {{ old('is_open_registration') ? 'checked' : '' }}>
            <label class="form-check-label" for="openRegCreate">Open Registration</label>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" id="editName" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Logo (Upload baru jika ingin ganti)</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            <div class="mt-2" id="currentLogoWrap" style="display:none;">
              <small class="text-muted d-block">Logo saat ini:</small>
              <img id="currentLogoImg" src="" style="max-height:70px; max-width:160px;">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" id="description_edit" class="form-control" rows="6"></textarea>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_open_registration" id="editOpenReg">
            <label class="form-check-label" for="editOpenReg">Open Registration</label>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-label-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
          <button class="btn btn-primary" type="submit">Update</button>
        </div>

      </form>

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
    const el = document.querySelector('#description_create');
    if (!el || el.dataset.ckeditorInitialized) return;
    el.dataset.ckeditorInitialized = "1";
    ClassicEditor.create(el).then(ed => editorCreate = ed).catch(console.error);
  }

  function initEditEditor() {
    const el = document.querySelector('#description_edit');
    if (!el || el.dataset.ckeditorInitialized) return;
    el.dataset.ckeditorInitialized = "1";
    ClassicEditor.create(el).then(ed => editorEdit = ed).catch(console.error);
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('createModal')?.addEventListener('shown.bs.modal', initCreateEditor);
    document.getElementById('editModal')?.addEventListener('shown.bs.modal', initEditEditor);
  });

  function openEdit(id) {
    fetch("{{ url('/program') }}/" + id)
      .then(res => res.json())
      .then(data => {
        document.getElementById('editName').value = data.name ?? '';
        document.getElementById('editOpenReg').checked = data.is_open_registration ? true : false;
        document.getElementById('editForm').action = "{{ url('/program') }}/" + id;

        const wrap = document.getElementById('currentLogoWrap');
        const img  = document.getElementById('currentLogoImg');
        if (data.logo_url) { img.src = data.logo_url; wrap.style.display='block'; }
        else { img.src=''; wrap.style.display='none'; }

        new bootstrap.Modal(document.getElementById('editModal')).show();

        const wait = setInterval(() => {
          if (editorEdit) { editorEdit.setData(data.description ?? ''); clearInterval(wait); }
        }, 50);
      });
  }

  function deleteProgram(id) {
    Swal.fire({
      title: 'Hapus program?',
      text: 'Data yang dihapus tidak bisa dikembalikan',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#696cff',
      cancelButtonColor: '#8592a3',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then((res) => {
      if (!res.isConfirmed) return;

      fetch("{{ url('/program') }}/" + id, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": "{{ csrf_token() }}",
          "Accept": "application/json"
        }
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({ icon:'success', title:'Terhapus', text:data.message, timer:1500, showConfirmButton:false })
            .then(()=>location.reload());
        } else {
          Swal.fire('Gagal', 'Data gagal dihapus', 'error');
        }
      })
      .catch(()=> Swal.fire('Error', 'Terjadi kesalahan server', 'error'));
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
    new bootstrap.Modal(document.getElementById('createModal')).show();
  });
</script>
@endif
@endpush
