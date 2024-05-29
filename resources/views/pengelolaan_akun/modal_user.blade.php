<!-- Modal Create User -->
<div class="modal" id="modal-create-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST" id="form-create-user">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title">Buat Akun Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role"
                                required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->name }}" {{ $role->name == old('role') ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" placeholder="Password...">
                        <small>* biarkan kosong jika tidak ingin mengganti password</small>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--    Modal edit user --}}
<div class="modal" id="modal-edit-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                                required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->name }}" {{ $role->name == old('role') ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password...">
                        <small>* biarkan kosong jika tidak ingin mengganti password</small>
                        @error('role')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('modal-scripts')
    <script type="module">
        $(document).ready(function () {
            @error('create-user')
            $('#modal-create-user').modal('show');
            @enderror

            @error('edit-user')
            $('#modal-edit-user').modal('show');
            @enderror

            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                $('#form-delete').attr('action', url);
            });

            $('#modal-create-user').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.text-danger').text('');
            });

            $('#modal-edit-user').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.text-danger').text('');
            });

            $('#modal-edit-user').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                var action = button.data('action');
                $.get(url, function (data) {
                    $('#modal-edit-user form').attr('action', action);
                    $('#modal-edit-user input[name="email"]').val(data.email);
                    $('#modal-edit-user #name').val(data.name);
                    $('#modal-edit-user #role').val(data.roles[0].name);
                });
            });

            $('#modal-create-user').on('show.bs.modal', function () {
                $('#modal-create-user').find('.is-invalid').removeClass('is-invalid');
                $('#modal-create-user').find('.text-danger').text('');

                // Reset form
                $(this).find('input, select').each(function () {
                    if (this.tagName.toLowerCase() === 'input') {
                        if (this.name !== '_token' && this.name !== '_method') {
                            $(this).val('');
                        }
                    } else if (this.tagName.toLowerCase() === 'select') {
                        $(this).val('').change()
                    }
                });
            });
        })
    </script>
@endpush
