@if(auth()->user()->role_id === 1)
    <div class="form-button-action">
        <button data-toggle="tooltip" id="btn-edit" class="btn btn-sm btn-icon btn-warning" data-original-title="Edit" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-edit"></i>
        </button>
        <button data-toggle="tooltip" id="btn-delete" class="btn btn-sm btn-icon btn-danger" data-original-title="Delete" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-trash-alt"></i>
        </button>
    </div>
@endif
