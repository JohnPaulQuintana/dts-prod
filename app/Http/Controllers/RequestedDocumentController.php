<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Office;
use Milon\Barcode\DNS1D;
use App\Events\NotifyEvent;
use App\Models\Barcode;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestedDocumentController extends Controller
{
    public function showIncomingRequest(){
        $user_id = Auth::user()->id;
        // for the user who requested the documents
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->leftJoin('offices', 'requested_documents.recieved_offices', '=', 'offices.id')
            ->whereIn('requested_documents.requestor_user', [$user_id])
            ->orderBy('requested_documents.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();

            $documents = $documents->map(function ($item) {
                $item->scanned = false;
                return $item;
            });
            // onwer of documents
            $documents = $documents->map(function ($item) {
                $item->current_location = $item->office_abbrev. ' | ' .$item->office_name;
                return $item;
            });
        // Now, you can join the Log table with the requested_documents table on the requested_document_id
        // Call the function to format documents with logs
        $documentsWithLogs = $this->formatDocumentsWithLogs('my document',$documents);
        // dd($documentsWithLogs);

        //for the forwarded documents for this user
        $forwardedDocuments = DB::table('logs')
            ->select('logs.*', 'requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->leftJoin('requested_documents', 'requested_documents.trk_id', '=', 'logs.trk_id')
            ->leftJoin('offices', 'logs.forwarded_to', '=', 'offices.id')
            ->whereIn('logs.forwarded_to', [Auth::user()->office_id])
            ->where('requested_documents.requestor_user', '!=', Auth::user()->id) //not the Auth user
            ->whereIn('logs.created_at', function ($query) {
                $query->select(DB::raw('MAX(created_at)'))
                    ->from('logs')
                    ->groupBy('trk_id');
            })
            ->orderBy('logs.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();


            
        $forwardedDocumentsWithLogs = $this->formatDocumentsWithLogs('requested',$forwardedDocuments);    
        // dd($forwardedDocumentsWithLogs);
        
        // merge the 2 collections
        $mergedDocumentsWithLogs = $documentsWithLogs->concat($forwardedDocumentsWithLogs)->sortByDesc('created_at');;
        // dd($mergedDocumentsWithLogs);
        //for selection request
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')->get();
       
        return view('departments.components.contents.requestDocument')->with(['documents'=>$mergedDocumentsWithLogs, 'departments'=>$allDepartments]);
    }
    public function showIncomingRequestAdmin(){
        $user_id = Auth::user()->id;
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->join('offices', 'requested_documents.requestor', '=', 'offices.id')
            // ->whereIn('requested_documents.requestor', [Auth::user()->office_id])
            ->whereIn('requested_documents.forwarded_to', [1, $user_id])
            ->orderBy('requested_documents.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();

        // dd($documents);
            // dd($documents);
        // Create a new collection with the desired structure
        $formattedDocuments = collect([]);

        foreach ($documents as $document) {
            $formattedDocument = [
                'document_id' => $document->id,
                'trk_id' => $document->trk_id,
                'requestor' => $document->requestor,
                'purpose' => $document->purpose,
                'documents' => $document->documents,
                'status' => $document->status,
                'created_at' => $document->created_at,
                'corporate_office' => [
                    'office_id' => $document->office_id,
                    'office_name' => $document->office_name,
                    'office_abbrev' => $document->office_abbrev,
                    'office_head' => $document->office_head,
                ],
            ];
        
            // Push the formatted document into the collection
            $formattedDocuments->push($formattedDocument);
           
        }

        //for selection request
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')->get();

        // Merge the two collections into a single collection
        // $combinedDocuments = $documents->concat($allDepartments);
       
        return view('admin.components.contents.requestDocument')->with(['documents'=>$formattedDocuments, 'departments'=>$allDepartments]);
    }

    public function updateIncomingRequest(Request $request){
        // dd($request);
        $id = $request->input('id');
        // Update the 'status' field using the trk_id
        $affectedRows = RequestedDocument::where('id', $id)->update(['trk_id'=>$this->generateTRKID(),'status' => 'approved']);
        // Retrieve the updated records
        $updatedRecords = RequestedDocument::where('id', $id)->get();
        // dd($updatedRecords[0]->id);
        $formattedRecords = $updatedRecords->map(function ($record) {
            $record->formatted_created_at = Carbon::parse($record->created_at)->isoFormat('ddd DD, YYYY, MMM');
            $record->formatted_updated_at = Carbon::parse($record->updated_at)->isoFormat('ddd DD, YYYY, MMM');
            return $record;
        });
        
        // get the office cred
        $office = Office::where('id',Auth::user()->office_id)->first();
      
        // Create a new RequestedDocument instance with default values
        $documentLogs = new Log([
            'trk_id' => $updatedRecords[0]->trk_id,
            'requested_document_id' => $updatedRecords[0]->id,
            'forwarded_to' => $office->id, // department id
            'current_location' => $office->office_abbrev. ' | ' .$office->office_name, // current loaction  department abbrev
            'notes' => 'Waiting for the documents to arrived',//if the have a notes
            'notes_user' => 'false',//if the have a notes
            'status' => $updatedRecords[0]->status, // Set the on-going status
            'scanned' => true,
        ]);

        $documentLogs->save();

        // get the cred from office
        $notification = new Notification([
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $updatedRecords[0]->requestor_user,//by default admin
            'notification_message'=>auth()->user()->name.' from '.$office->office_name .' has approved your document!',
            'notification_status'=>'unread',
        ]);
        $notification->save();

        // generate barcode png
        $this->generateBarcode($updatedRecords[0]->trk_id);//generate barcode png

        // generate pdf
        $this->generatePdf($updatedRecords[0]->trk_id, $updatedRecords[0]->id,$notification->notification_from_name,$office->office_name, $updatedRecords[0]->formatted_created_at,$updatedRecords[0]->formatted_created_at);

        event(new NotifyEvent('documents is updated!'));
        // Build the success message
        $message = 'Successfully updated document!';

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }
    
    // insert request
    public function create(Request $request)
    {
        // dd($request);
        // Validate the uploaded file
        $request->validate([
            'document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
            'request-text' => 'required|max:255',
            'department' => 'required|max:255',
        ]);

        // Split the value into parts using the pipe character '|'
        // $parts = explode('|', $request->input('department'));

        // Check if an image was uploaded
        if ($request->hasFile('document')) {
            $image = $request->file('document');

            $imageFolder = 'documents'; // You can change this folder name as needed

            // Store the uploaded image with a unique name
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageFolder . '/' .$imageName, file_get_contents($image));

            // Create a new RequestedDocument instance with default values
            $documentRequest = new RequestedDocument([
                // 'trk_id' => $this->generateTRKID(),
                'requestor' => auth()->user()->office_id, // Assuming you want to associate with the logged-in user
                'requestor_user' => auth()->user()->id, // Assuming you want to associate with the logged-in user
                'forwarded_to' => 1, // administrator
                'purpose' => $request->input('request-text'),
                'recieved_offices' => 1,//administrator
                'documents' => $imageName,
                'status' => 'pending', // Set the default status
            ]);

            $documentRequest->save();

             // Create a new RequestedDocument instance with default values
            $documentLogs = new Log([
                'requested_document_id' => $documentRequest->id,
                'forwarded_to' => $documentRequest->forwarded_to, // department id
                'current_location' => $request->input('department'), // current loaction  department abbrev
                'notes' => 'requesting for approval',
                'notes_user' => 'false',
                'status' => $documentRequest->status, // Set the default status
                'scanned'=>false,
            ]);
            
            $documentLogs->save();

            // explode the department abbr
            // $dept = explode(' | ',$request->input('department'));

            // get the office of requestor
            $requestorOffice = Office::where('id',$documentRequest->requestor)->first();
            // get the cred from office
            $notification = new Notification([
                'notification_from_id' => auth()->user()->id,
                'notification_from_name' => auth()->user()->name,
                'notification_to_id' => 1,//by default admin
                'notification_message'=>auth()->user()->name.' from '.$requestorOffice['office_name'].' Has forwarded a document!',
                'notification_status'=>'unread',
            ]);
            $notification->save();
            event(new NotifyEvent('departments sending a documents'));
            // Save the image timestamp in the database
            // $imageModel = new Image();
            // $imageModel->timestamp = $imageName;
            // $imageModel->save();

            // Build the success message
        $message = 'Successfully Submitted your documents!';

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);

        }
        // Prepare the toast notification data
        $notification = [
            'status' => 'warning',
            'message' => 'Documents not submitted successfully!',
        ];
         // Convert the notification to JSON
         $notificationJson = json_encode($notification);
        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);

    }

    // get the logs
    public function getLogs(Request $request){
        $id = $request->input('id');
        $logsWithDocuments = Log::with('requestedDocument')
        ->where('requested_document_id', $id)
        ->orderBy('created_at', 'desc') // Order by 'created_at' column in ascending order
        ->get();

        // Find the latest log entry based on created_at
        $latestLog = $logsWithDocuments->first();

        // Format the 'created_at' timestamps and add the 'current' key to the latest log entry
        $formattedLogs = $logsWithDocuments->map(function ($log) use ($latestLog) {
            $formattedCreatedAtSent = $log->created_at->format('M d Y');
            $formattedCreatedAtSpent = $log->created_at->diffForHumans([
                'parts' => 2, // Limit to days, hours (12-hour format), and months
            ]);

            // Check if this log entry is the latest based on created_at and add the 'current' key
            if ($log->id === $latestLog->id) {
                $log->now = 'Current Location';
                $log->class = 'text-primary';
                $log->bgclass = 'border border-primary';
            }else{
                $log->now = 'Passed Location';
                $log->class = 'text-success';
                $log->bgclass = 'border border-success';
            }

            $log->time_sent = $formattedCreatedAtSent;
            $log->time_spent = $formattedCreatedAtSpent;

            return $log;
        });

        return response()->json(['logs'=>$formattedLogs]);
    }

    // forward documents
    public function forwardIncomingRequest(Request $request){
        // dd($request);
        $request->validate([
            'department' => 'required', // Adjust the validation rules as needed
            'department_staff' => 'required|max:255',
        ]);
        $partsDepartment = explode(" | ", $request->input('department'));
        $partsDepartmentStaff = explode(" | ", $request->input('department_staff'));
        // get the office cred
        // $office = Office::where('id',Auth::user()->office_id)->first();
        $affectedRows = RequestedDocument::where('trk_id', $request->input('trk_id'))->update(['status' => 'forwarded']);
        // $logsRecord = $this->getForwardedByAdmin();
        // foreach ($logsRecord as $value) {
        //     if($value->forwarded_to == $partsDepartment[0]){
        //         $message = 'Your already forwarded this documents!';
        //         // Prepare the toast notification data
        //         $notification = [
        //             'status' => 'error',
        //             'message' => $message,
        //         ];

        //         // Convert the notification to JSON
        //         $notificationJson = json_encode($notification);

        //         // Redirect back with a success message and the inserted products
        //         return back()->with('notification', $notificationJson);
        //     }
        // }

        // Create a new RequestedDocument instance with default values notify the accounts that forwarded
        $documentLogs = new Log([
            'trk_id' => $request->input('trk_id'),
            'requested_document_id' => $request->input('id'),
            'forwarded_to' => $partsDepartment[0], // department id
            'current_location' => $partsDepartment[2]. ' | ' .$partsDepartment[1], // current loaction  department abbrev
            'notes' => 'Documents is forwarded to '.$partsDepartment[1].'. Accounts '.$partsDepartmentStaff[2],//if the have a notes
            'notes_user' => $request->filled('notes') ? $request->input('notes') : 'false',
            'status' => 'forwarded', // Set the default status
            'scanned' => true, // Set the default status
        ]);  
        $documentLogs->save();

        // get the office cred
        $office = Office::where('id',Auth::user()->office_id)->first();

        // get the cred from office
        $notificationForwarded = new Notification([
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $partsDepartmentStaff[0],//by default admin
            'notification_message'=>auth()->user()->name.' from '.$office->office_name .' has forwarded a document!',
            'notification_status'=>'unread',
        ]);
        $notificationForwarded->save();

        // Retrieve the updated records
        $updatedRecords = RequestedDocument::where('id', $request->input('id'))->first();
        // dd($updatedRecords->requestor_user);

        // get the cred from office
        $notificationRequestor = new Notification([
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $updatedRecords->requestor_user,//by default admin
            'notification_message'=>$office->office_name .' has forwarded your documents to '.$partsDepartment[1].'. Accounts '.$partsDepartmentStaff[2],
            'notification_status'=>'unread',
        ]);
        $notificationRequestor->save();

        event(new NotifyEvent('documents is forwarded!'));
        // Build the success message
        $message = 'Successfully forwarded document!';

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);

    }

    //get all departements and users
    public function departmentAndUsers($id){
        // dd($id);
        $excludedOfficeIds = [$id, 1];
        // Retrieve all departments and their users
        $departmentWithUsers = DB::table('offices')
        ->leftJoin('users', 'offices.id', '=', 'users.office_id')
        ->select('offices.*', 'users.name as user_name', 'users.email as user_email', 'users.id as user_id','users.office_id as user_office_id')
        ->whereNotIn('offices.id', $excludedOfficeIds)
        ->get();
        // Group the results by user name using Laravel collection's groupBy method
        $usersWithOffices = $departmentWithUsers->groupBy('user_name');
        // Transform the grouped collection into the specified format
        $result = $usersWithOffices->map(function ($offices, $userName) {
            return [
                'user_id' => $offices->first()->user_id, // Get the user ID from the first office,
                'user_office_id' => $offices->first()->user_office_id, // Get the user ID from the first office,
                'user_name' => $userName,
                'offices' => $offices->map(function ($office) {
                    return [
                        'office_id' => $office->id,
                        'office_name' => $office->office_name,
                        'office_abbrev' => $office->office_abbrev,
                        'office_head' => $office->office_head,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();
        return response()->json(['departmentWithUsers'=>$result]);
    }

    // barcode
    public function barcodePrinting(Request $request){
        // dd($request->trk);
        $records = DB::table('barcodes')
            ->select('barcodes.*')
            ->where('trk_id',$request->trk)
            ->get();
        // dd($records); 
            return response()->json(['records'=>$records]);

    }

    // recieved documents
    public function recievedDocument(Request $request){
        // dd($request);
        $trk = explode("-", $request->input('tracking_no'));
        // dd($trk);
        $department = explode(" | ", $request->input('document_current_loc'));

        $trkExists = RequestedDocument::where('trk_id', $trk[1])->exists();
        // dd($trkExists);
        if(!$trkExists){
            // Build the success message
            $message = "There's is no record's for that Tracking Number!";

            // Prepare the toast notification data
            $notification = [
                'status' => 'error',
                'message' => $message,
            ];

            // Convert the notification to JSON
            $notificationJson = json_encode($notification);

            // Redirect back with a success message and the inserted products
            return back()->with('notification', $notificationJson);
        }
        // Create a new RequestedDocument instance with default values notify the accounts that forwarded
        $documentLogs = new Log([
            'trk_id' => $trk[1],
            'requested_document_id' => $request->input('document_id'),
            'forwarded_to' => Auth::user()->office_id, // department id
            'current_location' => $request->input('document_current_loc'), // current loaction  department abbrev
            'notes' => 'Documents reached '.$department[1].'. Tracking Number - '.$request->input('tracking_no'),//if the have a notes
            'notes_user' => $request->filled('notes') ? $request->input('notes') : 'false',
            'status' => 'approved', // Set the default status
            'scanned' => 2, // Set the default status
        ]);  
        $documentLogs->save();
        // Build the success message
        $message = "Successfully Received Documents!";

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }

    public function update(Request $request){
        // dd($request);
         // Parse the trk_id
        $trkId = $request->input('trk_id');
        $department_id = $request->input('department_id');
        // Update the 'status' field using the trk_id
        $affectedRows = RequestedDocument::where('trk_id', $trkId)->update(['status' =>'on-going']);

        if ($affectedRows > 0) {
            // Fetch the updated RequestedDocument record
            $documentRequest = RequestedDocument::where('trk_id', $trkId)->first();
            // Create a new RequestedDocument instance with default values
            $documentLogs = new Log([
                'trk_id' => $documentRequest->trk_id,
                'requested_by' => $documentRequest->requested_by, // Assuming you want to associate with the logged-in user
                'requested_to' => $department_id, // Set the default value for requested_to
                'description' => $documentRequest->description,
                'status' => $documentRequest->status, // Set the default status
            ]);
            $documentLogs->save();

            return response()->json(['message' => 'Status updated successfully']);
             
        } else {
            return response()->json(['message' => 'Document request not found'], 404);
        }

    }

    public function generateTRKID(){
         // Generate a unique ID  and 6 random digits
         $uniqueId = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
         return $uniqueId;
    }
    
    // format documents
    function formatDocumentsWithLogs($types,$documents)
    {
        return $documents->map(function ($document) use ($types) {
            $log = Log::select('requested_document_id', 'trk_id', 'current_location', 'notes', 'status','scanned')
                ->where('requested_document_id', $document->id)->get();

            // Extract the 'current_location' values from the logs
            $locations = $log->pluck('current_location')->toArray();

            // Remove duplicates from the locations array
            $uniqueLocations = array_unique($locations);

            // Extract only the first part (before the '|') from each location
            $formattedLocations = array_map(function ($location) {
                $parts = explode(' | ', $location);
                return $parts[0];
            }, $uniqueLocations);

            // Concatenate the 'current_location' values with '|'
            $formattedLocationsString = implode(' | ', $formattedLocations);

            // Create the formatted document
            $formattedDocument = [
                'type' => $types,
                'document_id' => $document->id,
                'trk_id' => $document->trk_id,
                'requestor' => $document->requestor,
                'purpose' => $document->purpose,
                'documents' => $document->documents,
                'status' => $document->status,
                'current_location'=>$document->current_location,
                'created_at' => $document->created_at,
                'corporate_office' => [
                    'office_id' => $document->office_id,
                    'office_name' => $document->office_name,
                    'office_abbrev' => $document->office_abbrev,
                    'office_head' => $document->office_head,
                ],
                'logs' => $formattedLocationsString,
                'scanned' =>$document->scanned,
            ];

            return $formattedDocument;
        });
    }

    //get all forwarded by admin
    function getForwardedByAdmin(){
        $logsRecord = Log::where('status','forwarded')->get();
        return $logsRecord;
    }

    // generate barcode png
    function generateBarcode($trk_id){
        // Generate the barcode image path
        $dns1d = new DNS1D;
        $barcodeImagePath = $dns1d->getBarcodePNGPath($trk_id, 'C39', 3, 50);

        if ($barcodeImagePath) {
            // Define the destination folder within the public directory
            $destinationFolder = 'barcode';

            // Get the image file name (you may need to customize this based on your requirements)
            $fileName = $trk_id.'.png';

            // Define the full path where the image should be saved in the public folder
            $fullPath = public_path($destinationFolder . '/' . $fileName);

            // Ensure the destination folder exists; if not, create it
            if (!file_exists(public_path($destinationFolder))) {
                mkdir(public_path($destinationFolder), 0777, true);
            }

            // Copy the barcode image to the specified folder within the public directory
            if (copy(public_path($barcodeImagePath), $fullPath)) {
                // Image has been successfully saved
                echo "Barcode image saved to $destinationFolder folder in the public directory.";
                // Delete the original barcode image from the public directory
                unlink(public_path($barcodeImagePath));
            } else {
                // Handle the case where the image could not be saved
                echo "Failed to save barcode image.";
            }
        } else {
            // Handle the case where the barcode image data could not be generated
            echo "Failed to generate barcode image.";
        }
    }



    // generate pdf
    function generatePdf($trk_id, $id,$name,$department, $date_created,$date_approved){
        $barcode_Path = 'barcode_pdfs/';
        // create pdf for carcode printing
        $pdf = new Fpdf;

        $pdf->AddPage();

         // Define the card dimensions and position
        $cardX = 20; // X-coordinate of the card
        $cardY = 20; // Y-coordinate of the card
        $cardWidth = 170; // Width of the card
        $cardHeight = 150; // Height of the card


        // Draw a border around the card
        $pdf->SetDrawColor(0); // Set the border color to black
        $pdf->Rect($cardX, $cardY, $cardWidth, $cardHeight);

        // Calculate Y-coordinate for the title to center it vertically in the card
        $pdf->SetFont('Courier', 'B', 18);
        $titleHeight = 10; // Height of the title cell
        // Center-align the title and set color (e.g., blue)
        $pdf->Cell(0, 50, 'Tracking No.', 0, 1, 'C', false, '');
        
         // Add the barcode image
        $barcodeImageX = ($cardX * 4); // X-coordinate of the barcode image
        $barcodeImageY = 40; // Y-coordinate of the barcode image
        $pdf->Image(public_path('barcode/').$trk_id.'.png', $barcodeImageX , $barcodeImageY, 50);

        // Reset font size for the value (smaller)
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -6, "TRK-".$trk_id, 0, 1, 'C');

        // Date Created
        $pdf->SetFont('Arial', '', 16);
        $pdf->SetTextColor(0); // RGB color
        $pdf->Cell(0, 30, 'Date Created', 0, 1, 'C');
        // 
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -15, $date_created, 0, 1, 'C');

        // Reset font size for the value (smaller)
        $pdf->SetFont('Arial', '', 16);
        $pdf->SetTextColor(0); // RGB color
        $pdf->Cell(0, 30, 'Date Approved', 0, 1, 'C');
        // 
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -15, $date_approved, 0, 1, 'C');

        // Date Approved
        $pdf->SetFont('Arial', '', 16);
        $pdf->SetTextColor(0); // RGB color
        $pdf->Cell(0, 30, 'Department', 0, 1, 'C');
        // 
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -15, $department.' Office', 0, 1, 'C');

        // Date Approved
        $pdf->SetFont('Arial', '', 16);
        $pdf->SetTextColor(0); // RGB color
        $pdf->Cell(0, 30, 'Department Staff', 0, 1, 'C');
        // 
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -15, $name, 0, 1, 'C');

        $pdf->Output('F', public_path($barcode_Path) . $trk_id . '.pdf');

        $barcodeRecord = new Barcode([
            'trk_id' => $trk_id,
            'document_id' => $id,
            'document_code' => $barcode_Path . $trk_id . '.pdf',
        ]);

        $barcodeRecord->save();
    }
}
