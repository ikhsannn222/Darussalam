@extends('layouts.app')

@section('title','Pendaftaran')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pendaftaran</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Pendaftaran
    </button>
  </div>

  {{-- FILTER --}}
  <div class="card mb-3">
    <div class="card-body">
      <form method="GET" class="row g-2">
        <div class="col-md-4">
          <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama siswa / orang tua">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-secondary w-100">Filter</button>
        </div>
      </form>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="card">
    <div class="card-header">Daftar Pendaftaran</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Nama Siswa</th>
            <th>Program</th>
            <th>Kelas</th>
            <th>Orang Tua</th>
            <th>Status</th>
            <th width="60" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendaftaran as $p)
          <tr>
            <td>{{ $p->student_name }}</td>
            <td>{{ $p->program_name }}</td>
            <td>{{ $p->class_name ?? '-' }}</td>
            <td>
              <strong>{{ $p->parent_name }}</strong><br>
              <small>{{ $p->parent_phone }}</small>
            </td>
            <td>
              <span class="badge bg-{{
                $p->status=='approved'?'success':($p->status=='rejected'?'danger':'warning') }}">
                {{ ucfirst($p->status) }}
              </span>
            </td>
            <td class="text-center">
              <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" onclick="showData({{ $p->id }})"><i class="bx bx-show"></i> Show</a></li>
                  <li><a class="dropdown-item" onclick="editData({{ $p->id }})"><i class="bx bx-edit"></i> Edit</a></li>
                  <li><a class="dropdown-item text-danger" onclick="deleteData({{ $p->id }})"><i class="bx bx-trash"></i> Delete</a></li>
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

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('pendaftaran.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pendaftaran</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label>Nama Siswa</label>
          <input name="student_name" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label>Tanggal Lahir</label>
          <input type="date" name="birth_date" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label>Jenis Kelamin</label>
          <select name="gender" class="form-select" required>
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
          </select>
        </div>

        <div class="col-md-6">
          <label>Program</label>
          <select name="program_id" class="form-select" required>
            @foreach(DB::table('programs')->where('is_open_registration',1)->get() as $prog)
              <option value="{{ $prog->id }}">{{ $prog->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <label>Alamat</label>
          <textarea id="address_create" name="address" class="form-control"></textarea>
        </div>

        <hr>

        <div class="col-md-6">
          <label>Nama Orang Tua</label>
          <input name="parent_name" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label>No HP Orang Tua</label>
          <input name="parent_phone" class="form-control" required>
        </div>

        <div class="col-12">
          <label>Email Orang Tua</label>
          <input name="parent_email" class="form-control">
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
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
        <h5 class="modal-title">Update Pendaftaran</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label>Status</label>
          <select name="status" id="edit_status" class="form-select">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="col-md-6">
          <label>Kelas (jika approved)</label>
          <select name="class_id" id="edit_class" class="form-select">
            <option value="">- Pilih Kelas -</option>
            @foreach(DB::table('classes')->get() as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <label>Catatan</label>
          <textarea name="notes" id="edit_notes" class="form-control"></textarea>
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
      <h5 id="show_student"></h5>
      <p><strong>Alamat:</strong></p>
      <div id="show_address" class="border rounded p-2"></div>
      <hr>
      <p><strong>Status:</strong> <span id="show_status"></span></p>
      <p><strong>Catatan:</strong></p>
      <div id="show_notes"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.0/classic/ckeditor.js"></script>
<script>
let addressEditor=null;

ClassicEditor.create(document.querySelector('#address_create'))
  .then(ed=>addressEditor=ed);

function showData(id){
  fetch('/admin/pendaftaran/'+id)
  .then(r=>r.json())
  .then(d=>{
    document.getElementById('show_student').innerText=d.student_name;
    document.getElementById('show_address').innerHTML=d.address;
    document.getElementById('show_status').innerText=d.status;
    document.getElementById('show_notes').innerHTML=d.notes ?? '-';
    new bootstrap.Modal(modalShow).show();
  });
}

function editData(id){
  fetch('/admin/pendaftaran/'+id)
  .then(r=>r.json())
  .then(d=>{
    formEdit.action='/admin/pendaftaran/'+d.id;
    edit_status.value=d.status;
    edit_class.value=d.class_id ?? '';
    edit_notes.value=d.notes ?? '';
    new bootstrap.Modal(modalEdit).show();
  });
}

function deleteData(id){
  Swal.fire({
    title:'Hapus pendaftaran?',
    icon:'warning',
    showCancelButton:true
  }).then(res=>{
    if(!res.isConfirmed) return;
    fetch('/admin/pendaftaran/'+id,{
      method:'DELETE',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(()=>location.reload());
  });
}
</script>
@endpush
