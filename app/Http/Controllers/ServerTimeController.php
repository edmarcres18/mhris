<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ServerTimeController extends Controller
{
    /**
     * Get the current server time in Asia/Manila timezone
     *
     * @return JsonResponse
     */
    public function getTime(): JsonResponse
    {
        // Set timezone to Asia/Manila
        $timestamp = Carbon::now('Asia/Manila');
        
        // Generate a hash of the timestamp for verification
        $hash = hash_hmac('sha256', $timestamp->toIso8601String(), config('app.key'));
        
        return response()->json([
            'timestamp' => $timestamp->toIso8601String(),
            'timezone' => 'Asia/Manila',
            'hash' => $hash,
            // Add formatted strings for verification
            'formatted' => [
                'date' => $timestamp->format('Y-m-d'),
                'time' => $timestamp->format('H:i:s'),
                'full' => $timestamp->format('Y-m-d H:i:s')
            ]
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Accept, X-Requested-With')
          ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Verify if a timestamp is valid and hasn't been tampered with
     *
     * @param string $timestamp
     * @param string $hash
     * @return JsonResponse
     */
    public function verifyTimestamp(string $timestamp, string $hash): JsonResponse
    {
        // Regenerate hash for the given timestamp
        $expectedHash = hash_hmac('sha256', $timestamp, config('app.key'));
        
        // Verify hash matches
        $isValid = hash_equals($expectedHash, $hash);
        
        // Parse the timestamp
        try {
            $parsedTime = Carbon::parse($timestamp)->setTimezone('Asia/Manila');
            $now = Carbon::now('Asia/Manila');
            
            // Check if timestamp is within reasonable range (5 minutes)
            $isRecent = $parsedTime->diffInMinutes($now) <= 5;
            
            return response()->json([
                'valid' => $isValid && $isRecent,
                'timestamp' => $timestamp,
                'timezone' => 'Asia/Manila',
                'server_time' => $now->toIso8601String(),
                'diff_minutes' => $parsedTime->diffInMinutes($now)
            ])->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
              ->header('Access-Control-Allow-Headers', 'Accept, X-Requested-With')
              ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'error' => 'Invalid timestamp format'
            ], 400)->header('Access-Control-Allow-Origin', '*')
                  ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
                  ->header('Access-Control-Allow-Headers', 'Accept, X-Requested-With')
                  ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }
    }
} 