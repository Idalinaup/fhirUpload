@php
    $initialValue = ""; // Initialize $initialValue

foreach($item->getInitial() as $initial){
    if($initial->getValueString() != null){
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
}

@endphp


<input type="time" class="i_{{$item->getLinkId()}}" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value="{{ $initialValue }}" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
