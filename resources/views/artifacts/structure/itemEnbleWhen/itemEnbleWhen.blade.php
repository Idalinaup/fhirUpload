@php

@endphp

<p>
<h1>

Question: {{ $enableWhen->getQuestion() }}
Operator: {{ $enableWhen->getOperator()->getValue() }}
Answer: {{ $enableWhen->getAnswerBoolean() }}

</h1>
</p>
