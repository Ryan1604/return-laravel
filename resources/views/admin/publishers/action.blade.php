@if(auth()->user()->isLibrarian())
    <div class="form-button-action d-flex">
        <button data-toggle="tooltip" id="btn-edit" class="btn btn-sm btn-icon btn-warning mr-1" data-original-title="Edit" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-edit"></i>
        </button>
        <button data-toggle="tooltip" id="btn-delete" class="btn btn-sm btn-icon btn-danger" data-original-title="Delete" value="{{ $id }}" data-name="{{ $name }}">
            <i class="fa fa-trash-alt"></i>
        </button>
    </div>
@endif
