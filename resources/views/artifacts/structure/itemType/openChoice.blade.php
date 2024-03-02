<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<select name="additionalInvestigatorRole" id="additionalInvestigatorRole" style="width: 100%;">
    <option value="" disabled selected>Select an option or type a value</option>
    @foreach($item->getAnswerOption() as $answerOption)
        <option value="{{ $answerOption->getValueCoding()->getCode() }}">
            {{ $answerOption->getValueCoding()->getDisplay() }}
        </option>
    @endforeach
</select>

<script>
    $(document).ready(function() {
        $('#additionalInvestigatorRole').select2({
            tags: true
        });
    });
</script>