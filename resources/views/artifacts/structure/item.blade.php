@php
   $typeObject = $item->getType()
@endphp

<p>
<h1>

    {{$item->getLinkId()}}
    {{$text = $item->gettext()}}

    {{$type = $typeObject->getValue()}}
    
</h1>


@foreach($item->getItem() as $itemChild)

    @include('artifacts.structure.item', ['item' => $itemChild])

@endforeach


@if ($type == "boolean")

    @include('artifacts.structure.boolean')

@endif

@if ($type == "decimal")

    @include('artifacts.structure.decimal')
    
@endif

@if ($type == "integer")

    @include('artifacts.structure.integer')
    
@endif

@if ($type == "date")

    @include('artifacts.structure.date')

@endif

@if ($type == "dateTime")

    @include('artifacts.structure.dateTime')

@endif

@if ($type == "time")

    @include('artifacts.structure.time')

@endif


@if ($type == "string")

    @include('artifacts.structure.string')

@endif

@if ($type == "text")

    @include('artifacts.structure.text')

@endif

@if ($type == "url")

    @include('artifacts.structure.url')

@endif

@if ($type == "coding")

    @include('artifacts.structure.coding')

@endif

@if ($type == "attachment")

    @include('artifacts.structure.attachment')

@endif

@if ($type == "reference")

    @include('artifacts.structure.reference')

@endif





</p>