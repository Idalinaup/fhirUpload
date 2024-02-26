@php
   // dd($object->getItem());
@endphp

<h1>
    {{$object->getUrl()}}
</h1>

@foreach($object->getItem() as $item)

    @include('artifacts.structure.item')

@endforeach