@php
    $question = $enableWhen->getQuestion();
    $operator = $enableWhen->getOperator()->getValue();
   // $answer = $enableWhen->getAnswerCoding()->getCode();
@endphp

<p class="red-text">

Question: {{ $question }}
Operator: {{ $operator }}

</p>

<style>
    .red-text {
        color: red;
    }
</style>