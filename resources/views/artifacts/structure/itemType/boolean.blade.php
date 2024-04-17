@php
    $initialValue = " "; // Initialize $initialValue

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

<div class="form-check">
    <input class="form-check-input" type="radio" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value=true {{ ($item->getreadOnly() == "true") ? "disabled" : "" }}>
    <label class="form-check-label" for="{{$item->getLinkId()}}_">Yes</label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value=false {{ ($item->getreadOnly() == "true") ? "disabled" : "" }}>
    <label class="form-check-label" for="{{$item->getLinkId()}}">No</label>
</div>


<script>
    var radios = document.getElementsByName('{{$item->getLinkId()}}');

    // Add a change event listener to each radio button
    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('change', function() {
            // Log the value of the selected radio button to the console
            console.log(this.value);
        });
    }
</script>