<p>
<h1>


    {{$item->getLinkId()}}
    
</h1>


@foreach($item->getItem() as $itemChild)

    @include('artifacts.structure.item', ['item' => $itemChild])

@endforeach

</p>