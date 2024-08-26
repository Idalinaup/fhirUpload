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

$answerOptions = $item->getAnswerOption(); // Supondo que getAnswerOption() retorna um array

$answerArrays = []; // Array para armazenar todas as opções de resposta


foreach ($answerOptions as $answerOption)
    if ($answerOption->getValueCoding() != null)
            $answerArray = [
                'code' => $answerOption->getValueCoding()->getCode(),
                'system' => $answerOption->getValueCoding()->getSystem(),
                'display' => $answerOption->getValueCoding()->getDisplay()
            ];
@endphp

@if($item->getRepeats() == "true")
    @foreach($item->getAnswerOption() as $answerOption)
        <div class="form-check">
            @if($answerOption->getValueString() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueString() }}">
                    <label value="{{ $answerOption->getValueString()  }}">
                        {{ $answerOption->getValueString() }}
                    </label>
            @elseif($answerOption->getValueInteger() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueInteger() }}">
                    <label value="{{ $answerOption->getValueInteger()  }}">
                        {{ $answerOption->getValueInteger() }}
                    </label>
            @elseif($answerOption->getValueDate() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueDate() }}">
                    <label value="{{ $answerOption->getValueDate()  }}">
                        {{ $answerOption->getValueDate() }}
                    </label>
            @elseif($answerOption->getValueTime() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueTime() }}">
                    <label value="{{ $answerOption->getValueTime()  }}">
                        {{ $answerOption->getValueTime() }}
                    </label>
            @elseif($answerOption->getValueCoding() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" id="{{$item->getLinkId()}}[]" name="{{$item->getLinkId()}}[]" value="{{ json_encode($answerOption->getValueCoding()) }}">
                    <label class="form-check-label" for="{{$item->getLinkId()}}">
                        {{ $answerOption->getValueCoding()->getDisplay() }}
                    </label>
            @elseif($answerOption->getValueReference() != null)
                <input class="form-check-input i_{{$item->getLinkId()}}" type="checkbox" name="{{$item->getLinkId()}}[]" id="{{$item->getLinkId()}}" value="{{ $answerOption->getValueReference() }}">
                    <label value="{{ $answerOption->getValueReference()  }}">
                        {{ $answerOption->getValueReference() }}
                    </label>
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
    <select class="form-select i_{{$item->getLinkId()}}" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}">
        <option value="" disabled selected>Selecione uma opção</option>
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
            @elseif($answerOption->getValueCoding() != null)
                <option value="{{ $answerOption->getValueCoding()->getCode() }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                    @if($answerOption->getValueCoding()->getDisplay() != null)
                        {{ $answerOption->getValueCoding()->getDisplay() }}
                    @else
                        {{ $answerOption->getValueCoding()->getCode() }}
                    @endif
                </option>
            @endif
        @endforeach
    </select>
@endif