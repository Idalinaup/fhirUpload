@php
    use Illuminate\Support\Facades\Log;
    $enableBehavior = $itemChild->getEnableBehavior();
    
    $question = $enableWhen->getQuestion();

    $question =  str_replace(".", "\\\\.", $question);

    $operator = $enableWhen->getOperator()->getValue();
    if ($operator == "="){
        $operator = "==";
    }
    
    $answerBoolean = $enableWhen->getAnswerBoolean();
    $answerCoding = $enableWhen->getAnswerCoding() ? $enableWhen->getAnswerCoding()->getCode() : null;
    $answerDate = $enableWhen->getAnswerDate();
    $answerDateTime = $enableWhen->getAnswerDateTime();
    $answerDecimal = $enableWhen->getAnswerDecimal();
    $answerInteger = $enableWhen->getAnswerInteger();
    $answerQuantity = $enableWhen->getAnswerQuantity();
    $answerReference = $enableWhen->getAnswerReference();
    $answerString = $enableWhen->getAnswerString();
    $answerTime = $enableWhen->getAnswerTime();
    
    $answer = $answerBoolean ?? $answerCoding ?? $answerDate ?? $answerDateTime ?? $answerDecimal ?? $answerInteger ?? $answerQuantity ?? $answerReference ?? $answerString ?? $answerTime;

    $enableWhenConditions = $itemChild->getEnableWhen();
    $enableWhenCount = count($enableWhenConditions);

    $isEnabled = false;

    if ($enableBehavior === 'all') {
        $isEnabled = collect($enableWhen)->every(fn($item) => $item['value']);
    } elseif ($enableBehavior === 'any') {
        $isEnabled = collect($enableWhen)->contains(fn($item) => $item['value']);
    }
    
    
@endphp


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@section('form_script')
<script>
$(document).ready(function(){
    $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').hide();
    console.log("{{ $operator }}");
        $( ".i_{{ $question}}" ).on( "change", function() {
            console.log("{{ $question }} changed");
            var value = $(this).val(); // Get the value of the changed element
            console.log("Value: " + value);

            if (value.includes('code:')) {
                const pattern = /code:([^ ]+)/;
                const code = value.match(pattern)[1];
                value = code;
            }

            @if($operator == "exists")
                    if (value && value.trim() !== "") { // Check if the value exists and is not empty
                        $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').show();
                    } else {
                        $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').hide();
                    }
            @else
                if (eval("'" + value + "'" + '{{$operator}}' + "'" + '{{ $answer }}' + "'")) {
                    $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').show();
                    console.log("Show: ");
                } else {
                    $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').hide();
                    console.log("Hide: ");
                }
            @endif
        });
});


</script>


@append