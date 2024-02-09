<?php

$apiKey = '16da3be2529107f998d5afa659464f55';
$apiUrl = 'http://ws.audioscrobbler.com/2.0/';



// Create an array to store the data for all artists and their albums
$artistData = [];

// Make an API request to get the top artists
$topArtistsUrl = $apiUrl . '?' . http_build_query([
    'method' => 'chart.getTopArtists',
    'api_key' => $apiKey,
    'format' => 'json',
    'limit' => 1000, // Adjust the limit as needed
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $topArtistsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$topArtistsResponse = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

curl_close($ch);

$topArtistsData = json_decode($topArtistsResponse, true);

if (isset($topArtistsData['artists']['artist'])) {
    $artists = [];
    foreach ($topArtistsData['artists']['artist'] as $topArtist) {
        $artistName = $topArtist['name'];

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

        $artistAlbumsResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        $artistAlbumsData = json_decode($artistAlbumsResponse, true);

        if (isset($artistAlbumsData['topalbums']['album'])) {
            $artistAlbums = [];
            foreach ($artistAlbumsData['topalbums']['album'] as $album) {
                // Fetch additional album details here
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
                
                $albumInfoResponse = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    echo 'Curl error: ' . curl_error($ch);
                }
                
                curl_close($ch);
                
                $albumInfoData = json_decode($albumInfoResponse, true);
                /*
                // Check if the keys exist before accessing them
              //  $releaseDate = isset($albumInfoData['album']['releasedate']) ? $albumInfoData['album']['releasedate'] : 'N/A';
              function extractReleaseDate($rawDate) {
                $matches = [];
                if (preg_match('/(\d+\s\w+\s\d{4})/', $rawDate, $matches)) {
                    return $matches[1];
                }
                return 'N/A';
            }*/
              $releaseDate = isset($albumInfoData['album']['wiki']['published']) ? $albumInfoData['album']['wiki']['published'] : 'N/A';

            

                $description = isset($albumInfoData['album']['wiki']['summary']) ? $albumInfoData['album']['wiki']['summary'] : 'N/A';
                $imageURL = isset($albumInfoData['album']['image'][3]['#text']) ? stripslashes($albumInfoData['album']['image'][3]['#text']) : 'N/A';
               
               /*
                if ($imageURL !== 'N/A') {
                    $imageContents = file_get_contents($imageURL);
                    if ($imageContents !== false) {
                        $imageFilePath = 'src/assets/images/albums/' . basename($imageURL); 
                        file_put_contents($imageFilePath, $imageContents);
                    }
                }
*/
                if ($imageURL !== 'N/A') {
                    $imageContents = file_get_contents($imageURL);
                    if ($imageContents !== false) {
                        $imageFilePath = 'src/assets/images/albums/' . basename($imageURL); 
                        file_put_contents($imageFilePath, $imageContents);
                    }
                }
               
                $tags = isset($albumInfoData['album']['tags']['tag']) ? $albumInfoData['album']['tags']['tag'] : [];
                // Extract only tag names
                /*
                $tagNames = [];
                foreach ($tags as $tag) {
                    $tagNames[] = $tag['name'];
                }
                */
               // $tracks = isset($albumInfoData['album']['tracks']['track']) ? $albumInfoData['album']['tracks']['track'] : [];
                
                // Simulate random values for the condition property
                $conditions = ['new', 'as new', 'very good', 'good', 'fair'];
                $condition = $conditions[array_rand($conditions)];
                
                // Create an album data array
                $albumData = [
                    'artist_name' => $artistName,
                    'album_name' => $albumName,
                    'condition' => $condition,
                    'release_date' => $releaseDate,
                    'description' => $description,
                  //  'tracks' => $tracks,
                
                    'edition_type' => $editionType,
                    'image_url' => $imageFilePath ,
                    'tags' => $tags
                ];
                
                $artistAlbums[] = $albumData;
            }

            // Store the artist's data
            $artists[$artistName] = $artistAlbums;
        }
    }
}

// Convert the data to a JSON object
$jsonData = json_encode($artists, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Define the file path to save the JSON data
$filePath = 'artists_and_albums.json';

// Write the JSON data to the file
file_put_contents($filePath, $jsonData);

// Output a success message
echo 'Artist and album data saved to ' . $filePath;

?>
