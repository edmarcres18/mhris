<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    /**
     * Get media file like ringtones securely.
     * 
     * This method ensures files are accessible in both local and production environments
     * with proper caching and security headers.
     *
     * @param  string  $type
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getMediaFile($type, $filename)
    {
        // Define allowed media types
        $allowedTypes = ['sounds', 'images'];
        
        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }
        
        // Sanitize filename to prevent directory traversal
        $filename = basename($filename);
        
        // Get file path from public directory
        $path = public_path("{$type}/{$filename}");
        
        // Check if file exists
        if (!File::exists($path)) {
            abort(404);
        }
        
        // Get MIME type
        $mimeType = File::mimeType($path);
        
        // Prepare response with appropriate headers
        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');
        
        // Set cache control for better performance
        $response->setMaxAge(86400); // 1 day
        $response->setPublic();
        
        return $response;
    }
} 