@if(auth()->user()->isLibrarian())
<div class="form-button-action">
    <button type="button" data-toggle="tooltip" id="btn-edit-lost-book" class="btn btn-icon btn-sm btn-warning" data-original-title="Edit" value="{{ $id }}" data-book="{{ $title }}">
        <i class="fa fa-edit"></i>
    </button>
</div>
@endif
