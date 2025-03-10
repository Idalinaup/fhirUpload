<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="asset-path" content="{{ asset('') }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

@php
use Illuminate\Support\Facades\Log;
$extensionCount = 0;

if($objectQuestionnaire == "Questionnaire")
{
    function checkExtensions($items) {
        $count = 0;
        foreach ($items as $item) {
            if ($item->getExtension()) {
                $count++;
            }
            if ($item->getItem()) {
                $count += checkExtensions($item->getItem());
            }
        }
        return $count;
    }

    $extensionCount = checkExtensions($objectQuestionnaire->getItem());
}
@endphp

<body>
    <form action="{{ route('artifacts.response') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Id" id="Id" value="{{ $objectQuestionnaire->getId() }}">
        <input type="hidden" name="objectQuestionnaire" id="objectQuestionnaire" value="{{ $objectQuestionnaire}}">
        <input type="hidden" name="status" id="status" value="{{ $objectQuestionnaire->getstatus() }}">
        <input type="hidden" name="selectedArtifact" id="selectedArtifact" value="{{$selectedArtifactName}}">
<br>

<div class="questionnaire-info row">
    <div class="col-11">
        @if($objectQuestionnaire != "Questionnaire")
        <p>
            <span style="position: relative; display: inline-block; font-size: 17px;">
                <svg style="color:rgb(255, 19, 11); vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" 
                width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 
                    1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 
                    0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
                O recurso carregado não é um Questionnaire, mas sim um: {{$objectQuestionnaire}}.
            </span>
        </p>
        @else
        <p>
            The following Questionnaire was loaded from: {{$objectQuestionnaire->getId()}}.
        <p>
        </p>
            The status of the Questionnaire is:
            <span class="badge bg-success" title="This resource is ready for normal use.">
                {{$objectQuestionnaire->getstatus()->getValue()}}
            </span>
        </p>
    </div>

    @if($extensionCount > 0)
        <div class="col-md-1 text-right" >
            <button  class="lf-help-button btn btn-sm" style="color:rgb(255, 19, 11)" >
                <span class="tooltiptextp">
                    Attention: There may be an extension that has not yet been implemented</span> 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 
                    1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 
                    0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
            </button>
        </div>
    @endif
</div>

<div>
    @foreach($objectQuestionnaire->getItem() as $item)
        <div class="questionnaire-item">
            @include('artifacts.structure.item')
        </div>            
    @endforeach
</div>

@yield('form_script')
<br>  
@csrf
<button id="submit-button" type="submit" class="btn btn-success">SUBMIT</button>
    </form>
</body>
@endif

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


<style>
.tooltiptextp {
    width: 120px;
  bottom: 120%;
  left: 80%;
  margin-left: -150px; 
  visibility: hidden;
  width: 210px;
  background-color: rgba(255, 58, 58, 0.5);
  color: #000000;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

.transparent-badge {
    opacity: 0.1;
}

.lf-help-button:hover .tooltiptextp {
    visibility: visible;
}
</style>