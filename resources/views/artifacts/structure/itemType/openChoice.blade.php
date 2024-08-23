@php
use Illuminate\Support\Facades\Log;

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
            @if($answerOption->getValueString() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueString() }}">
                    <option value="{{ $answerOption->getValueString()  }}">
                        {{ $answerOption->getValueString() }}
                    </option>
            @elseif($answerOption->getValueInteger() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueInteger() }}">
                    <option value="{{ $answerOption->getValueInteger()  }}">
                        {{ $answerOption->getValueInteger() }}
                    </option>
            @elseif($answerOption->getValueDate() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueDate() }}">
                    <option value="{{ $answerOption->getValueDate()  }}">
                        {{ $answerOption->getValueDate() }}
                    </option>
            @elseif($answerOption->getValueTime() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueTime() }}">
                    <option value="{{ $answerOption->getValueTime()  }}">
                        {{ $answerOption->getValueTime() }}
                    </option>
            @elseif($answerOption->getValueCoding() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" id="{{$item->getLinkId()}}" name="{{$item->getLinkId()}}[]" value="{{ $answerOption->getValueCoding()->getCode() }}">
                    <label class="form-check-label" for="{{$item->getLinkId()}}">
                        {{ $answerOption->getValueCoding()->getDisplay() }}
                    </label>
            @elseif($answerOption->getValueReference() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueReference() }}">
                    <option value="{{ $answerOption->getValueReference()  }}">
                        {{ $answerOption->getValueReference() }}
                    </option>
            @elseif($answerOption->getExtension() != null)
                    @foreach($answerOption->getExtension() as $extension)
                        <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $extension->getValueString() }}">
                        <label class="form-check-label" for="{{$item->getLinkId()}}">
                            {{ $extension->getValueString() }}
                        </label>
                    @endforeach
            @endif
        </div>
    @endforeach
@else
<select class="form-select i_{{$item->getLinkId()}}"  name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}">
    <option value="" disabled selected>Select an option or text </option>
    @foreach($item->getAnswerOption() as $answerOption)
        @if($answerOption->getValueString() != null)
            <option value="{{ $answerOption->getValueString() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                {{ $answerOption->getValueString() }}
            </option>
        @elseif($answerOption->getValueInteger() != null)
            <option value="{{ $answerOption->getValueInteger() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                {{ $answerOption->getValueInteger() }}
            </option>
        @elseif($answerOption->getValueDate() != null)
            <option value="{{ $answerOption->getValueDate() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                {{ $answerOption->getValueDate() }}
            </option>
        @elseif($answerOption->getValueTime() != null)
            <option value="{{ $answerOption->getValueTime() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                {{ $answerOption->getValueTime() }}
            </option>
        @elseif($answerOption->getExtension() != null)
            @foreach($answerOption->getExtension() as $extension)
                <option value="{{ $extension->getValueString() }}" {{ $extension->getInitialSelected() ? 'selected' : '' }}>
                    {{ $extension->getValueString() }}
                </option>
            @endforeach
        @else
            <option value="code:{{ $answerOption->getValueCoding()->getCode() }} display:{{ $answerOption->getValueCoding()->getDisplay() }} system:{{ $answerOption->getValueCoding()->getSystem() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                @if($answerOption->getValueCoding()->getDisplay() == null)
                    {{ $answerOption->getValueCoding()->getCode() }}
                @else
                    {{ $answerOption->getValueCoding()->getDisplay() }}
                @endif
            </option>
        @endif
    @endforeach
</select>
@endif
