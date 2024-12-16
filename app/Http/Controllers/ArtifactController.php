<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParserConfig;
use DCarbone\PHPFHIRGenerated\R4\PHPFHIRResponseParser;

use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRQuestionnaire;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRQuestionnaireResponse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRQuestionnaireResponse\FHIRQuestionnaireResponseItem;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRQuestionnaireResponse\FHIRQuestionnaireResponseAnswer;


use DCarbone\PHPFHIRGenerated\R4\FHIRElement;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAttachment;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBoolean;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRInteger;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDecimal;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDate;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRTime;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRQuantity;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRChoice;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIROpenChoice;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRText;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCanonical;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifier;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRNarrative;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDateTime;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRQuestionnaireResponseStatus;
use Illuminate\Support\Facades\Log;

class ArtifactController extends Controller
{
    public function index()
    {
        $artifacts = Storage::files('artifacts');

        return view('artifacts.index', compact('artifacts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            ['file' => 'required|mimes:application/json']
        ]);

        $file = $request->file('file');
        $file->storeAs('artifacts', $file->getClientOriginalName());
        //log::debug($file);
        return Redirect::route('artifacts.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function generateForm(Request $request) {
        $selectedArtifact = $request->input('selectedArtifact');
        //log::debug($selectedArtifact);
        $artifactsPath = storage_path('app');
        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;
        $content = file_get_contents($filePath);
    
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            if ($extension === 'json') {
                $decodedContent = json_decode($content, true);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $decodedContent]);
            } else {
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Tipo de arquivo não suportado']);
            }
        } else {
            return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Arquivo não encontrado']);
        }
    }

    public function parseFHIRQuestionnaire(Request $request){
        $selectedArtifact = $request->input('selectedArtifact');
        $artifactsPath = storage_path('app');

        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;

        $config = new PHPFHIRResponseParserConfig([
             'sxeArgs' => LIBXML_COMPACT | LIBXML_NSCLEAN // choose different libxml arguments if you want, ymmv.
        ]);

        $parser = new PHPFHIRResponseParser($config);


        $content = file_get_contents($filePath);
        //log::debug($content);

        $objectQuestionnaire = $parser->parse($content);


        //log::debug($objectQuestionnaire);

        return view('artifacts.FHIRQuestionnaireViewer', compact('objectQuestionnaire' , 'selectedArtifactName'));
    }

    public function FHIRQuestionnaireResponseItemsLoop($objectQuestionnaire, $response, $answers ){
	
        foreach ($objectQuestionnaire->getItem() as $itemQuestionnaire) {
  
            $type = $itemQuestionnaire->getType()->getValue()->getValue();
  
            if ($type == 'group') {

                $linkId = $itemQuestionnaire->getLinkId()->getValue()->getValue();
                  //$response-> kk coisa para por o grupo na response
                $itemGroup = new FHIRQuestionnaireResponseItem();
  
                $itemGroup->setLinkId(new FHIRString((string) $linkId)); 

                $itemGroup->setText ($itemQuestionnaire->gettext()); 
                   
                $itemGroup = $this->FHIRQuestionnaireResponseItemsLoop($itemQuestionnaire, $itemGroup, $answers );
                  
                $response->addItem($itemGroup);
                  
            }else{

                $linkId = $itemQuestionnaire->getLinkId()->getValue()->getValue();

                if(isset($answers[str_replace(".", "_", $linkId)])){
                      $answer = $answers[str_replace(".", "_", $linkId)];
                }

                else{
                      //dd(str_replace("\.", "_", $linkId));
                    continue;
                }
                  
                $response->addItem($this->FHIRQuestionnaireResponseItem($itemQuestionnaire, $answer));
            }
        }
  
        return $response;
    }

    function evaluateFHIRPath($resource, $expression) {
        // Encode the resource as a JSON string
        $resourceJson = json_encode($resource);

        // Escape the JSON string for the command line
        $escapedResourceJson = escapeshellarg($resourceJson);
        $escapedExpression = escapeshellarg($expression);

        // Call the Node.js script
        $command = "node C:\Users\Idalina\Desktop\fhirUpload\node-scripts\evaluateFHIRPath.js $escapedResourceJson $escapedExpression";
        $output = shell_exec($command);

        // Decode the JSON result
        log($output);
        return json_decode($output, true);
    }

    public function FHIRQuestionnaireResponse(Request $request){

        $id = $request->input('Id');
        //$type = $request->input('type');
        $answers = $request->all();

        $selectedArtifact = $request->input('selectedArtifact');

        $artifactsPath = storage_path('app');

        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;

        $config = new PHPFHIRResponseParserConfig([
             'sxeArgs' => LIBXML_COMPACT | LIBXML_NSCLEAN // choose different libxml arguments if you want, ymmv.
         ]);

        $parser = new PHPFHIRResponseParser($config);

        $content = file_get_contents($filePath);

        $objectQuestionnaire = $parser->parse($content);

        $response = new FHIRQuestionnaire();
    
        // Create a new QuestionnaireResponse instance
        $response = new FHIRQuestionnaireResponse();
    
        // Set status
        //$status = new FHIRQuestionnaireResponseStatus(['value' => $statusValue]);
        //$response->setStatus($status);
    
        // Set identifier
        $identifier = new FHIRIdentifier();
        $identifier->setValue($id); // Example value for identifier
        $response->setIdentifier($identifier);

        $basedOn = new FHIRReference(['reference' => 'Reference(CarePlan|ServiceRequest)']);
        $response->setBasedOn([$basedOn]); // Since basedOn is an array

        $partOf = new FHIRReference(['reference' => 'Reference(Observation|Procedure)']);
        $response->setPartOf([$partOf]); // Since partOf is an array
    
        // Set questionnaire
        $response->setQuestionnaire(new FHIRCanonical(['value' => 'http://hl7.org/fhir/Questionnaire/gcs']));
    
    
        // Set source
        $source = new FHIRReference();
        $source->setReference(new FHIRString('Practitioner/f007'));
        $response->setSource($source);


        // Subject
        $subject = new FHIRReference(['reference' => 'Reference(Any)']);
        $response->setSubject($subject);

        // Encounter
        $encounter = new FHIRReference(['reference' => 'Reference(Encounter)']);
        $response->setEncounter($encounter);

        // Authored
        date_default_timezone_set('Europe/Lisbon');

        $authored = new FHIRDateTime(['value' => date('c')]); 
        $response->setAuthored($authored);

        // Author
        $author = new FHIRReference(['reference' => 'Reference(Device|Organization|Patient|Practitioner|PractitionerRole|RelatedPerson)']);
        $response->setAuthor($author);

        // Source
        $source = new FHIRReference(['reference' => 'Reference(Device|Organization|Patient|Practitioner|PractitionerRole|RelatedPerson)']);
        $response->setSource($source);

		$response = $this->FHIRQuestionnaireResponseItemsLoop($objectQuestionnaire, $response, $answers);

        return response()->json($response);
    }


    public function FHIRQuestionnaireResponseItem($itemQuestionnaire, $answer)
    {
        $linkId = $itemQuestionnaire->getLinkId()->getValue()->getValue();
        $answerType = $itemQuestionnaire->getType()->getValue()->getValue();
    
        $item = new FHIRQuestionnaireResponseItem();
        $item->setLinkId(new FHIRString((string) $linkId));
        $item->setText($itemQuestionnaire->getText());
    
        $answerItems = $this->processAnswers($answerType, $answer);
    
        foreach ($answerItems as $answerItem) {
            $item->addAnswer($answerItem);
        }
    
        return $item;
    }
    
    private function processAnswers($answerType, $answer)
    {
        $answerItems = [];
    
        if (is_array($answer)) {
            foreach ($answer as $singleAnswer) {
                $answerItems[] = $this->createAnswerItem($answerType, $singleAnswer);
            }
        } else {
            $answerItems[] = $this->createAnswerItem($answerType, $answer);
        }
    
        return $answerItems;
    }
    
    private function createAnswerItem($answerType, $answer)
    {
        $answerItem = new FHIRQuestionnaireResponseAnswer();
    
        switch ($answerType) {
            case 'boolean':
                $answerItem->setValueBoolean(new FHIRBoolean($answer));
                break;
            case 'decimal':
                $answerItem->setValueDecimal(new FHIRDecimal($answer));
                break;
            case 'integer':
                $answerItem->setValueInteger(new FHIRInteger($answer));
                break;
            case 'date':
                $answerItem->setValueDate(new FHIRDate($answer));
                break;
            case 'dateTime':
                $answerItem->setValueDateTime(new FHIRDateTime($answer));
                break;
            case 'time':
                $answerItem->setValueTime(new FHIRTime($answer));
                break;
            case 'string':
                $answerItem->setValueString(new FHIRString($answer));
                break;
            case 'text':
                $answerItem->setValueString(new FHIRString($answer));
                break;
            case 'url':
                $answerItem->setValueUri(new FHIRUri($answer));
                break;
            case 'attachment':
                $attachmentArray = $this->processAttachment($answer);
                $answerItem->setValueAttachment(new FHIRAttachment($attachmentArray));
                break;
            case 'reference':
                $answerItem->setValueReference(new FHIRReference($answer));
                break;
            case 'quantity':
                $answerData = [
                    'value' => (is_array($answer) ? $answer[0] : $answer),
                ];
                $answerItem->setValueQuantity(new FHIRQuantity($answerData));
                break;
                case 'coding':
                    log::debug("coding:", $answer);
                    $answerItem->setValueCoding(new FHIRCoding($answer));
                    break;

                case 'choice':
                    $decodedAnswer = json_decode($answer, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $answerItem->setValueCoding(new FHIRCoding($decodedAnswer));
                        log::debug($answerItem);
                    } else {
                        log::error("JSON decode error: " . json_last_error_msg());
                    }
                break;

        }
    
        return $answerItem;
    }
    
    private function processAttachment($answer)
    {
        return [
            'contentType' => $answer['contentType'] ?? null,
            'language' => $answer['language'] ?? null,
            'data' => $answer['data'] ?? null,
            'url' => $answer['url'] ?? null,
            'size' => $answer['size'] ?? null,
            'hash' => $answer['hash'] ?? null,
            'title' => $answer['title'] ?? null,
            'creation' => $answer['creation'] ?? null,
        ];
    }
    
}