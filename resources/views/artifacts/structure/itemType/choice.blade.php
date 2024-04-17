@php
    $initialValue = ""; // Initialize $initialValue

foreach($item->getInitial() as $initial){
        $initialBoolean = $initial->getValueBoolean();
        $initialCoding = $initial->getValueCoding() ? $initial->getValueCoding()->getCode() : null;
        $initialDate = $initial->getValueDate();
        $initialDateTime = $initial->getValueDateTime();
        $initialDecimal = $initial->getValueDecimal();
        $initialInteger = $initial->getValueInteger();
        $initialQuantity = $initial->getValueQuantity();
        $initialReference = $initial->getValueReference();
        $initialStringValue = $initial->getValueString();
        $initialTime = $initial->getValueTime();
        
        $initialValue = $initialBoolean ?? $initialCoding ?? $initialDate ?? $initialDateTime ?? $initialDecimal ?? $initialInteger ?? $initialQuantity ?? $initialReference ?? $initialStringValue ?? $initialTime;
}

@endphp

@if($item->getRepeats() == "true")
    @foreach($item->getAnswerOption() as $answerOption)
        <div class="form-check">
            @if($answerOption->getExtension() != null)
                @foreach($answerOption->getExtension() as $extension)
                    <input class="form-check-input" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $extension->getValueString() }}">
                    <label class="form-check-label" for="{{$item->getLinkId()}}">
                        {{ $extension->getValueString() }}
                    </label>
                @endforeach
            @else
            
            <input class="form-check-input" type="checkbox" id="{{$item->getLinkId()}}" name="{{$item->getLinkId()}}[]" value="{{ $answerOption->getValueCoding()->getCode() }}">
                <label class="form-check-label" for="{{$item->getLinkId()}}">
                    {{ $answerOption->getValueCoding()->getDisplay() }}
                </label>
            @endif
        </div>
    @endforeach
@else
    <select class="form-select" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}">
        <option value="" disabled selected>Select an option</option>
        @foreach($item->getAnswerOption() as $answerOption)
            @if($answerOption->getExtension() != null)
                @foreach($answerOption->getExtension() as $extension)
                    <option value="{{ $extension->getValueString() }}">
                        {{ $extension->getValueString() }}
                    </option>
                @endforeach
            @else
                <option value="{{ $answerOption->getValueCoding()->getCode() }}">
                    {{ $answerOption->getValueCoding()->getDisplay() }}
                </option>
            @endif
        @endforeach
    </select>
@endif
