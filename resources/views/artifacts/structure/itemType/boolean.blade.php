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

<div class="row">
    <div class="col-sm-4">
        <div class="form-check">
            <input class="form-check-input i_{{$item->getLinkId()}}" type="radio" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value="true" {{ ($item->getreadOnly() == "true") ? "disabled" : "" }}>
            <label class="form-check-label" for="{{$item->getLinkId()}}">Yes</label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check">
            <input class="form-check-input i_{{$item->getLinkId()}}" type="radio" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value="false" {{ ($item->getreadOnly() == "true") ? "disabled" : "" }}>
            <label class="form-check-label" for="{{$item->getLinkId()}}">No</label>
        </div>
    </div> 
    <div class="col-sm-4">
        <button type="button" id="clearRadio" class="btn btn-secondary btn-sm custom-transparent-button" data-toggle="tooltip" title="Set question as unanswered">Clear</button>
    </div>
</div>


<script>
    document.getElementById('clearRadio').addEventListener('click', function() {
        document.querySelectorAll('input[type="radio"][name="{{$item->getLinkId()}}"]').forEach(function(radio) {
            radio.checked = false;
        });
    });
</script>

<style>
    .custom-transparent-button {
        background-color: rgba(0, 0, 0, 0.04); /* Cor de fundo preta com 10% de opacidade */
        color: #000; /* Define a cor do texto do botão */
        border-color: transparent; /* Faz a borda ser transparente */
    }
    .custom-transparent-button:hover {
        background-color: #6c757d; /* Muda a cor de fundo para cinza no hover */
        border-color: #6c757d; /* Muda a cor da borda para cinza no hover */
    }
</style>