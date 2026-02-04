@extends('layouts.app')

@section('title','Testimoni')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Testimoni</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Testimoni
    </button>
  </div>

  {{-- TABLE --}}
  <div class="card">
    <div class="card-header">Daftar Testimoni</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Peran</th>
            <th>Rating</th>
            <th>Status</th>
            <th width="60" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($testimonis as $t)
          <tr>
            <td>{{ $t->name }}</td>
            <td>{{ $t->role }}</td>
            <td>{{ $t->rating }} ★</td>
            <td>
              <span class="badge bg-{{ $t->is_approved ? 'success':'secondary' }}">
                {{ $t->is_approved ? 'Approved':'Pending' }}
              </span>
            </td>
            <td class="text-center">
              <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" onclick="showData({{ $t->id }})">
                      <i class="bx bx-show"></i> Show
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" onclick="editData({{ $t->id }})">
                      <i class="bx bx-edit"></i> Edit
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item text-danger" onclick="deleteData({{ $t->id }})">
                      <i class="bx bx-trash"></i> Delete
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

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('testimoni.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Testimoni</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label>Nama</label>
          <input name="name" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label>Peran</label>
          <input name="role" class="form-control" value="Orang Tua Santri">
        </div>

        <div class="col-md-4">
          <label>Rating</label>
          <select name="rating" class="form-select">
            @for($i=5;$i>=1;$i--)
              <option value="{{ $i }}">{{ $i }} ★</option>
            @endfor
          </select>
        </div>

        <div class="col-12">
          <label>Pesan</label>
          <textarea name="message" id="message_create" class="form-control"></textarea>
        </div>

        <input type="hidden" name="is_approved" value="1">
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
    <form method="POST" id="formEdit" class="modal-content">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Edit Testimoni</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label>Nama</label>
          <input name="name" id="edit_name" class="form-control">
        </div>

        <div class="col-md-6">
          <label>Peran</label>
          <input name="role" id="edit_role" class="form-control">
        </div>

        <div class="col-md-4">
          <label>Rating</label>
          <select name="rating" id="edit_rating" class="form-select">
            @for($i=5;$i>=1;$i--)
              <option value="{{ $i }}">{{ $i }} ★</option>
            @endfor
          </select>
        </div>

        <div class="col-12">
          <label>Pesan</label>
          <textarea name="message" id="edit_message" class="form-control"></textarea>
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
    <div class="modal-content p-4">
      <h5 id="show_name"></h5>
      <small id="show_role"></small>
      <hr>
      <div id="show_message"></div>
      <hr>
      <div id="show_rating"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>
<script>
const modalShow = document.getElementById('modalShow');
const modalEdit = document.getElementById('modalEdit');
const formEdit  = document.getElementById('formEdit');

ClassicEditor.create(document.querySelector('#message_create'));
ClassicEditor.create(document.querySelector('#edit_message'));

function showData(id){
  fetch('/testimoni/'+id)
    .then(r=>r.json())
    .then(d=>{
      show_name.innerText=d.name;
      show_role.innerText=d.role;
      show_message.innerHTML=d.message;
      show_rating.innerHTML='Rating: '+d.rating+' ★';
      new bootstrap.Modal(modalShow).show();
    });
}

function editData(id){
  fetch('/testimoni/'+id)
    .then(r=>r.json())
    .then(d=>{
      formEdit.action='/testimoni/'+id;
      edit_name.value=d.name;
      edit_role.value=d.role;
      edit_rating.value=d.rating;
      edit_message.value=d.message;
      new bootstrap.Modal(modalEdit).show();
    });
}

function deleteData(id){
  Swal.fire({
    title:'Hapus testimoni?',
    icon:'warning',
    showCancelButton:true
  }).then(res=>{
    if(!res.isConfirmed) return;
    fetch('/testimoni/'+id,{
      method:'DELETE',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(()=>location.reload());
  });
}
</script>
@endpush
