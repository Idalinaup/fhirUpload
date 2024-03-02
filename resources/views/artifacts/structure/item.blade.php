@php
   $typeObject = $item->getType();
   $type = $typeObject->getValue();

   $extensionObject = $item->getExtension();

@endphp

<p>
<h1>

    {{$item->getLinkId()}}
    {{$text = $item->gettext()}}

    {{$type = $typeObject->getValue()}}

    {{$extensionObject = $item->getExtension()}}

</h1>


@foreach($item->getItem() as $itemChild)

    @include('artifacts.structure.item', ['item' => $itemChild])

@endforeach


@foreach($item->getEnableWhen() as $enableWhen)


    @include('artifacts.structure.itemEnbleWhen.itemEnbleWhen', ['item' => $enableWhen] ) 

@endforeach

@foreach($extensionObject as $extension)
    $extension = $object->valueCodeableConcept()->getCoding()->getDisplay();
@endforeach


@switch($type)
    @case('boolean')
        @include('artifacts.structure.itemType.boolean')
        @break

    @case('decimal')
        @include('artifacts.structure.itemType.decimal')
        @break

    @case('integer')
        @include('artifacts.structure.itemType.integer')
        @break

    @case('date')
        @include('artifacts.structure.itemType.date')
        @break

    @case('dateTime')
        @include('artifacts.structure.itemType.dateTime')
        @break

    @case('time')
        @include('artifacts.structure.itemType.time')
        @break

    @case('string')
        @include('artifacts.structure.itemType.string')
        @break

    @case('text')
        @include('artifacts.structure.itemType.text')
        @break

    @case('url')
        @include('artifacts.structure.itemType.url')
        @break

    @case('choice')
        @include('artifacts.structure.itemType.choice')
        @break

    @case('openChoice')
        @include('artifacts.structure.itemType.openChoice')
        @break

    @case('quantity')
        @include('artifacts.structure.itemType.quantity')
        @break

    @case('attachment')
        @include('artifacts.structure.itemType.attachment')
        @break

    @case('reference')
        @include('artifacts.structure.itemType.reference')
        @break
@endswitch

</p>