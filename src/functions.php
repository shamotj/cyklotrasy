<?php

function readTrackData($path, $res) {
    $data = [];
    $data['path'] = $path;
    $data['basename'] = basename($path);
    $data['public_path'] = 'data/' . $data['basename'];
    $data['ctime'] = filectime($path);

    $files = scandir($path);
    foreach ($files as $file) {
        
        // Read description
        if (strtolower($file) === 'readme.md') {
            $parser = new Parsedown();
            $data['description'] = $parser->text(file_get_contents($path . '/' . $file));
        }

        // Read name
        if (strtolower($file) === 'name.txt') {
            $data['name'] = file_get_contents($path . '/' . $file);
        }

        // Images
        if (preg_match('/\.jpg/i', $file)) {
            if (!isset($data['images'])) $data['images'] = [];
            $data['images'][] = $file;
        }

        // GPX
        if (preg_match('/\.gpx/i', $file)) {
            $data['gpx'] = $file;
        }
    }

    return $data;
}

function loadTracks($dir) {
    $tracks = [];
    if ($res = opendir($dir)) {
        while (($file = readdir($res)) !== false) {
            if (is_dir($dir . '/' . $file) && $file !== '.' && $file !== '..') {
                $tracks[$file] = readTrackData($dir . '/' . $file, $res);
            }
        }
        closedir($res);
    }

    return $tracks;
}

