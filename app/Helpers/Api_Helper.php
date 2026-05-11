<?php

if (!function_exists('apiResponse')) {
    function apiResponse($status, $message, $data = null, $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}

if (!function_exists('apiResponse')) {
    function getRootCategory($category)
    {
        while ($category->parent_id) {
            $category = $category->parent;
        }

        return $category;
    }
}
