<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Message;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get a message attachment by message ID
     * 
     * @param string $id Message ID
     * @return \Illuminate\Http\Response
     */
    public function getMessageAttachment($id)
    {
        // Find the message
        $message = Message::findOrFail($id);
        
        // Check if message has an attachment
        if (!$message->attachment) {
            return response()->json(['error' => 'No attachment found'], 404);
        }
        
        // Get the file path
        $filePath = $message->attachment;
        
        // Check if file exists in storage
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        // Get file information
        $fileName = $message->attachment_name ?: basename($filePath);
        $fileType = $message->attachment_type ?: $this->getMimeType($filePath);
        
        // Get file content
        $fileContent = Storage::get($filePath);
        
        // Return file url and metadata for the file viewer
        return response()->json([
            'success' => true,
            'url' => url('messaging/attachment/download/' . $id),
            'name' => $fileName,
            'type' => $fileType,
            'size' => Storage::size($filePath),
            'created_at' => $message->created_at->format('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Download a message attachment by message ID
     * 
     * @param string $id Message ID
     * @return \Illuminate\Http\Response
     */
    public function downloadMessageAttachment($id)
    {
        // Find the message
        $message = Message::findOrFail($id);
        
        // Check if message has an attachment
        if (!$message->attachment) {
            return response()->json(['error' => 'No attachment found'], 404);
        }
        
        // Get the file path
        $filePath = $message->attachment;
        
        // Check if file exists in storage
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        // Get file name
        $fileName = $message->attachment_name ?: basename($filePath);
        
        // Check if we should stream the file or download it
        $disposition = request()->has('view') ? 'inline' : 'attachment';
        
        // Stream the file
        return Storage::download($filePath, $fileName, [
            'Content-Type' => $message->attachment_type ?: $this->getMimeType($filePath),
            'Content-Disposition' => "$disposition; filename=\"$fileName\""
        ]);
    }
    
    /**
     * Preview a message attachment
     * 
     * @param string $id Message ID
     * @return \Illuminate\Http\Response
     */
    public function previewMessageAttachment($id)
    {
        // Find the message
        $message = Message::findOrFail($id);
        
        // Check if message has an attachment
        if (!$message->attachment) {
            return response()->json(['error' => 'No attachment found'], 404);
        }
        
        // Get the file path
        $filePath = $message->attachment;
        
        // Check if file exists in storage
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        // Determine appropriate action based on file type
        $fileType = $message->attachment_type ?: $this->getMimeType($filePath);
        
        // For images, PDFs, and text files, use inline disposition
        if (strpos($fileType, 'image/') === 0 || 
            $fileType === 'application/pdf' ||
            strpos($fileType, 'text/') === 0) {
            
            return $this->streamFile($filePath, $fileType, $message->attachment_name);
        }
        
        // For other files, redirect to download
        return redirect()->route('messaging.attachment.download', $id);
    }
    
    /**
     * Stream a file with appropriate headers
     * 
     * @param string $path
     * @param string $type
     * @param string|null $name
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamFile($path, $type, $name = null)
    {
        $fileName = $name ?: basename($path);
        
        $response = new StreamedResponse;
        $response->setCallback(function () use ($path) {
            $stream = Storage::readStream($path);
            fpassthru($stream);
            fclose($stream);
        });
        
        $disposition = 'inline';
        $response->headers->set('Content-Type', $type);
        $response->headers->set('Content-Disposition', "$disposition; filename=\"$fileName\"");
        $response->headers->set('Content-Length', Storage::size($path));
        
        return $response;
    }
    
    /**
     * Get MIME type for a file
     * 
     * @param string $path
     * @return string
     */
    protected function getMimeType($path)
    {
        // Get the storage path for the file
        $storagePath = Storage::path($path);
        
        // Try to get the MIME type
        $mime = File::mimeType($storagePath);
        
        return $mime ?: 'application/octet-stream';
    }
}
