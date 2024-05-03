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
            'file' => 'required|mimes:json,xml|max:2048',
        ]);

        $file = $request->file('file');
        $file->storeAs('artifacts', $file->getClientOriginalName());

        return Redirect::route('artifacts.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function generateForm(Request $request) {
        $selectedArtifact = $request->input('selectedArtifact');
        $artifactsPath = storage_path('app');
        $selectedArtifactName = is_array($selectedArtifact) ? $selectedArtifact[0] : $selectedArtifact;
        $filePath = $artifactsPath . '/' . $selectedArtifactName;
        $content = file_get_contents($filePath);
    
        // File content handling
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    
            if ($extension === 'json') {
                $decodedContent = json_decode($content, true);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $decodedContent]);
            } elseif ($extension === 'xml') {
                $xml = simplexml_load_string($content);
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => $xml]);
            } else {
                return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Tipo de arquivo não suportado']);
            }
        } else {
            return response()->json(['selectedArtifactName' => $selectedArtifactName, 'fileContent' => 'Arquivo não encontrado']);
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
             'registerAutoloader' => true, // use if you are not using Composer
             'sxeArgs' => LIBXML_COMPACT | LIBXML_NSCLEAN // choose different libxml arguments if you want, ymmv.
         ]);

        $parser = new PHPFHIRResponseParser($config);

        $content = file_get_contents($filePath);

        $objectQuestionnaire = $parser->parse($content);

        return view('artifacts.FHIRQuestionnaireViewer', compact('objectQuestionnaire'));
    }



    public function FHIRQuestionnaireResponse(Request $request){
        // Get input parameters
        //$statusValue = $request->input('status');

        $id = $request->input('Id');
        $type = $request->input('typeQuestionnaire');
        $answers = $request->all();

        //Log::debug($type);

        //dd($type);

        $selectedArtifact = $request->input('selectedArtifact');

        //$selectedArtifact = $request->all();

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

            foreach ($answers as $linkId => $answer) {

                $item = new FHIRQuestionnaireResponseItem();

                $item->setLinkId(new FHIRString((string) $linkId)); 

                $item->setText(new FHIRString(("Text"))); 

                $item->setDefinition(new FHIRUri(("Uri"))); 

                //Log::debug($linkId);

                //Log::debug($answer);


                    $answerItem = new FHIRQuestionnaireResponseAnswer();

                    $answerType = gettype($answer);
                    

                    switch ($answerType) {
                        case 'boolean':
                            $booleanValue = ($answer === 'true');
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
                            $answerItem->setValueAttachment(new FHIRAttachment($answer));
                            break;
                        case 'reference':
                            $answerItem->setValueReference(new FHIRReference($answer));
                            break;
                        case 'quantity':
                            $answerItem->setValueQuantity(new FHIRQuantity($answer));
                            break;
                        case 'coding':
                            $answerItem->setValueCoding(new FHIRCoding($answer));
                            break;
                    }

                $item->addAnswer($answerItem);

                $response->addItem($item);

            }
        return response()->json($response);
    }
}