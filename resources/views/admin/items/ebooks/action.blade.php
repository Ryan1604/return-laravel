<div class="form-button-action">
    <button type="button" data-toggle="tooltip" id="btn-detail" class="btn btn-sm btn-icon btn-info" data-original-title="Detail" value="{{ $id }}" data-ebook="{{ $title }}">
        <i class="fa fa-eye"></i>
    </button>
    @if(auth()->user()->isLibrarian())
    <a href="{{ route('admin.items.ebooks.edit', $id) }}" data-toggle="tooltip" class="btn btn-sm btn-icon btn-warning" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </a>
    <button type="button" data-toggle="tooltip" id="btn-delete" class="btn btn-sm btn-icon btn-danger" data-original-title="Delete" value="{{ $id }}" data-ebook="{{ $title }}">
        <i class="fa fa-trash-alt"></i>
    </button>
    @endif
</div>
