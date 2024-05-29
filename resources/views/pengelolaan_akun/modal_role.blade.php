<!-- Modal Create Role -->
@php
    $lang = \App\PermissionHelper::getLang();
@endphp
<div class="modal" id="modal-create-role" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('role.store') }}" method="POST" id="form-create-role">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title">Buat Role Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Permission</label>
                        <div class="form-group">
                            <br/>
                            @foreach($permissions as $permission)
                                <label class="form-check">
                                    <input class="form-check-inline @error('permission') is-invalid @enderror"
                                           type="checkbox" name="permission[]" value="{{$permission->name}}">
                                    @error('permission')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                    <span
                                        class="form-check-label">{{$permission->name}} @isset($lang[$permission->name])
                                            ({{$lang[$permission->name]}})
                                        @endisset</span>
                                </label>
                                <br/>
                            @endforeach
                        </div>
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

{{--    Modal edit role --}}
<div class="modal" id="modal-edit-role" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Permission</label>
                        <div class="form-group">
                            <br/>
                            @foreach($permissions as $permission)
                                <label class="form-check">
                                    <input class="form-check-inline" type="checkbox" name="permission[]"
                                           value="{{$permission->name}}">
                                    <span
                                        class="form-check-label">{{$permission->name}} @isset($lang[$permission->name])
                                            ({{$lang[$permission->name]}})
                                        @endisset</span></span>
                                </label>
                                <br/>
                            @endforeach
                        </div>
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

            @error('create-role')
            $('#modal-create-role').modal('show');
            @enderror

            @error('edit-role')
            $('#modal-edit-role').modal('show');
            @enderror

            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                $('#form-delete').attr('action', url);
            });

            $('#modal-create-role').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.text-danger').text('');
            });

            $('#modal-edit-role').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.text-danger').text('');
            });

            $('#modal-edit-role').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                var action = button.data('action');

                $.get(url, function (data) {
                    $('#modal-edit-role').find('form').attr('action', action);
                    $('#modal-edit-role').find('input[name="name"]').val(data.name);
                    $('#modal-edit-role').find('input[name="permission[]"]').each(function () {
                        if (data.permission_names.includes($(this).val())) {
                            $(this).prop('checked', true);
                        }
                    });
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
