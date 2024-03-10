<link href="{{ asset('css/style.css') }}" rel="stylesheet">

@php
   $typeObject = $item->getType();
   $type = $typeObject->getValue();

@endphp

@if (!Str::endsWith($item->getLinkId(), '_help'))
    <div class="flex-container">
        <p class="lead inline-element">{{ $item->getLinkId() }}</p>
        <p class="lead inline-element">{{ $text = $item->gettext() }}</p>
@endif

        @foreach($item->getItem() as $itemChild)
            @foreach($itemChild->getExtension() as $extension)
                @if($extension->getValueCodeableConcept() !== null)
                    @foreach($extension->getValueCodeableConcept()->getCoding() as $coding)
                        @if($coding->getDisplay() == "Help-Button")
                            <span class="inline-element">
                                @include('artifacts.structure.itemExtension.itemExtension', ['item' => $itemChild])
                            </span>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endforeach
    </div>

@foreach($item->getItem() as $itemChild)
    <div class="questionnaire-item">
        @include('artifacts.structure.item', ['item' => $itemChild])

        @foreach($itemChild->getEnableWhen() as $enableWhen)
            @include('artifacts.structure.itemEnbleWhen.itemEnbleWhen', ['item' => $enableWhen] ) 
        @endforeach
    </div>
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

@foreach($item->getEnableWhen() as $enableWhen)

    @include('artifacts.structure.itemEnbleWhen.itemEnbleWhen', ['item' => $enableWhen] ) 

@endforeach

</p>
