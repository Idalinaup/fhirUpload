@php
    $question = $enableWhen->getQuestion();

    $question =  str_replace(".", "\\\\.", $question);

    $operator = $enableWhen->getOperator()->getValue();
    if ($operator == "="){
        $operator = "==";
    }
    
    $answerBoolean = $enableWhen->getAnswerBoolean();
    $answerCoding = $enableWhen->getAnswerCoding() ? $enableWhen->getAnswerCoding()->getCode() : null;;
    $answerDate = $enableWhen->getAnswerDate();
    $answerDateTime = $enableWhen->getAnswerDateTime();
    $answerDecimal = $enableWhen->getAnswerDecimal();
    $answerInteger = $enableWhen->getAnswerInteger();
    $answerQuantity = $enableWhen->getAnswerQuantity();
    $answerReference = $enableWhen->getAnswerReference();
    $answerString = $enableWhen->getAnswerString();
    $answerTime = $enableWhen->getAnswerTime();
    $answer = $answerBoolean ?? $answerCoding ?? $answerDate ?? $answerDateTime ?? $answerDecimal ?? $answerInteger ?? $answerQuantity ?? $answerReference ?? $answerString ?? $answerTime;
@endphp


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@section('form_script')
<script>
$(document).ready(function(){
    $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').hide();
    console.log("{{ $question }}");

    if ($("#{{ $question }}").length) {
        console.log("Element exists");
    } else {
        console.log("Element not found");
    }

    // Bind click event to check if it works
    $("#{{ $question }}").click(function() {
        console.log("Clicked");
    });


    $( "#{{ $question}}" ).on( "change", function() {
        console.log("mudou");
        var value = $(this).val(); // Get the value of the changed element
        console.log(value);
        // Assuming $operator is a string representing a comparison operator like "==", "<=", etc.
        if (eval("'" + value + "'" + '{{$operator}}' + "'" + '{{ $answer }}' + "'")) {
            alert("enable");
            $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').show();
        }
    });
});
</script>
@append