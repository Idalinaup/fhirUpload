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

$answerOptions = $item->getAnswerOption(); 
$valueSet = $item->getAnswerValueSet() ?? null;;// Supondo que getAnswerOption() retorna um array

$answerArrays = []; // Array para armazenar todas as opções de resposta

foreach ($answerOptions as $answerOption) {
    if ($answerOption->getValueCoding() != null) {
        $answerArray = [
            'code' => $answerOption->getValueCoding()->getCode(),
            'system' => $answerOption->getValueCoding()->getSystem(),
            'display' => $answerOption->getValueCoding()->getDisplay()
        ];
    }
}
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
    @if(!empty($answerOptions))
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
                <option value="{{ json_encode($answerOption->getValueCoding()) }}" {{ $answerOption->getInitialSelected() ? 'selected' : '' }}>
                    @if($answerOption->getValueCoding()->getDisplay() != null)
                        {{ $answerOption->getValueCoding()->getDisplay() }}
                    @else
                        {{ $answerOption->getValueCoding()->getCode() }}
                    @endif
                </option>
            @endif
        @endforeach
        </select>

    @elseif(!empty($valueSet))
        <div class="position-relative mt-2">
    
    <input type="text"
       class="form-control autocomplete-input"
       id="{{ $item->getLinkId() }}_autocomplete"
       placeholder="Digite pelo menos 3 caracteres..."
       data-valueset='{{ $item->getAnswerValueSet() }}'
       autocomplete="off">


    <!-- Hidden field to store selected JSON -->
    <input type="hidden"
           name="{{$item->getLinkId()}}"
           id="{{$item->getLinkId()}}_hidden">

    <!-- Dropdown -->
    <ul class="list-group position-absolute w-100 d-none autocomplete-list"
        style="z-index:1000;"></ul>
    </div> 
    @endif
@endif

<script>
document.querySelectorAll(".autocomplete-input").forEach(input => {

    if (input.dataset.listenerAttached) return;
    input.dataset.listenerAttached = "true";

    const THRESHOLD = 3;
    let valueSet = input.dataset.valueset;
    let dropdown = input.parentElement.querySelector(".autocomplete-list");
    let hiddenInput = input.parentElement.querySelector("input[type='hidden']");

    input.addEventListener("input", function () {
        let query = input.value.trim();

        if (query.length < THRESHOLD) {
            dropdown.classList.add("d-none");
            dropdown.innerHTML = "";
            return;
        }

        dropdown.innerHTML = `<li class="list-group-item disabled">Loading…</li>`;
        dropdown.classList.remove("d-none");

        let url = (valueSet === "http://hl7.org/fhir/ValueSet/administrative-gender" ||
                   valueSet === "http://hl7.org/fhir/ValueSet/icd-10-procedures")
            ? `/valueset/expand?url=${valueSet}`
            : `/valueset/expand?url=${valueSet}&filter=${encodeURIComponent(query)}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                let filtered = data.expansion?.contains || [];
                dropdown.innerHTML = "";

                 if (!data.expansion || !Array.isArray(data.expansion.contains)) {
            throw new Error("ValueSet expansion is missing or invalid in the database/API");
        }

                if (filtered.length === 0) {
                    dropdown.classList.add("d-none");
                    return;
                }

                filtered.slice(0, 40).forEach(item => {
                    let li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action";
                    li.textContent = `${item.display} (${item.code})`;

                    li.addEventListener("click", function (ev) {
                        ev.stopPropagation();
                        input.value = item.display;
                        hiddenInput.value = JSON.stringify({
                            code: item.code,
                            system: item.system,
                            display: item.display
                        });
                        dropdown.classList.add("d-none");
                        input.blur(); // evita reabrir
                    });

                    dropdown.appendChild(li);
                });

                dropdown.classList.remove("d-none");
            })
            .catch(err => {
                dropdown.innerHTML = `<li class="list-group-item text-danger">Erro ao carregar dados. Tente novamente.</li>`;
                dropdown.classList.remove("d-none");
                setTimeout(() => dropdown.classList.add("d-none"), 4000);
            });
    });
});

// esconder dropdown se clica
document.addEventListener("click", function (ev) {
    document.querySelectorAll(".autocomplete-input").forEach(input => {
        let dropdown = input.parentElement.querySelector(".autocomplete-list");
        if (!input.parentElement.contains(ev.target)) {
            dropdown.classList.add("d-none");
        }
    });
});
</script>


<style>
.autocomplete-list {
    max-height: 200px;       /* max height of dropdown */
    overflow-y: auto;        /* vertical scroll if too many items */
    padding: 0;
    margin: 0;
    list-style: none;
    border: 1px solid #ced4da; /* Bootstrap-like border */
    border-radius: 0.25rem;
    background: #fff;
    position: absolute;
    width: 100%;             /* match input width */
    z-index: 1000;           /* appear above other elements */
}
</style>

