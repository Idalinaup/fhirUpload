<select name="answerOption">
    <option value="" disabled selected>Select an option</option>
    @foreach($item->getAnswerOption() as $answerOption)
        <option value="{{ $answerOption->getValueCoding()->getCode() }}">
            {{ $answerOption->getValueCoding()->getDisplay() }}
        </option>
    @endforeach
</select>