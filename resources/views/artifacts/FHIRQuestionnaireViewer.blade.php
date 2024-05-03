@php
    //dd($objectQuestionnaire->getItem());
    // Supondo que $questionnaire seja uma instância de Questionnaire e $answers seja um array de respostas
    use Illuminate\Support\Facades\Log;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <form action="{{ route('artifacts.response') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Id" id="Id" value="{{ $objectQuestionnaire->getId() }}">
        <input type="hidden" name="objectQuestionnaire" id="objectQuestionnaire" value="{{ $objectQuestionnaire}}">
        <input type="hidden" name="status" id="status" value="{{ $objectQuestionnaire->getstatus() }}">
        <br>
        <div class="questionnaire-info">
            <p>
                The following Questionnaire was loaded from:  {{$objectQuestionnaire->getId()}}
            </p>
        </div>

        <div>
            @foreach($objectQuestionnaire->getItem() as $item)

                <div class="questionnaire-item">
                    @include('artifacts.structure.item')
                </div>
                @php
                    $typeQuestionnaire = $item->getType();
                    $typeQuestionnaireArray[] = $typeQuestionnaire;
                @endphp
            @endforeach
            @php
                //Log::debug(json_encode($typeQuestionnaireArray));
            @endphp
        </div>

        @yield('form_script')
        <br>  
        @csrf
        <button id="submit-button" type="submit" class="btn btn-success" >SUBMIT</button>
    </form>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#submit-button').click(function() {
            $.ajax({
                url: '/artifacts/generate/FHIRQuestionnaireResponse',
                method: 'POST',
                data: {
                    selectedArtifact: $('#selectedArtifact').val(), 
                 },
                 success: function(data) {
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<script> 
    if ( $item->getRequired() === "true" && $item->getLinkId() == null) {
        document.getElementById('submit-button').disabled = false;
    } else {
        console.log("Item is not required", itemRequired);
        console.log("Item", itemRequired);
        document.getElementById('submit-button').disabled = true;
    }
</script>