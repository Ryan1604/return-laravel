<div class="form-button-action">
    <button data-toggle="tooltip" id="btn-detail" class="btn btn-sm btn-icon btn-info" data-original-title="Detail" value="{{ $id }}" data-name="{{ $name }}">
        <i class="fa fa-eye"></i>
    </button>
    @if(auth()->user()->isLibrarian())
        <button type="button" data-toggle="tooltip" id="btn-change-password" class="btn btn-sm btn-icon btn-warning" data-original-title="Change Password" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-lock"></i>
        </button>
        <a href="{{ route('admin.users.edit', $id) }}" data-toggle="tooltip" class="btn btn-sm btn-icon btn-warning" data-original-title="Edit">
            <i class="fa fa-edit"></i>
        </a>
        <button data-toggle="tooltip" id="btn-delete" class="btn btn-sm btn-icon btn-danger" data-original-title="Delete" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-trash-alt"></i>
        </button>
    @endif
</div>
