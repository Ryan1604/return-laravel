@if($rack_id <> NULL)
    {{ $rack['position'] }} - {{ $rack['category']['name'] }}
@else
    UNCLASSIFIED
@endif
