<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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
    <select class="form-control" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" style="width: 100%;">
        <option value="" disabled selected>Select an option or type a value</option>
        @foreach($item->getAnswerOption() as $answerOption)
            <option value="{{ $answerOption->getValueCoding()->getCode() }}">
                {{ $answerOption->getValueCoding()->getDisplay() }}
            </option>
        @endforeach
    </select>
</div>

<script>
    $(document).ready(function() {
        $('#{{$item->getLinkId()}}').select2({
            tags: true
        });
    });
</script>