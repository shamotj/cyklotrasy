<?php

require_once 'vendor/autoload.php';
require_once 'src/functions.php';

use GPXIngest\GPXIngest;

$dataDir = __DIR__ . '/data';
$templateDir = __DIR__ . '/src/templates';

// Load twig
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($templateDir);
$twig = new Twig_Environment($loader);

// Load tracks data
$tracks = loadTracks($dataDir);


// Control
if (isset($_GET['p'])) {
    $trackName = $_GET['p'];
    if (isset($tracks[$trackName])) {

        if ($tracks[$trackName]['gpx']) {
            // Load GPX and generate stats.
            $gpx = new \GPXIngest\GPXIngest();
            $gpx->enableExperimental('calcDistance');
            $gpx->enableExperimental('calcElevationGain');
            $gpx->loadFile($tracks[$trackName]['public_path'] . '/' . $tracks[$trackName]['gpx']);
            $gpx->ingest();
            $stats = $gpx->getStats('journey0');
            $elevationData = [];
            $dist = $idx = 0;
            // loop each segment
            foreach ($gpx->getTrackSegmentNames('journey0') as $segName) {
                foreach ($gpx->getSegment('journey0', $segName)->points as $point) {
                    $dist += (float) $gpx->jdist[$idx] * 0.3048; 
                    $elevationData[$dist] = $point->elevation;
                    $idx++;
                }
            }
        }

        echo $twig->render('track.html.twig', [
            'track' => $tracks[$trackName],
            'stats' => (isset($stats)) ? $stats : null,
            'elevationData' => $elevationData,
        ]);
    }
} else if (!empty($tracks)) {
    echo $twig->render('tracks.html.twig', [
        'tracks' => $tracks,
    ]);
}
