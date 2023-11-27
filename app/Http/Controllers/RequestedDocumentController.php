<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Office;
use App\Models\Report;
use App\Models\Barcode;
use Milon\Barcode\DNS1D;
use App\Events\NotifyEvent;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\GenerateTable;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RequestedDocumentController extends Controller
{

    public function monitor(){
        $documents = RequestedDocument::get();

        // Fetch logs for each document separately
        $documents->each(function ($document) {
            $document->logs = Log::where('requested_document_id', $document->id)
                ->orderBy('id', 'desc') // Order the logs by id in descending order to get the latest log first
                ->get();
        });
        

    // dd($documents);

    
        return view('admin.components.contents.monitor')->with(['documents'=>$documents]);
    }
    public function myRequestAdmin()
    {
        $user_id = Auth::user()->id;
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->join('offices', 'requested_documents.requestor', '=', 'offices.id')
            // ->whereIn('requested_documents.requestor', [Auth::user()->office_id])
            // ->whereIn('requested_documents.forwarded_to', [1, $user_id])
            ->Where('requested_documents.requestor_user', $user_id)
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
                'requestor_user_id' => $document->requestor_user,
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
            // dd($formattedDocuments);
        }

        //for selection request
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')
            ->where('office_type', '!=', 'viewing')
            ->where('id', '!=', 1)
            ->get();

        //    dd($allDepartments);
        // Initialize an empty array to store the merged data
        $mergedData = [];

        foreach ($allDepartments as $department) {
            $departmentId = $department->id;

            // Retrieve the users for the current department
            $usersInDepartment = User::where('office_id', $departmentId)
                ->where('status', 'active')
                ->where('office_id', '!=', 1)
                ->get();

            // Merge the office and user data for this department
            $mergedData[$department->office_abbrev] = [
                'office' => $department,
                'users' => $usersInDepartment,
            ];
        }
        //  dd($mergedData);

        // Merge the two collections into a single collection
        // $combinedDocuments = $documents->concat($allDepartments);

        return view('admin.components.contents.my-request')->with(['documents' => $formattedDocuments, 'departments' => $mergedData]);
        // return view('admin.components.contents.my-request', ['mydocs'=>$myDocuments]);
    }

    //departments my request
    public function myRequest()
    {
        $user_id = Auth::user()->id;
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->join('offices', 'requested_documents.requestor', '=', 'offices.id')
            // ->whereIn('requested_documents.requestor', [Auth::user()->office_id])
            // ->whereIn('requested_documents.forwarded_to', [1, $user_id])
            ->Where('requested_documents.requestor_user', $user_id)
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
                'requestor_user_id' => $document->requestor_user,
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
            // dd($formattedDocuments);
        }

        //for selection request
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')
            ->where('office_type', '!=', 'viewing')
            ->where('id', '!=', 1)
            ->get();

        //    dd($allDepartments);
        // Initialize an empty array to store the merged data
        $mergedData = [];

        foreach ($allDepartments as $department) {
            $departmentId = $department->id;

            // Retrieve the users for the current department
            $usersInDepartment = User::where('office_id', $departmentId)
                ->where('status', 'active')
                ->where('office_id', '!=', Auth::user()->office_id)
                ->get();

            // Merge the office and user data for this department
            $mergedData[$department->office_abbrev] = [
                'office' => $department,
                'users' => $usersInDepartment,
            ];
        }
        //  dd($mergedData);

        // Merge the two collections into a single collection
        // $combinedDocuments = $documents->concat($allDepartments);

        return view('departments.components.contents.my-request')->with(['documents' => $formattedDocuments, 'departments' => $mergedData]);
    }
    public function showIncomingRequest()
    {
        $user_id = Auth::user()->id;
        // old but for authuser only
        // for the user who requested the documents
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->leftJoin('offices', 'requested_documents.recieved_offices', '=', 'offices.id')
            ->whereIn('requested_documents.requestor_user', [$user_id])
            // ->whereIn('')
            // ->orWhere('requested_documents.forwarded_to', $user_id) // Add this condition for forwarded_to
            // ->where('requested_documents.forwarded_to', $user_id)
            // ->where('requested_documents.status','approved')
            ->orderBy('requested_documents.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();
        // dd($documents);
        // requested on me
        $documentsRequestedOnMe = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->leftJoin('offices', 'requested_documents.recieved_offices', '=', 'offices.id')
            ->whereIn('requested_documents.forwarded_to', [$user_id])
            // ->orWhere('requested_documents.forwarded_to', $user_id) // Add this condition for forwarded_to
            // ->where('requested_documents.forwarded_to', $user_id)
            ->where('requested_documents.status', 'pending')
            ->orderBy('requested_documents.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();

        // dd($documentsRequestedOnMe);
        //updated
        // $documents = DB::table('requested_documents')
        //     ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
        //     ->leftJoin('offices', 'requested_documents.recieved_offices', '=', 'offices.id')
        //     ->where(function ($query) use ($user_id) {
        //         $query->whereIn('requested_documents.requestor_user', [$user_id])
        //             ->orWhere('requested_documents.forwarded_to', $user_id);
        //     })
        //     ->orderBy('requested_documents.created_at', 'desc')
        //     ->get();


        // $documents = $documents->map(function ($item) {
        //     $item->scanned = false;
        //     return $item;
        // });
        // onwer of documents
        $documents = $documents->map(function ($item) use ($user_id) {
            $item->scanned = false;
            $item->current_location = $item->office_abbrev . ' | ' . $item->office_name;
            // Add the 'type' column based on the user_id
            if ($item->requestor_user == $user_id) {
                $item->type = 1; // my docs
            } else {
                $item->type = 0; //not my docs
            }
            return $item;
        });

        // dd($documents);
        //requested on me
        $documentsRequestedOnMeFormatted = $documentsRequestedOnMe->map(function ($item) use ($user_id) {
            $item->scanned = false;
            $item->current_location = $item->office_abbrev . ' | ' . $item->office_name;
            // Add the 'type' column based on the user_id
            if ($item->requestor_user == $user_id) {
                $item->type = 1; // my docs
            } else {
                $item->type = 0; //not my docs
            }
            return $item;
        });


        // Now, you can join the Log table with the requested_documents table on the requested_document_id
        // Call the function to format documents with logs
        $documentsWithLogs = $this->formatDocumentsWithLogs('my document', $documents);
        // dd($documentsWithLogs);

        //for requested on me formatted
        $documentsOnMe = $this->formatDocumentsWithLogs('requested', $documentsRequestedOnMeFormatted);
        // dd($documentsOnMe);
        //for the forwarded documents for this user
        $forwardedDocuments = DB::table('logs')
            ->select('logs.*', 'requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->leftJoin('requested_documents', 'requested_documents.trk_id', '=', 'logs.trk_id')
            ->leftJoin('offices', 'logs.forwarded_to', '=', 'offices.id')
            ->whereIn('logs.forwarded_to', [Auth::user()->office_id]) //the forwarded is the user_id // we have a problem
            ->where('requested_documents.requestor_user', '!=', Auth::user()->id) //not the Auth user
            ->whereIn('logs.created_at', function ($query) {
                $query->select(DB::raw('MAX(created_at)'))
                    ->from('logs')
                    ->groupBy('trk_id');
            })
            // ->where(function($query){
            //     $query->where('logs.destination', '=', Auth::user()->id);//
            // })
            ->orderBy('logs.created_at', 'desc') // Order by 'created_at' column in descending order (latest to oldest)
            ->get();

        // dd($forwardedDocuments);

        $forwardedDocumentsWithLogs = $this->formatDocumentsWithLogs('requested', $forwardedDocuments);
        // dd($forwardedDocumentsWithLogs);

        // merge the 2 collections
        $mergedDocumentsWithLogs = $documentsWithLogs->concat($forwardedDocumentsWithLogs)->concat($documentsOnMe)->sortByDesc('created_at');
        // dd($mergedDocumentsWithLogs);
        //for selection request
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')
            ->where('office_type', '!=', 'viewing')
            // ->where('id', '!=', Auth::user()->office_id)
            ->get();
        // dd($allDepartments);
        // Initialize an empty array to store the merged data
        $mergedData = [];

        foreach ($allDepartments as $department) {
            $departmentId = $department->id;

            // Retrieve the users for the current department
            $usersInDepartment = User::where('office_id', $departmentId)
                ->where('status', 'active')
                ->where('assigned', '!=', 'viewing')
                ->where('id', '!=', Auth::user()->id)
                ->get();

            // Merge the office and user data for this department
            $mergedData[$department->office_abbrev] = [
                'office' => $department,
                'users' => $usersInDepartment,
            ];
        }

        return view('departments.components.contents.requestDocument')->with(['documents' => $mergedDocumentsWithLogs, 'departments' => $mergedData]);
    }
    public function showIncomingRequestAdmin()
    {
        $user_id = Auth::user()->id;
        $documents = DB::table('requested_documents')
            ->select('requested_documents.*', 'offices.id as office_id', 'offices.office_name', 'offices.office_abbrev', 'offices.office_head')
            ->join('offices', 'requested_documents.requestor', '=', 'offices.id')
            // ->whereIn('requested_documents.requestor', [Auth::user()->office_id])
            ->whereIn('requested_documents.forwarded_to', [1, $user_id])
            // ->Where('requested_documents.requestor_user',$user_id)
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
                'pr' => $document->pr,
                'requestor' => $document->requestor,
                'requestor_user_id' => $document->requestor_user,
                'purpose' => $document->purpose,
                'amount' => $document->amount,
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
        $allDepartments = Office::select('id', 'office_name', 'office_abbrev', 'office_head')
            ->where('office_type', '!=', 'viewing')
            ->get();

        // Merge the two collections into a single collection
        // $combinedDocuments = $documents->concat($allDepartments);

        return view('admin.components.contents.requestDocument')->with(['documents' => $formattedDocuments, 'departments' => $allDepartments]);
    }

    public function updateIncomingRequest(Request $request)
    {
        // dd($request);
        $id = $request->input('id');
        $action = $request->input('action');
        $pr = $request->input('pr');
        $po = $request->input('po');
        // dd($pr);
        switch ($action) {
            case 'Approved':

                $affectedRows = RequestedDocument::where('id', $id)->update(['trk_id' => $this->generateTRKID(), 'pr' => 'PR-' . $pr, 'status' => 'approved']);


                // Retrieve the updated records
                $updatedRecords = RequestedDocument::where('id', $id)->get();
                // dd($updatedRecords[0]->id);
                $formattedRecords = $updatedRecords->map(function ($record) {
                    $record->formatted_created_at = Carbon::parse($record->created_at)->isoFormat('ddd DD, YYYY, MMM');
                    $record->formatted_updated_at = Carbon::parse($record->updated_at)->isoFormat('ddd DD, YYYY, MMM');
                    return $record;
                });

                // get the office cred
                $office = Office::where('id', Auth::user()->office_id)->first();

                // calculate the date and time range
                $startDate = Carbon::parse($updatedRecords[0]->created_at);  // Convert the created_at timestamp to a Carbon instance
                $endDate = Carbon::now();  // Get the current date and time

                // Calculate the difference between the two dates
                $range = $startDate->diff($endDate);

                // Format the range as hours, minutes, and seconds
                $rangeString = $range->format('%H:%I:%S');
                // Create a new RequestedDocument instance with default values
                $documentLogs = new Log([
                    'trk_id' => $updatedRecords[0]->trk_id,
                    'requested_document_id' => $updatedRecords[0]->id,
                    'forwarded_to' => $office->id, // department id
                    'destination' => Auth::user()->id,
                    'current_location' => $office->office_abbrev . ' | ' . $office->office_name, // current loaction  department abbrev
                    'notes' => 'Waiting for the documents to arrived', //if the have a notes
                    'notes_user' => 'false', //if the have a notes
                    'status' => $updatedRecords[0]->status, // Set the on-going status
                    'scanned' => true,
                    'time_range' => Carbon::now(),
                ]);

                $documentLogs->save();

                // get the cred from office
                $notification = new Notification([
                    'notification_from_id' => auth()->user()->id,
                    'notification_from_name' => auth()->user()->name,
                    'notification_to_id' => $updatedRecords[0]->requestor_user, //by default admin
                    'notification_message' => auth()->user()->name . ' from ' . $office->office_name . ' has approved your document!',
                    'notification_status' => 'unread',
                ]);
                $notification->save();

                // generate barcode png
                $this->generateBarcode($updatedRecords[0]->trk_id); //generate barcode png

                // generate pdf
                $this->generatePdf($updatedRecords[0]->trk_id, $updatedRecords[0]->id, $notification->notification_from_name, $office->office_name, $updatedRecords[0]->formatted_created_at, $updatedRecords[0]->formatted_created_at);

                event(new NotifyEvent('documents is updated!'));
                // Build the success message
                $message = 'Successfully updated document!';

                // Prepare the toast notification data
                $notification = [
                    'status' => 'success',
                    'message' => $message,
                ];
                break;
            case 'Archived':
                // Update the 'status' field using the trk_id
                $affectedRows = RequestedDocument::where('id', $id)->update(['status' => 'archived']);
                // Retrieve the updated records
                $updatedRecords = RequestedDocument::where('id', $id)->get();
                // dd($updatedRecords[0]->id);
                $formattedRecords = $updatedRecords->map(function ($record) {
                    $record->formatted_created_at = Carbon::parse($record->created_at)->isoFormat('ddd DD, YYYY, MMM');
                    $record->formatted_updated_at = Carbon::parse($record->updated_at)->isoFormat('ddd DD, YYYY, MMM');
                    return $record;
                });

                // get the office cred
                $office = Office::where('id', Auth::user()->office_id)->first();

                // Create a new RequestedDocument instance with default values
                $documentLogs = new Log([
                    'trk_id' => $updatedRecords[0]->trk_id,
                    'requested_document_id' => $updatedRecords[0]->id,
                    'forwarded_to' => $office->id, // department id
                    'destination' => Auth::user()->id,
                    'current_location' => $office->office_abbrev . ' | ' . $office->office_name, // current loaction  department abbrev
                    'notes' => 'Document is rejected by admin.', //if the have a notes
                    'notes_user' => 'false', //if the have a notes
                    'status' => $updatedRecords[0]->status, // Set the on-going status
                    'scanned' => true,
                ]);

                $documentLogs->save();

                // get the cred from office
                $notification = new Notification([
                    'notification_from_id' => auth()->user()->id,
                    'notification_from_name' => auth()->user()->name,
                    'notification_to_id' => $updatedRecords[0]->requestor_user, //by default admin
                    'notification_message' => auth()->user()->name . ' from ' . $office->office_name . ' has rejected your document!',
                    'notification_status' => 'unread',
                ]);
                $notification->save();

                event(new NotifyEvent('documents is rejected!'));
                // Build the success message
                $message = 'Successfully updated document!';

                // Prepare the toast notification data
                $notification = [
                    'status' => 'error',
                    'message' => $message,
                ];
            case 'Re-process':
                // Update the 'status' field using the trk_id
                $affectedRows = RequestedDocument::where('id', $id)->update(['status' => 'approved']);
                // Retrieve the updated records
                $updatedRecords = RequestedDocument::where('id', $id)->get();
                // dd($updatedRecords[0]->id);
                $formattedRecords = $updatedRecords->map(function ($record) {
                    $record->formatted_created_at = Carbon::parse($record->created_at)->isoFormat('ddd DD, YYYY, MMM');
                    $record->formatted_updated_at = Carbon::parse($record->updated_at)->isoFormat('ddd DD, YYYY, MMM');
                    return $record;
                });

                // get the office cred
                $office = Office::where('id', Auth::user()->office_id)->first();

                // Create a new RequestedDocument instance with default values
                $documentLogs = new Log([
                    'trk_id' => $updatedRecords[0]->trk_id,
                    'requested_document_id' => $updatedRecords[0]->id,
                    'forwarded_to' => $office->id, // department id
                    'destination' => Auth::user()->id,
                    'current_location' => $office->office_abbrev . ' | ' . $office->office_name, // current loaction  department abbrev
                    'notes' => 'Document is re-processing by admin.', //if the have a notes
                    'notes_user' => 'Document is re-processing by admin', //if the have a notes
                    'status' => $updatedRecords[0]->status, // Set the on-going status
                    'scanned' => true,
                ]);

                $documentLogs->save();

                // get the cred from office
                $notification = new Notification([
                    'notification_from_id' => auth()->user()->id,
                    'notification_from_name' => auth()->user()->name,
                    'notification_to_id' => $updatedRecords[0]->requestor_user, //by default admin
                    'notification_message' => auth()->user()->name . ' from ' . $office->office_name . ' has reprocess your document!',
                    'notification_status' => 'unread',
                ]);
                $notification->save();

                event(new NotifyEvent('documents is reprocess!'));
                // Build the success message
                $message = 'Successfully updated document!';

                // Prepare the toast notification data
                $notification = [
                    'status' => 'error',
                    'message' => $message,
                ];
                break;
            default:
                # code...
                break;
        }


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
        try {
            $request->validate([
                'document' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'request-text' => 'required|max:255',
                'department' => 'required|max:255',
            ]);
        } catch (ValidationException $e) {
            $notification = [
                'status' => 'error',
                'message' => 'Documents not submitted successfully!',
            ];
            // Convert the notification to JSON
            $notificationJson = json_encode($notification);
            // Redirect back with a success message and the inserted products
            return back()->with('notification', $notificationJson);
        }

        // Split the value into parts using the pipe character '|'
        $parts = explode('|', $request->input('department'));
        $departmentUser = Office::where('office_abbrev', $parts[0])->first();
        if ($departmentUser) {
            $usersInDepartment = User::where('office_id', $departmentUser['id'])->where('id', $parts[3])->first();
            //  dd($usersInDepartment);
        }
        // Check if an image was uploaded
        if ($request->hasFile('document')) {
            $image = $request->file('document');

            $imageFolder = 'documents'; // You can change this folder name as needed

            // Store the uploaded image with a unique name
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageFolder . '/' . $imageName, file_get_contents($image));

            // dd(auth()->user()->role);
            // Create a new RequestedDocument instance with default values
            $documentRequest = new RequestedDocument([
                // 'trk_id' => $this->generateTRKID(),
                'requestor' => auth()->user()->office_id, // Assuming you want to associate with the logged-in user
                'requestor_user' => auth()->user()->id, // Assuming you want to associate with the logged-in user
                // 'forwarded_to' => Auth::user()->role !== 1 ? 1 : $usersInDepartment['id'], // administrator
                'forwarded_to' => $usersInDepartment['id'], //can send to all
                'purpose' => $request->input('request-text'),
                'amount' => $request->input('amount'),
                'recieved_offices' => Auth::user()->role !== 1 ? 1 : $usersInDepartment['office_id'], //administrator
                'documents' => $imageName,
                'status' => 'pending', // Set the default status
            ]);

            $documentRequest->save();

            // if admin auto approved
            // if (Auth::user()->role === 1) {
            // $this->autoApproved($documentRequest->id, $usersInDepartment['id'], $request->input('purchased-request'));
            // Create a new RequestedDocument instance with default values
            // } else {
            // Create a new RequestedDocument instance with default values
            $documentLogs = new Log([
                'requested_document_id' => $documentRequest->id,
                'forwarded_to' => $documentRequest->forwarded_to, // department id 
                'destination' => $usersInDepartment['id'], // department user id 
                'current_location' => $parts[0] . ' | ' . $parts[1], // current loaction  department abbrev
                'notes' => 'requesting for approval',
                'notes_user' => 'false',
                'status' => $documentRequest->status, // Set the default status
                'scanned' => false,
            ]);

            $documentLogs->save();

            // explode the department abbr
            // $dept = explode(' | ',$request->input('department'));

            // get the office of requestor
            $requestorOffice = Office::where('id', $documentRequest->requestor)->first();
            // get the cred from office
            $notification = new Notification([
                'notification_from_id' => auth()->user()->id,
                'notification_from_name' => auth()->user()->name,
                'notification_to_id' => Auth::user()->role !== 1 ? 1 : $parts[3], //by default admin
                'notification_message' => auth()->user()->name . ' from ' . $requestorOffice['office_name'] . ' Has forwarded a document!',
                'notification_status' => 'unread',
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
            // }
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

    //admin auto approved function
    public function autoApproved($id, $reciever, $pr)
    {
        // Update the 'status' field using the trk_id
        $affectedRows = RequestedDocument::where('id', $id)->update(['trk_id' => $this->generateTRKID(), 'pr' => 'PR-' . $pr, 'status' => 'pending']);
        // Retrieve the updated records
        $updatedRecords = RequestedDocument::where('id', $id)->get();
        // dd($updatedRecords[0]->id);
        $formattedRecords = $updatedRecords->map(function ($record) {
            $record->formatted_created_at = Carbon::parse($record->created_at)->isoFormat('ddd DD, YYYY, MMM');
            $record->formatted_updated_at = Carbon::parse($record->updated_at)->isoFormat('ddd DD, YYYY, MMM');
            return $record;
        });

        // get the office cred
        $office = Office::where('id', Auth::user()->office_id)->first();

        //get the users
        $recieverUser = User::where('id', $reciever)->first();
        // calculate the date and time range
        $startDate = Carbon::parse($updatedRecords[0]->created_at);  // Convert the created_at timestamp to a Carbon instance
        $endDate = Carbon::now();  // Get the current date and time

        // Calculate the difference between the two dates
        $range = $startDate->diff($endDate);

        // Format the range as hours, minutes, and seconds
        $rangeString = $range->format('%H:%I:%S');
        // Create a new RequestedDocument instance with default values
        $documentLogs = new Log([
            'trk_id' => $updatedRecords[0]->trk_id,
            'requested_document_id' => $updatedRecords[0]->id,
            'forwarded_to' => $office->id, // department id
            'destination' => $recieverUser->id, // department user id 
            'current_location' => $office->office_abbrev . ' | ' . $office->office_name, // current location  department abbrev
            'notes' => 'Waiting for the documents to arrived', //if the have a notes
            'notes_user' => 'Admin requested a documents', //if the have a notes
            'status' => $updatedRecords[0]->status, // Set the on-going status
            'scanned' => false,
            'time_range' => Carbon::now(),
        ]);

        $documentLogs->save();

        // get the cred from office
        $notification = new Notification([
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $updatedRecords[0]->requestor_user, //by default admin
            'notification_message' => auth()->user()->name . ' successfully sent a document!',
            'notification_status' => 'unread',
        ]);
        $notification->save();

        // generate barcode png
        $this->generateBarcode($updatedRecords[0]->trk_id); //generate barcode png

        // generate pdf
        $this->generatePdf($updatedRecords[0]->trk_id, $updatedRecords[0]->id, $notification->notification_from_name, $office->office_name, $updatedRecords[0]->formatted_created_at, $updatedRecords[0]->formatted_created_at);

        event(new NotifyEvent('documents sent!'));
        // Build the success message
        $message = 'Successfully sent a document!';

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];
    }

    // get the logs
    public function getLogs(Request $request)
    {
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
            $formattedCreatedAtSpent = $log->created_at->format('h:i:s A');

            // Check if this log entry is the latest based on created_at and add the 'current' key
            if ($log->id === $latestLog->id) {
                $log->now = 'Current Location';
                $log->class = 'text-primary';
                $log->bgclass = 'border border-primary';
            } else {
                $log->now = 'Passed Location';
                $log->class = 'text-success';
                $log->bgclass = 'border border-success';
            }

            $log->time_sent = $formattedCreatedAtSent;
            $log->time_spent = $formattedCreatedAtSpent;

            return $log;
        });

        return response()->json(['logs' => $formattedLogs]);
    }

    // forward documents
    public function forwardIncomingRequest(Request $request)
    {
        // dd($request);
        $request->validate([
            'department' => 'required', // Adjust the validation rules as needed
            'department_staff' => 'required|max:255',
        ]);
        $partsDepartment = explode(" | ", $request->input('department'));
        $partsDepartmentStaff = explode(" | ", $request->input('department_staff'));
        // dd('')
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
            'destination' => $partsDepartmentStaff[0],
            'current_location' => $partsDepartment[2] . ' | ' . $partsDepartment[1], // current loaction  department abbrev
            'notes' => 'Documents is forwarded to ' . $partsDepartment[1] . '. Accounts ' . $partsDepartmentStaff[2], //if the have a notes
            'notes_user' => $request->filled('notes') ? $request->input('notes') : 'false',
            'status' => 'forwarded', // Set the default status
            'scanned' => false, // Set the default status
        ]);
        $documentLogs->save();

        // get the office cred
        $office = Office::where('id', Auth::user()->office_id)->first();

        // get the cred from office
        $notificationForwarded = new Notification([
            'notification_trk' => $request->input('trk_id'),
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $partsDepartmentStaff[0], //by default admin
            'notification_message' => auth()->user()->name . ' from ' . $office->office_name . ' has forwarded a document!',
            'notification_status' => 'unread',
        ]);
        $notificationForwarded->save();

        // Retrieve the updated records
        $updatedRecords = RequestedDocument::where('id', $request->input('id'))->first();
        // dd($updatedRecords->requestor_user);

        // get the cred from office
        $notificationRequestor = new Notification([
            'notification_trk' => $request->input('trk_id'),
            'notification_from_id' => auth()->user()->id,
            'notification_from_name' => auth()->user()->name,
            'notification_to_id' => $updatedRecords->requestor_user, //by default admin
            'notification_message' => $office->office_name . ' has forwarded your documents to ' . $partsDepartment[1] . '. Accounts ' . $partsDepartmentStaff[2],
            'notification_status' => 'unread',
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
    public function departmentAndUsers($requestor)
    {
        // dd($requestor);
        $excludedOfficeIds = [$requestor, 1, Auth::user()->id]; //user id
        // updates
        $departmentWithUsers = DB::table('offices')
            ->leftJoin('users', 'offices.id', '=', 'users.office_id')
            ->select('offices.*', 'users.name as user_name', 'users.email as user_email', 'users.id as user_id', 'users.office_id as user_office_id')
            ->whereNotIn('users.id', $excludedOfficeIds) //this is the problem all the users with the same office_id
            ->where(function ($query) {
                $query->where('users.status', 'active')
                    ->where('users.assigned', 'processing');
            })
            ->get();

        // Group the results by user name using Laravel collection's groupBy method
        $usersWithOffices = $departmentWithUsers->groupBy('user_name');
        // dd($usersWithOffices);
        // Transform the grouped collection into the specified format
        $result = $usersWithOffices->map(function ($offices, $userName) {
            // dd($userName);
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
        return response()->json(['departmentWithUsers' => $result]);
    }

    // barcode
    public function barcodePrinting(Request $request)
    {
        // dd($request->trk);
        $records = DB::table('barcodes')
            ->select('barcodes.*')
            ->where('trk_id', $request->trk)
            ->get();
        // dd($records); 
        return response()->json(['records' => $records]);
    }

    // recieved documents
    public function recievedDocument(Request $request)
    {
        $completed = $request->input('completed');
        $trk = explode("-", $request->input('tracking_no'));
        // dd($trk);
        $department = explode(" | ", $request->input('document_current_loc'));
        // Assuming $trk is an array
        if (isset($trk[1])) {
            // $trk[1] is available, check its existence in the database
            $trkExists = RequestedDocument::where('trk_id', $trk[1])->exists();
        } else {
            // $trk[1] is not available, use $trk[0] instead
            $trkExists = RequestedDocument::where('trk_id', $trk[0])->exists();
        }
        // $trkExists = RequestedDocument::where('trk_id', $trk[1])->exists();
        // dd($trkExists);
        if (!$trkExists) {
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
            'trk_id' => isset($trk[1]) ? $trk[1] : $trk[0], // Use $trk[1] if available, otherwise use $trk[0]
            'requested_document_id' => $request->input('document_id'),
            'forwarded_to' => Auth::user()->office_id, // department id
            'destination' => Auth::user()->id,
            'current_location' => $request->input('document_current_loc'), // current loaction  department abbrev
            'notes' => 'Documents reached ' . $department[1] . '. Tracking Number - ' . $request->input('tracking_no'), //if the have a notes
            'notes_user' => $request->filled('notes') ? $request->input('notes') : 'false',
            'status' => $request->input('completed') !== null
                ? 'completed'
                : 'approved',
            'scanned' => 2, // Set the default status
        ]);
        $documentLogs->save();

        if (isset($completed)) {
            RequestedDocument::where('id', $request->input('document_id'))
                ->update(['status' => 'completed']);
        }

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

    public function update(Request $request)
    {
        // dd($request);
        // Parse the trk_id
        $trkId = $request->input('trk_id');
        $department_id = $request->input('department_id');
        // Update the 'status' field using the trk_id
        $affectedRows = RequestedDocument::where('trk_id', $trkId)->update(['status' => 'on-going']);

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

    public function generateTRKID()
    {
        // Generate a unique ID  and 6 random digits
        $uniqueId = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        return $uniqueId;
    }

    // format documents
    function formatDocumentsWithLogs($types, $documents)
    {
        return $documents->map(function ($document) use ($types) {
            $log = Log::select('requested_document_id', 'trk_id', 'current_location', 'destination', 'notes', 'status', 'scanned')
                ->where('requested_document_id', $document->id)->get();

            // Extract the 'current_location' values from the logs
            $locations = $log->pluck('current_location')->toArray();
            $destinations = $log->pluck('destination')->toArray();

            // Remove duplicates from the locations array
            $uniqueLocations = array_unique($locations);

            // Extract only the first part (before the '|') from each location
            $formattedLocations = array_map(function ($location) {
                $parts = explode(' | ', $location);
                return $parts[0];
            }, $uniqueLocations);

            // Concatenate the 'current_location' values with '|'
            $formattedLocationsString = implode(' | ', $formattedLocations);
            // dd($destinations);
            // Create the formatted document
            $formattedDocument = [
                'type' => $types,
                'belongsTo' => $document->type ?? 3, // 3 means unknown
                'from' => $document->requestor_user,
                'document_id' => $document->id,
                'trk_id' => $document->trk_id,
                'pr' => $document->pr,
                'po' => $document->po,
                'requestor' => $document->requestor,
                'purpose' => $document->purpose,
                'amount' => $document->amount,
                'documents' => $document->documents,
                'status' => $document->status,
                'current_location' => $document->current_location,
                'destination' => $destinations,
                'created_at' => $document->created_at,
                'corporate_office' => [
                    'office_id' => $document->office_id,
                    'office_name' => $document->office_name,
                    'office_abbrev' => $document->office_abbrev,
                    'office_head' => $document->office_head,
                ],
                'logs' => $formattedLocationsString,
                'scanned' => $document->scanned,
            ];

            return $formattedDocument;
        });
    }

    //get all forwarded by admin
    function getForwardedByAdmin()
    {
        $logsRecord = Log::where('status', 'forwarded')->get();
        return $logsRecord;
    }

    // generate barcode png
    function generateBarcode($trk_id)
    {
        // Generate the barcode image path
        $dns1d = new DNS1D;
        $barcodeImagePath = $dns1d->getBarcodePNGPath($trk_id, 'C39', 3, 50);

        if ($barcodeImagePath) {
            // Define the destination folder within the public directory
            $destinationFolder = 'barcode';

            // Get the image file name (you may need to customize this based on your requirements)
            $fileName = $trk_id . '.png';

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


    public function generateReports(Request $request)
    {
        // dd($request);
        $trk = $request->input('trk');
        $from = $request->input('from');
        $to = $request->input('to');
        $office = $request->input('office');
        // $processedBy = $request->input('processed-by');
        $processedByDept = $request->input('processed-by-departments');
        $orderBy = $request->input('order-by');
        $status = $request->input('status');
        $action = $request->input('action');
        //working
        // $getAllData = DB::table('requested_documents')
        //     ->select('requested_documents.purpose','requested_documents.status','requested_documents.created_at',
        //         'requested_documents.id', 'requested_documents.recieved_offices',
        //         'offices.office_name','offices.id as officeId',
        //         'users.id as userId','users.name','users.office_id',
        //     )
        //     ->leftJoin('offices','requested_documents.recieved_offices', '=', 'offices.id')
        //     ->leftJoin('users','offices.id', '=', 'users.office_id')
        //     ->where(function ($query) use ($trk, $from, $to, $office, $processedBy) {
        //         if (!empty($trk)) {
        //             $query->where('requested_documents.id', '=', $trk);
        //         }
        //         if (!empty($from)) {
        //             $query->where('requested_documents.created_at', '>=', $from. ' 00:00:00');
        //         }
        //         if (!empty($to)) {
        //             $query->where('requested_documents.created_at', '<=', $to. ' 23:59:59');
        //         }
        //         if (!empty($office)) {
        //             $query->where('requested_documents.recieved_offices', '=', $office);
        //         }
        //         if (!empty($processedBy)) {
        //             $query->where('users.id', '=', $processedBy);
        //         }
        //     })
        //     ->orderBy('requested_documents.created_at', $orderBy) // Apply the order-by condition
        //     ->get();

        // office not used
        switch ($action) {
            case 'per-tracking':
                $getAllData = DB::table('logs')
                    ->select(
                        'logs.*',
                        'users.id as userId',
                        'users.office_id as userofficeId',
                        'users.name',
                        'offices.id as officeId',
                        'offices.office_name'
                    )
                    ->leftJoin('users', 'logs.forwarded_to', '=', 'users.id')
                    ->leftJoin('offices', 'users.office_id', '=', 'offices.id')
                    ->where('logs.requested_document_id', '=', $trk)
                    //its for status fetching
                    // ->when($status !== '*', function ($query) use ($status) {
                    //     $query->where('logs.status', '=', $status);
                    // })
                    ->where('logs.created_at', '>=', $from . ' 00:00:00')
                    ->where('logs.created_at', '<=', $to . ' 23:59:59')
                    ->orderBy('logs.created_at', $orderBy) // Apply the order-by condition
                    ->get();
                break;

            case 'all-user':
                $getAllData = DB::table('requested_documents')
                    ->select(
                        'requested_documents.id',
                        'requested_documents.requestor_user',
                        'requested_documents.forwarded_to',
                        'requested_documents.pr',
                        'requested_documents.po',
                        'users.id as userId',
                        'users.office_id as userofficeId',
                        'users.name',
                        'offices.id as officeId',
                        'offices.office_name',
                        'logs.*'
                    )
                    ->leftJoin('users', 'requested_documents.requestor_user', '=', 'users.id')
                    ->leftJoin('offices', 'users.office_id', '=', 'offices.id')
                    ->leftJoin('logs', 'requested_documents.id', '=', 'logs.requested_document_id')
                    ->when($processedByDept !== '*', function ($query) use ($processedByDept) {
                        $query->where('offices.id', '=', $processedByDept);
                    })
                    ->when($status !== '*', function ($query) use ($status) {
                        $query->where('logs.status', '=', $status);
                    })
                    ->when($status === 'completed', function ($query) {
                        $query->where('logs.status', '=', 'completed');
                    })
                    ->when($from, function ($query) use ($from) {
                        $query->whereDate('logs.created_at', '>=', $from);
                    })
                    ->when($to, function ($query) use ($to) {
                        $query->whereDate('logs.created_at', '<=', $to);
                    })
                    ->orderBy('logs.created_at', $orderBy)
                    ->get();
        }
        // dd($getAllData);

        if (count($getAllData) > 0) {
            $pdfName = $this->generateReportDocuments2($getAllData, $from, $to);

            $saveReports = new Report();
            $saveReports->report_trk = $trk ?? 'not-generated';
            $saveReports->path = 'reports/' . $pdfName . '.pdf';
            $saveReports->save();

            // Prepare the toast notification data
            $notification = [
                'id' => $saveReports->id,
                'path' => 'reports/' . $pdfName . '.pdf',
                'modal' => true,
                'status' => 'success',
                'message' => 'Successfully generated reports!',
            ];
        } else {
            // Prepare the toast notification data
            $notification = [
                'id' => 0,
                'path' => 'reports/',
                'modal' => false,
                'status' => 'error',
                'message' => 'There is no record found!',
            ];
            // Handle the case where $getAllData is empty, e.g., return an error or a message.
            // You can use a conditional statement or raise an exception as needed.
        }
        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }
    // generate pdf
    function generatePdf($trk_id, $id, $name, $department, $date_created, $date_approved)
    {
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
        $pdf->Image(public_path('barcode/') . $trk_id . '.png', $barcodeImageX, $barcodeImageY, 50);

        // Reset font size for the value (smaller)
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -6, "TRK-" . $trk_id, 0, 1, 'C');

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
        $pdf->Cell(0, 30, 'Approved By:', 0, 1, 'C');
        // 
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(0, 0, 255); // RGB color
        $pdf->Cell(0, -15, $department . ' Office', 0, 1, 'C');

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

    // generate reports
    function generateReportDocuments($datas, $from, $to)
    {
        // dd($datas);
        $pdf = new GenerateTable('P', 'mm', 'A4'); //custom class for generating table
        // $fpdf = new Fpdf('P', 'mm', 'A4');
        $pdf->AddPage();
        $bgColor = 211; // Initial background color (gray)
        // Header
        $pdf->SetFont('Courier', 'B', 18);
        $pdf->Cell(0, 10, 'Document Tracking System' . ' Reports', 0, 1, 'C');
        $pdf->SetFont('Courier', '', 12);
        $pdf->Cell(0, 5, 'From: ' . $from . ' | To: ' . $to, 0, 1, 'C');
        $pdf->Ln(10);

        // get the title for next page
        // $pdf->getHeader($types);
        // $pdf->SetFont('Courier', '', 14);
        // $columnWidth = 190 / count($names); // Adjust this width as needed
        // foreach ($names as $name) {
        //     // Set the background color
        //     $pdf->SetFillColor(211, 211, 211);
        //     $pdf->Cell($columnWidth, 10, $name, 0, 0, 'C', true);
        // }


        // add heading
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->SetFillColor(192, 192, 192); // RGB values for gray
        $pdf->Cell(10, 5, 'ID', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Name', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'TRK-No', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Location', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Notes', 1, 0, 'C', true);
        $pdf->Cell(18, 5, 'Status', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Created', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Time', 1, 0, 'C', true);
        // Reset the background color
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(7); // Move to the next row

        $pdf->SetFont('Courier', '', 10);

        $pdf->SetWidths(array(10, 30, 25, 30, 30, 18, 25, 25)); //set width for each column (6)

        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetLineHeight(6); //hieght of each lines, not rows

        $json = file_get_contents(public_path('MOCK_DATA.json')); //read data
        $data = json_decode($json, true);

        foreach ($datas as $item) {
            //    dd($item);
            //write data using Row() methiod containing array of value
            //    $pdf->Row(Array(
            //         'TRK-'.$item['id'],
            //         $item['first_name'],
            //         $item['last_name'],
            //         $item['email'],
            //         $item['gender'],
            //         $item['address'],
            //    ));

            $pdf->Row(array(
                $item->id,
                $item->name,
                // $item->office_name,
                ($item->trk_id ? "TRK-" . $item->trk_id : "Not-Generated"), // Check if $item->trk_id is not null
                $item->current_location,
                $item->notes,
                $item->status,
                $item->created_at,
                $item->time_range,
            ));
        }

        // Output the PDF
        $uniqueId = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $pdf->Output('F', public_path('reports/') . $uniqueId . '.pdf');

        return $uniqueId;
    }

    function generateReportDocuments2($datas, $from, $to)
    {
        // dd($datas);
        $from2 = $from ?? now()->format('Y-m-d');
        $to2 = $to ?? now()->format('Y-m-d');

        $pdf = new GenerateTable('P', 'mm', 'A4'); //custom class for generating table
        // $fpdf = new Fpdf('P', 'mm', 'A4');
        $pdf->AddPage();
        $bgColor = 211; // Initial background color (gray)
        // Header
        $pdf->SetFont('Courier', 'B', 18);
        $pdf->Cell(0, 10, 'Document Tracking System' . ' Reports', 0, 1, 'C');
        $pdf->SetFont('Courier', '', 12);
        $pdf->Cell(0, 5, 'From: ' . $from2 . ' | To: ' . $to2, 0, 1, 'C');
        $pdf->Ln(10);

        // get the title for next page
        // $pdf->getHeader($types);
        // $pdf->SetFont('Courier', '', 14);
        // $columnWidth = 190 / count($names); // Adjust this width as needed
        // foreach ($names as $name) {
        //     // Set the background color
        //     $pdf->SetFillColor(211, 211, 211);
        //     $pdf->Cell($columnWidth, 10, $name, 0, 0, 'C', true);
        // }


        // add heading
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->SetFillColor(192, 192, 192); // RGB values for gray
        $pdf->Cell(30, 5, 'TRK-No', 1, 0, 'C', true);
        $pdf->Cell(20, 5, 'PR#', 1, 0, 'C', true);
        $pdf->Cell(20, 5, 'PO#', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Purpose', 1, 0, 'C', true);
        $pdf->Cell(30, 5, 'Status', 1, 0, 'C', true);
        // $pdf->Cell(18, 5, 'Status', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Created', 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Time', 1, 0, 'C', true);
        // Reset the background color
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(7); // Move to the next row

        $pdf->SetFont('Courier', '', 10);

        $pdf->SetWidths(array(30, 20, 20, 30, 30, 25, 25)); //set width for each column (6)

        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetLineHeight(6); //hieght of each lines, not rows

        $json = file_get_contents(public_path('MOCK_DATA.json')); //read data
        $data = json_decode($json, true);

        foreach ($datas as $item) {
            //    dd($item);
            //write data using Row() methiod containing array of value
            //    $pdf->Row(Array(
            //         'TRK-'.$item['id'],
            //         $item['first_name'],
            //         $item['last_name'],
            //         $item['email'],
            //         $item['gender'],
            //         $item['address'],
            //    ));

            $pdf->Row(array(
                ($item->trk_id ? "TRK-" . $item->trk_id : "Not-Generated"),
              
                // $item->office_name,
                ($item->pr ? $item->pr : "No PR"), 
                ($item->po ? $item->po : "No PO"),
                $item->current_location,
                $item->status,
                $item->created_at,
                $item->time_range,
            ));
        }

        // Output the PDF
        $uniqueId = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $pdf->Output('F', public_path('reports/') . $uniqueId . '.pdf');

        return $uniqueId;
    }
    // cancel report 
    public function cancelReports(Request $request)
    {
        // dd($request);
        $reportId = $request->input('id');

        // Get the report and its credentials
        $report = Report::find($reportId);
        $path = $report->path;

        $filename = public_path($path);
        if (file_exists($filename)) {
            unlink($filename);
            // Delete the report
            $report->delete();
            // Prepare the toast notification data
            $notification = [
                'id' => 0,
                'path' => 'reports/',
                'modal' => false,
                'status' => 'success',
                'message' => 'Successfully cancelled reports!',
            ];
        } else {
            $notification = [
                'id' => 0,
                'path' => 'reports/',
                'modal' => false,
                'status' => 'success',
                'message' => 'Successfully cancelled reports!',
            ];
        }

        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }
}
