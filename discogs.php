
<?php


$apiKey = '16da3be2529107f998d5afa659464f55'; // Replace with your actual Last.fm API key
$musicBrainzApiKey = 'W8zEqATz-WLb5Zki_oZFeyUf4Pf_rvyC'; // Replace with your MusicBrainz API key

// Function to make API requests to Last.fm
function getLastFMAPI($method, $params) {
    global $apiKey;
    $params['api_key'] = $apiKey;
    $params['format'] = 'json';

    $url = 'http://ws.audioscrobbler.com/2.0/?method=' . $method . '&' . http_build_query($params);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Function to make API requests to MusicBrainz
function getMusicBrainzAPI($path, $params) {
    global $musicBrainzApiKey;
    $url = 'https://musicbrainz.org/ws/2' . $path . '?' . http_build_query($params);

    $options = [
        'http' => [
            'header' => "Authorization: Bearer " . $musicBrainzApiKey
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Set your MusicBrainz API lookup endpoint for albums
$albumLookupEndpoint = '/release-group/';

// Step 1: Get the top 10 artists
$topArtists = getLastFMAPI('chart.getTopArtists', ['limit' => 10]);

// Iterate through the top artists
foreach ($topArtists['artists']['artist'] as $artist) {
    echo 'Artist: ' . $artist['name'] . "\n";

    // Step 2: Get the top 3 albums for each artist
    $topAlbums = getLastFMAPI('artist.getTopAlbums', ['artist' => $artist['name'], 'limit' => 3]);

    // Iterate through the top albums for each artist
    foreach ($topAlbums['topalbums']['album'] as $album) {
        echo 'Album: ' . $album['name'] . "\n";
        echo 'Playcount: ' . $album['playcount'] . "\n";

        // Step 3: Get the MusicBrainz release group ID for the album
        $releaseGroupID = $album['mbid'];

        // Step 4: Use MusicBrainz API to retrieve release date
        $albumDetails = getMusicBrainzAPI($albumLookupEndpoint . $releaseGroupID, []);

        if (isset($albumDetails['first-release-date'])) {
            echo 'Release Date: ' . $albumDetails['first-release-date'] . "\n";
        }

        echo "\n";
    }
}
?>
