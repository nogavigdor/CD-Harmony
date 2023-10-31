<?php
echo "hi";

$apiKey = '16da3be2529107f998d5afa659464f55';
//$SharedSecret='228c6d60ed834fa53eafa6dfeeea9021';
$apiUrl = 'http://ws.audioscrobbler.com/2.0/';

$params = [
    'method' => 'chart.getTopArtists',
    'api_key' => $apiKey,
    'format' => 'json',
    'limit' =>1000, // Adjust the limit as needed
];

$requestUrl = $apiUrl . '?' . http_build_query($params);
echo $requestUrl;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$artists = [];

if (isset($data['artists']['artist'])) {
    foreach ($data['artists']['artist'] as $artist) {
        $artists[] = $artist['name'];
    }
}

foreach($artists as $artist) {
    echo $artist .'<br>';
}


// Convert the data to a JSON object
$jsonData = json_encode($artists, JSON_PRETTY_PRINT);

// Define the file path to save the JSON data
$filePath = 'artists.json';

// Write the JSON data to the file
file_put_contents($filePath, $jsonData);

// Output a success message
echo 'Artist and album data saved to ' . $filePath;
?>

// Now, $artists will contain a list of popular artists.

