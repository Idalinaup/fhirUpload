@php
    $enableBehavior = $itemChild->getEnableBehavior();
    
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

    @if($operator == "exists")
        $( "#{{ $question}}" ).on( "change", function() {
            var value = $(this).val(); // Get the value of the changed element

            if (value) { // This will be true if value is not null, undefined, or an empty string
                $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').show();
            }
        });
    @else
        $( "#{{ $question}}" ).on( "change", function() {
            console.log("{{ $question }} changed");
            var value = $(this).val(); // Get the value of the changed element
            console.log("Value: " + value);
            
            if (eval("'" + value + "'" + '{{$operator}}' + "'" + '{{ $answer }}' + "'")) {
                $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').show();
            } else {
                $('*[data-linkid="{{ $itemChild->getLinkId() }}"]').hide();
            }
        });
    @endif
});
</script>
@append