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


$minValue = null;
$maxValue = null;

foreach ($item->getExtension() as $extension) {
    if ($extension->getUrl() == 'http://hl7.org/fhir/StructureDefinition/minValue') {
        $minValue = $extension->getValueDecimal();
    } elseif ($extension->getUrl() == 'http://hl7.org/fhir/StructureDefinition/maxValue') {
        $maxValue = $extension->getValueDecimal();
    }
}

@endphp

<div class="form-group">
    <input type="number" 
           class="form-control i_{{$item->getLinkId()}}" 
           placeholder="Enter your number here" 
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
</div>

