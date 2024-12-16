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
        $initialString = $initial->getValueString();
        $initialTime = $initial->getValueTime();
        
        $initialValue = $initialBoolean ?? $initialCoding ?? $initialDate ?? $initialDateTime ?? $initialDecimal ?? $initialInteger ?? $initialQuantity ?? $initialReference ?? $initialString ?? $initialTime;
}

@endphp

@if($item->getExtension())
    @foreach($item->getExtension() as $extension)
        @if($extension->getUrl() == 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-calculatedExpression' )
            @php
            $expression = $extension->getValueExpression()->getExpression();
            //Log::debug($expression);
            @endphp

            <div class="form-group">
                <input type="number" 
                    class="form-control i_{{$item->getLinkId()}}" 
                    placeholder="{{$expression}}"
                    name="{{ $item->getLinkId() }}" 
                    id="{{ $item->getLinkId() }}" 
                    value="{{ $initialValue }}" 
                    maxlength="{{ $item->getMaxLength() }}" 
                    {{ $item->getReadOnly() == "true" ? 'disabled' : '' }}>
            </div>
        @endif
    @endforeach
@else
<div class="form-group">
    <input type="number" 
        class="form-control i_{{$item->getLinkId()}}" 
        placeholder="Enter number here" 
        name="{{ $item->getLinkId() }}" 
        id="{{ $item->getLinkId() }}" 
        value="{{ $initialValue }}" 
        maxlength="{{ $item->getMaxLength() }}" 
        {{ $item->getReadOnly() == "true" ? 'disabled' : '' }}>
    </div>
@endif