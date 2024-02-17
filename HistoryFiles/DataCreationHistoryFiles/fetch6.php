<?php
$apiKey = '16da3be2529107f998d5afa659464f55'; // Replace with your Last.fm API key
$apiUrl = 'http://ws.audioscrobbler.com/2.0/';

// Function to get album tracks for a specific artist and album
function getAlbumTracks($artistName, $albumName, $apiKey, $apiUrl) {
    $albumTracks = [];

    $albumInfoUrl = $apiUrl . '?' . http_build_query([
        'method' => 'album.getTracks',
        'artist' => $artistName,
        'album' => $albumName,
        'api_key' => $apiKey,
        'format' => 'json',
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $albumInfoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $albumTracksResponse = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    $albumTracksData = json_decode($albumTracksResponse, true);

    if (isset($albumTracksData['tracks']['track'])) {
        foreach ($albumTracksData['tracks']['track'] as $track) {
            $trackName = $track['name'];
            $trackDuration = $track['duration'];

            // Add the track data to the album's tracks array
            $albumTracks[] = [
                'name' => $trackName,
                'duration' => $trackDuration,
            ];
        }
    }

    return $albumTracks;
}

// Create an array to store the data for all artists and their albums
$artistsData = [];
$multiCurl = curl_multi_init();
$curlHandles = [];

// Make an API request to get the top artists
$topArtistsUrl = $apiUrl . '?' . http_build_query([
    'method' => 'chart.getTopArtists',
    'api_key' => $apiKey,
    'format' => 'json',
    'limit' => 1, // Adjust the limit as needed
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $topArtistsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$curlHandles[] = $ch;
curl_multi_add_handle($multiCurl, $ch);

// Execute the multi-curl requests
$running = null;
do {
    curl_multi_exec($multiCurl, $running);
} while ($running > 0);

foreach ($curlHandles as $ch) {
    $topArtistsResponse = curl_multi_getcontent($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);

    $topArtistsData = json_decode($topArtistsResponse, true);

    if (isset($topArtistsData['artists']['artist'])) {
        foreach ($topArtistsData['artists']['artist'] as $topArtist) {
            $artistName = $topArtist['name'];

            // Create an array to store albums for the current artist
            $artistAlbums = [];

            // Make an API request to get the top albums for the artist
            $artistAlbumsUrl = $apiUrl . '?' . http_build_query([
                'method' => 'artist.getTopAlbums',
                'artist' => $artistName,
                'api_key' => $apiKey,
                'format' => 'json',
                'limit' => 10, // Adjust the limit as needed
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $artistAlbumsUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curlHandles[] = $ch;
            curl_multi_add_handle($multiCurl, $ch);

            // Execute the multi-curl requests for artist albums
            $running = null;
            do {
                curl_multi_exec($multiCurl, $running);
            } while ($running > 0);

            $artistAlbumsResponse = curl_multi_getcontent($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }

            curl_multi_remove_handle($multiCurl, $ch);
            curl_close($ch);

            $artistAlbumsData = json_decode($artistAlbumsResponse, true);

            if (isset($artistAlbumsData['topalbums']['album'])) {
                foreach ($artistAlbumsData['topalbums']['album'] as $album) {
                    $albumName = $album['name'];
                    $editionType = 'Standard'; // Define the $editionType variable here

                    // Include more API calls and data fetching for release date, description, and edition type here
                    $albumInfoUrl = $apiUrl . '?' . http_build_query([
                        'method' => 'album.getInfo',
                        'artist' => $artistName,
                        'album' => $albumName,
                        'api_key' => $apiKey,
                        'format' => 'json',
                    ]);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $albumInfoUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $curlHandles[] = $ch;
                    curl_multi_add_handle($multiCurl, $ch);

                    // Execute the multi-curl requests for album information
                    $running = null;
                    do {
                        curl_multi_exec($multiCurl, $running);
                    } while ($running > 0);

                    $albumInfoResponse = curl_multi_getcontent($ch);

                    if (curl_errno($ch)) {
                        echo 'Curl error: ' . curl_error($ch);
                    }

                    curl_multi_remove_handle($multiCurl, $ch);
                    curl_close($ch);

                    $albumInfoData = json_decode($albumInfoResponse, true);

                    $releaseDate = isset($albumInfoData['album']['wiki']['published']) ? $albumInfoData['album']['wiki']['published'] : 'N/A';
                    $description = isset($albumInfoData['album']['wiki']['summary']) ? $albumInfoData['album']['wiki']['summary'] : 'N/A';
                    $imageURL = isset($albumInfoData['album']['image'][2]['#text']) ? stripslashes($albumInfoData['album']['image'][2]['#text']) : 'N/A';

                    // Define $imageFilePath before the if condition
                    $imageFilePath = 'N/A';

                    if ($imageURL !== 'N/A' && !empty($imageURL)) {
                        $imageContents = file_get_contents($imageURL);

                        if ($imageContents !== false) {
                            $imageFilePath = 'src/assets/images/albums/fetch6/' . basename($imageURL);
                            file_put_contents($imageFilePath, $imageContents);
                        } else {
                            echo 'Failed to fetch image contents from URL: ' . $imageURL;
                        }
                    } else {
                        // Handle the case when no image is available
                        $imageFilePath = 'temp_image.jpg'; 
                    }

                    $tags = isset($albumInfoData['album']['tags']['tag']) ? $albumInfoData['album']['tags']['tag'] : [];

                    // Simulate random values for the condition property
                    $conditions = ['new', 'as new', 'very good', 'good', 'fair'];
                    $condition = $conditions[array_rand($conditions)];

                    // Get album tracks
                    $albumTracks = getAlbumTracks($artistName, $albumName, $apiKey, $apiUrl);

                    // Create an album data array
                    $albumData = [
                        'artist_name' => $artistName,
                        'album_name' => $albumName,
                        'condition' => $condition,
                        'release_date' => $releaseDate,
                        'description' => $description,
                        'edition_type' => $editionType,
                        'image_url' => $imageFilePath,
                        'tags' => $tags,
                        'tracks' => $albumTracks,
                    ];

                    // Add the album data to the artist's albums array
                    $artistAlbums[] = $albumData;
                }
            }

            // Store the artist's album data in the main data array
            $artistsData[$artistName] = $artistAlbums;
        }
    }
}

// Convert the data to a JSON object
$jsonData = json_encode($artistsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Define the file path to save the JSON data
$filePath = 'fetch6.json';

// Write the JSON data to the file
file_put_contents($filePath, $jsonData);

// Output a success message
echo 'Artist and album data saved to ' . $filePath;
?>
