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

<div class="form-group">
    <input type="number" 
           class="form-control" 
           placeholder="Enter your number here" 
           name="{{ $item->getLinkId() }}" 
           id="{{ $item->getLinkId() }}" 
           value="{{ $initialValue }}"
           min="{{ $item->getExtensionValue('http://hl7.org/fhir/StructureDefinition/minValue') }}"
           max="{{ $item->getExtensionValue('http://hl7.org/fhir/StructureDefinition/maxValue') }}"
           {{ $item->getReadOnly() == "true" ? 'disabled' : '' }}>
</div>

