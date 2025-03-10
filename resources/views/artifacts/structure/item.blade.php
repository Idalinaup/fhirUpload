@php
    use Illuminate\Support\Facades\Log;
   // $typeObject = $item->getType();
    $type = $item->getType()->getValue();

    $options = collect($item->getExtension())->filter(function ($extension) {
        return $extension->getValueCoding() !== null && $extension->getValueCoding()->getDisplay() !== null;
    });
@endphp


<div data-linkid="{{$item->getLinkId()}}" >
    <div class="flex-container">
        <!-- Content for the first div -->
        <div class="flex-half">
            @if (!Str::endsWith($item->getLinkId(), 'help') && !Str::startsWith($item->getLinkId(), 'ExternalDataFor'))
                <p class="lead inline-element">{{ $text = $item->gettext() }}</p>
                    @if ($item->getRequired() == "true")
                        <span class="inline-element" style="color: red;">*</span>
                    @endif
            @endif

            <!-- Help-Button -->
            
            @foreach($item->getItem() as $itemChild)
                @foreach($itemChild->getExtension() as $extension)
                    @if($extension->getValueCodeableConcept() !== null)
                        @foreach($extension->getValueCodeableConcept()->getCoding() as $coding)
                            @if($coding->getDisplay() == "Help-Button")
                                <span class="inline-element">
                                    @include('artifacts.structure.itemExtension.itemHelp', ['item' => $itemChild])
                                </span>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        </div>

        <!-- Content for the second div -->
        <div class="flex-quarter">
            <div class="questionnaire-item-type" >  
                <input type="hidden" name="type" id="type" value="{{ $type }}">
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
        
                    @case('open-choice')
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
            </div>
        </div>
        
        @foreach($item->getItem() as $itemChild)
            @if($options->isNotEmpty())
            <!-- Content for the third div -->
            <div class="flex-third">
                <div class="form-group">
                    <select name="{{$item->getLinkId()}}[]" class="form-control">
                        @foreach($options as $option)
                            <option value="{{ $option->getValueCoding()->getDisplay() }}">
                                {{ $option->getValueCoding()->getDisplay() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        @endforeach
        @foreach($item->getEnableWhen() as $enableWhen)
            @include('artifacts.structure.itemEnableWhen.itemEnableWhen', ['item' => $enableWhen, 'itemChild'=>$item] ) 
        @endforeach
    </div>

    <div class="questionnaire-item-sub" >
        @foreach($item->getItem() as $itemChild)
            <div class="questionnaire-item">
                @include('artifacts.structure.item', ['item' => $itemChild])
            </div>
        @endforeach
    </div>
</div>

