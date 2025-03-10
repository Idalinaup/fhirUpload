@php
    $initialValue = ""; // Initialize $initialValue

    // Fetch the initial value for the item
    foreach($item->getInitial() as $initial) {
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
        
        // Assign the first non-null value
        $initialValue = $initialBoolean ?? $initialCoding ?? $initialDate ?? $initialDateTime ?? $initialDecimal ?? $initialInteger ?? $initialQuantity ?? $initialReference ?? $initialStringValue ?? $initialTime;
    }

    $minValue = null;
    $maxValue = null;

    // Fetch the minValue and maxValue from the extensions
    foreach ($item->getExtension() as $extension) {
        if ($extension->getUrl() == 'http://hl7.org/fhir/StructureDefinition/minValue') {
            $minValue = $extension->getValueDecimal();
            //log::info($minValue);
        } elseif ($extension->getUrl() == 'http://hl7.org/fhir/StructureDefinition/maxValue') {
            $maxValue = $extension->getValueDecimal();
            //log::info($maxValue);
        }
            elseif ($extension->getUrl() == 'http://hl7.org/fhir/StructureDefinition/questionnaire-itemControl') {
                foreach ($extension->getValueCodeableConcept()->getCoding() as $coding) {
                    if ($coding->getCode() == "slider") {
                        $isSlider = true;
                    }
                }
            }
    }


    
@endphp

@if(isset($isSlider))
    @php
        $DisplayText = $coding->getDisplay(); // Pode ser qualquer combinação de valores entre os delimitadores "#"

        // Extrair o primeiro valor entre os delimitadores "#"
        $firstDelimiterStart = strpos($DisplayText, '#') + 1;
        $firstDelimiterEnd = strpos($DisplayText, '#', $firstDelimiterStart);
        $firstValue = substr($DisplayText, $firstDelimiterStart, $firstDelimiterEnd - $firstDelimiterStart);

        // Extrair o segundo valor entre os delimitadores "#"
        $secondDelimiterStart = strpos($DisplayText, '#', $firstDelimiterEnd + 1) + 1;
        $secondDelimiterEnd = strpos($DisplayText, '#', $secondDelimiterStart);
        $secondValue = substr($DisplayText, $secondDelimiterStart, $secondDelimiterEnd - $secondDelimiterStart);

    @endphp
    <div class="i_{{$item->getLinkId()}}">
        <span> {{ $firstValue }}</span>
        <input type="range"
            name="{{ $item->getLinkId() }}" 
            id="{{ $item->getLinkId() }}" 
            min="{{ $minValue ?? 0 }}" 
            max="{{ $maxValue ?? 100 }}" 
            value="{{ $initialValue ?? 50 }}" 
            class="slider" 
            id="{{ $item->getLinkId() }}">
        <span>{{ $secondValue}}</span>
    </div>
@else
<div class="form-group">
    <input type="number" 
           class="form-control i_{{$item->getLinkId()}}" 
           placeholder="Enter number here" 
           name="{{ $item->getLinkId() }}" 
           id="{{ $item->getLinkId() }}" 
           value="{{ $initialValue }}"
           @if (!is_null($minValue))
               min="{{ $minValue }}"
           @endif
           @if (!is_null($maxValue))
               max="{{ $maxValue }}"
           @endif
           maxlength="{{ $item->getMaxLength() }}" 
           {{ $item->getReadOnly() == "true" ? 'disabled' : '' }}>
           <span class="error-message" id="error-{{$item->getLinkId()}}" style="color: red; display: none;">
            O valor não pode ser superior a {{ $maxValue }}.
        </span>
</div>
@endif

<script>
function validateInput(input, maxValue) {
    const errorMessage = document.getElementById(`error-${input.id}`);
    if (parseFloat(input.value) > maxValue) {
        errorMessage.style.display = 'inline';
    } else {
        errorMessage.style.display = 'none';
    }
}
</script>




<style>
    .slidecontainer {
        display: flex;
        align-items: center;
    }

    .slidecontainer span {
        flex: 1;
        text-align: center;
    }

    .slidecontainer input[type=range] {
        flex: 8;
        margin: 0 10px;
    }
</style>
