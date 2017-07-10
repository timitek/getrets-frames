<?php

namespace App\Http\Controllers\Api;

use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\StatusCodes;

class ApiController extends Controller
{
    protected $request;

    private $statusCode = StatusCodes::HTTP_OK;
    public function getStatusCode() { return $this->statusCode; }
    public function setStatusCode($value) { $this->statusCode = $value; return $this; }


    /**
     * Create a new ApiController reference
     * 
     * @param Request $request The request object is injected for use in 
     * determining constraints of the request, such as the format the be used 
     * for the response
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Submit the response and all meta data back to the client
     * 
     * @param type $data
     * @param type $headers
     * @return type
     */
    public function respond($data, $headers = []) {

        return Response::json(
            $data, $this->getStatusCode(), $headers
        );
    }

    /**
     * A default wrapper for a common response where the client is expecting to 
     * unfold the data from a member named "data".
     * 
     * @param type $data
     * @return type
     */
    public function respondData($data) {
        return $this->respond([
                    'data' => $data
        ]);
    }

    /**
     * Respond to the client with an error containing a specific message and 
     * HTTP response code
     * 
     * @param type $message Custom error message to provide
     * @return type
     */
    public function respondWithError($message) {
        return $this->respond([
                    'error' => [
                        'message' => $message,
                        'statusCode' => $this->getStatusCode()
                    ]
        ]);
    }

    /**
     * Respond with a 422 status code (Unprocessable entity).
     * 
     * @param type $message Custom error message to provide
     * @return type
     */
    public function respondUnprocessable($message = 'Unprocessable request!') {
        return $this->setStatusCode(StatusCodes::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * Respond with a 403 status code (Forbidden).
     * 
     * @param type $message Custom error message to provide
     * @return type
     */
    public function respondUnauthorized($message = 'Not Authorized!') {
        return $this->setStatusCode(StatusCodes::HTTP_FORBIDDEN)->respondWithError($message);
    }

    /**
     * Respond with a 404 status code (Not found).
     * 
     * @param type $message Custom error message to provide
     * @return type
     */
    public function respondNotFound($message = 'Not Found!') {
        return $this->setStatusCode(StatusCodes::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with a 500 status code (Internal server error).
     * 
     * @param type $message Custom error message to provide
     * @return type
     */
    public function respondInternalError($message = 'Internal Error!') {
        return $this->setStatusCode(StatusCodes::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }


    
    
    
    

    /**
     * Verfies that files were uploaded successfully
     * 
     * @param array $fileList The list of files to verify (items can be array with additional requirements)
     * @return type
     */
    public function verifyFiles(array $fileList) {
        $failMessage = null;
        foreach ($fileList as $file) {
            $fileName = $file;
            $required = null;
            
            if (is_array($file)) {
                $fileName = $file["name"];
                $required = $file["required"];
            }
            
            if ($this->request->hasFile($fileName)) {
                if (!$this->request->file($fileName)->isValid()) {
                    $failMessage = $fileName . " failed to upload correctly.";
                    break;
                }
            }
            else {
                if (!empty($required)) {
                    $failMessage = $required;
                    break;
                }
            }
        }
        
        return $failMessage;
    }
    
    /**
     * Compares an array of input provided against an array required fields
     * (where the key of the required fields is the input to check and the value
     * is used as the message to respoind back from the api call with)
     * 
     * @param array $requiredFields The required fields used to sanitize the provided input
     * @param array $provided The input that was decoded and parsed (if null uses Request input)
     * @return type
     */
    public function verifyProvidedInput(array $requiredFields, array $provided = null) {
        $failureResponse = null;
        $input = (empty($provided) ? $this->request->input() : $provided);
        foreach ($requiredFields as $key => $value) {
            if (array_key_exists($key, $input) == false) {
                $failureResponse = $this->respondUnprocessable($value);
                break;
            }
        }
        return $failureResponse;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    

    /**
     * Decodes a serialized json object into a formal object
     * 
     * @param type $key The key to fetch the strong from the request
     * @param array $additionalInput Any additional input provided by the controller action
     * @return type
     */
    public function decodeInput($key, array $additionalInput = null) {
        $decoded = null;

        $json = $this->request->input($key);
        if (!empty($json)) {
            if (is_array($json)) {
                $decoded = (object)$json;
            }
            else {
                $decoded = json_decode($json);
            }
        }
        
        if (!empty($additionalInput)) {
            if (empty($decoded)) {
                $decoded = (Object)$additionalInput;
            }
            else {
                $decoded = (Object)array_merge((array)$decoded, $additionalInput);
            }
        }

        return $decoded;
    }

    /**
     * Decodes the input and enforces requirements.  
     * Input will be returned in input
     * Failures will be returned in failureResponse.
     * 
     * @param type $key The key to fetch the string from the request
     * @param array $additionalInput Any additional input provided by the controller action
     * @return type
     */
    public function requireInput($key, array $requiredFields = null, array $additionalInput = null) {
        
        $failureResponse = null; // Assume no failure
        $input = $this->decodeInput($key, $additionalInput);
        
        if ($this->isEmpty($input)) {
            $failureResponse = $this->respondUnprocessable("A filter must be provided");
        }
        else {
            if (!empty($requiredFields)) {
                $failureResponse = $this->verifyProvidedInput($requiredFields, (array)$input);
            }
        }

        return (Object)[
            "input" => $input,
            "failureResponse" => $failureResponse
        ];
    }    
}
