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
        log::debug($file);
        return Redirect::route('artifacts.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function generateForm(Request $request) {
        $selectedArtifact = $request->input('selectedArtifact');
        log::debug($selectedArtifact);
        $artifactsPath = storage_path('app');
        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;
        $content = file_get_contents($filePath);
    
        if (file_exists($filePath)) {
            log::debug("idalina");
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    
            if ($extension === 'json') {
                $decodedContent = json_decode($content, true);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $decodedContent]);
            } else {
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Tipo de arquivo nÃ£o suportado']);
            }
        } else {
            log::debug("Joao");
            return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Arquivo nÃ£o encontrado']);
        }
    }

    public function parseFHIRQuestionnaire(Request $request){
        $selectedArtifact = $request->input('selectedArtifact');

        //$selectedArtifact = $request->all();

        Log::debug($selectedArtifact);
        $artifactsPath = storage_path('app');

        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;

        $config = new PHPFHIRResponseParserConfig([
             'sxeArgs' => LIBXML_COMPACT | LIBXML_NSCLEAN // choose different libxml arguments if you want, ymmv.
        ]);

        $parser = new PHPFHIRResponseParser($config);

        $content = file_get_contents($filePath);

        $objectQuestionnaire = $parser->parse($content);

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
	
	public function FHIRQuestionnaireResponseItem($itemQuestionnaire, $answer){
      
        $linkId = $itemQuestionnaire->getLinkId()->getValue()->getValue();
        $answerType = $itemQuestionnaire->getType()->getValue()->getValue();

        $item = new FHIRQuestionnaireResponseItem();

        $item->setLinkId(new FHIRString((string) $linkId)); 
                
        $item->setText ($itemQuestionnaire->gettext()); 

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
                case 'url':
                    $answerItem->setValueUri(new FHIRUri($answer));
                    break;
                case 'attachment':
                    $contentType = trim(substr($answer, $contentTypeStart = strpos($answer, 'contentType:') + strlen('contentType:'), strpos($answer, 'language:') - $contentTypeStart));
                        $language = trim(substr($answer, $languageStart = strpos($answer, 'language:') + strlen('language:'), strpos($answer, 'data:') - $languageStart));
                        $data = trim(substr($answer, $dataStart = strpos($answer, 'data:') + strlen('data:'), strpos($answer, 'url:') - $dataStart));
                        $url = trim(substr($answer, $urlStart = strpos($answer, 'url:') + strlen('url:'), strpos($answer, 'size:') - $urlStart));
                        $size = trim(substr($answer, $sizeStart = strpos($answer, 'size:') + strlen('size:'), strpos($answer, 'hash:') - $sizeStart));
                        $hash = trim(substr($answer, $hashStart = strpos($answer, 'hash:') + strlen('hash:'), strpos($answer, 'title:') - $hashStart));
                        $title = trim(substr($answer, $titleStart = strpos($answer, 'title:') + strlen('title:'), strpos($answer, 'creation:') - $titleStart));
                        $creation = trim(substr($answer, $creationStart = strpos($answer, 'creation:') + strlen('creation:')));
                    
                    $attachmentArray = [
                        'contentType' => $answer['contentType'] ?? null, // Replace null with default or calculated value if necessary
                        'language' => $answer['language'] ?? null,
                        'data' => $answer['data'] ?? null,
                        'url' => $answer['url'] ?? null,
                        'size' => $answer['size'] ?? null,
                        'hash' => $answer['hash'] ?? null,
                        'title' => $answer['title'] ?? null,
                        'creation' => $answer['creation'] ?? null,
                    ];
                
                    // Assuming $answer is structured in a way that these fields can be directly accessed
                    // If $answer does not directly contain these fields, you may need to extract or calculate them differently
                
                    $answerItem->setValueAttachment(new FHIRAttachment($attachmentArray));;

                    $answerItem->setValueAttachment(new FHIRAttachment($answer));
                    break;
                case 'reference':
                    $answerItem->setValueReference(new FHIRReference($answer));
                    break;
                case 'quantity':
                    //dd($itemQuestionnaire);
                    $answerData = [
                        'value' => (is_array($answer)?$answer[0]:$answer), // assuming $answer is a numeric value
                        //'unit' => (is_array($answer)?$answer[1]:""), 
                        //'system' => (is_array($answer)?$answer[2]:""),
                       // 'code' => (is_array($answer)?$answer[3]:""),
                        
                    ];
                    $answerItem->setValueQuantity(new FHIRQuantity($answerData));
                    break;
                case 'coding':
                    log::debug("coding:", $answer);
                    $answerItem->setValueCoding(new FHIRCoding($answer));
                    break;
                case 'choice':
                    log::debug( $answer); 

                    $code = trim(substr($answer, $codeStart = strpos($answer, 'code:') + strlen('code:'), strpos($answer, 'display:') - $codeStart));
                    $display = trim(substr($answer, $displayStart = strpos($answer, 'display:') + strlen('display:'), strpos($answer, 'system:') - $displayStart));
                    $system = trim(substr($answer, $systemStart = strpos($answer, 'system:') + strlen('system:')));

                    $answerData = [
                        'value' =>(is_array($answer)?$answer[0]:$answer),  // assuming $answer is a numeric value
                        'system' => $system ? $system : null,
                        'code' => $code ? $code : null,
                        'display' => $display ? $display : null,
                    ];
                    $answerItem->setValueCoding(new FHIRCoding($answerData));
                    break;
            }

        $item->addAnswer($answerItem);
    
    return $item;
    }
		
}