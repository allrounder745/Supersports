<?php
// List of allowed origins (replace with your allowed domains)
$allowedOrigins = [
    "https://shwe7ank.netlify.app"
];

// Get the origin of the request
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Check if the request origin is allowed
if (in_array($requestOrigin, $allowedOrigins)) {
    // Allow the request by setting the CORS header
    header("Access-Control-Allow-Origin: " . $requestOrigin);
} else {
    // Deny the request
    http_response_code(403); // Forbidden
    echo json_encode([
        "error" => "Chutiye khud ka script bna. Jada load na de mere pe"
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

// Set the content type to JSON
header("Content-Type: application/json");

// Function to fetch content and extract the file URL
function fetchFileUrl($url, $id) {
    // Fetch the content using file_get_contents
    $content = @file_get_contents($url);

    // Check if content was fetched successfully
    if ($content === FALSE) {
        // Log the error
        error_log("Failed to fetch content from: " . $url);

        // Return an error message
        return [
            "id" => $id,
            "error" => "Unable to fetch content from the URL."
        ];
    }

    // Extract the file URL using regex
    $fileUrlMatch = [];
    preg_match('/file:\s*"([^"]+)"/', $content, $fileUrlMatch);

    // Check if the file URL was found
    if (empty($fileUrlMatch[1])) {
        // Log the error
        error_log("No file URL found in the content for: " . $id);

        // Return an error message
        return [
            "id" => $id,
            "error" => "No file URL found in the content."
        ];
    }

    // Return the ID and file URL
    return [
        "id" => $id,
        "file" => $fileUrlMatch[1]
    ];
}

// Array of URLs and their corresponding IDs
$sources = [
    [
        "url" => "https://play.denver1769.fun/Play/v18/cache/sp2_ios.php",
        "id" => "sp2_ios"
    ],
    [
        "url" => "https://play.denver1769.fun/Play/v18/cache/sp2.php",
        "id" => "sp2"
    ],
    [
        "url" => "https://play.denver1769.fun/Play/v18/cache/SP_sd.php",
        "id" => "SP_sd"
    ]
];

// Fetch file URLs for all sources
$results = [];
foreach ($sources as $source) {
    $results[] = fetchFileUrl($source["url"], $source["id"]);
}

// Output the JSON response without escaping slashes
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
