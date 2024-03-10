@if($item->getRepeats() == "true")
    @foreach($item->getAnswerOption() as $answerOption)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="answerOption[]" value="{{ $answerOption->getValueCoding()->getCode() }}">
        <label class="form-check-label">
            {{ $answerOption->getValueCoding()->getDisplay() }}
        </label>
    </div>
    @endforeach


@else
    <select name="answerOption">
        <option value="" disabled selected>Select an option</option>
        @foreach($item->getAnswerOption() as $answerOption)
            <option value="{{ $answerOption->getValueCoding()->getCode() }}">
                {{ $answerOption->getValueCoding()->getDisplay() }}
            </option>
        @endforeach
    </select>
    
@endif
       

