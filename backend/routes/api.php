<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', function() { return response()->json(['token' => 'fake']); });
Route::post('/auth/logout', function() { return response()->json(['message' => 'ok']); });
Route::post('/auth/forgot-password', function() { return response()->json(['message' => 'ok']); });
Route::post('/auth/reset-password', function() { return response()->json(['message' => 'ok']); });
Route::get('/auth/me', function(Request $request) { return $request->user(); })->middleware('auth:sanctum');
Route::get('/admin/users', function() { return response()->json(['data' => []]); })->middleware('auth:sanctum');
