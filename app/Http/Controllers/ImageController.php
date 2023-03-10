<?php

namespace App\Http\Controllers;

use Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function getImage(Request $request, $size) {
        $filename = $request->filename;

        // Make a new response out of the contents of the file
        // We refactor this to use the image resize function.
        // Set the response status code to 200 OK
        $response = Response::make(
            Image::resize($filename, $size),
            200
        );

        // Set the mime type for the response.
        // We now use the Image class for this also.
        $response->header(
            'content-type',
            Image::getMimeType($filename)
        );

        // We return our image here.
        return $response;
    }
}
