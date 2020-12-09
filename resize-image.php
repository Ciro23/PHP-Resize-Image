<?php

/*
@param string $source image source path
@param string|null $shape image final shape (square, horizontal, vertical)
@return the image to create or false on failure
*/
function resizeImage($source, $shape = null) {

    // gets the image extension
    $imageExt = explode(".", $source);
    $imageExt = array_pop($imageExt);

    switch($imageExt) {
        case "jpg":
        case "jpeg":
            $image = imagecreatefromjpeg($source);
            break;

        case "png":
            $image = imagecreatefrompng($source);
            break;

        default:
            return false;
    }

    // size of the uploaded image
    $size = getimagesize($source);

    $source = $size;
    $offset = [0, 0];

    if ($shape == "square") {
        $newSize = [315, 315];

        if ($size[0] > $size[1]) {          // if the image is horizontal
            $offset[0] = ($size[0] - $size[1]) / 2;

            $source[0] = $size[1];
            $source[1] = $size[1];

        } else if ($size[0] < $size[1]) {   // if the image is vertical
            $offset[1] = ($size[1] - $size[0]) / 2;

            $source[1] = $size[0];
            $source[0] = $size[0];
        }
    } else if ($shape == "horizontal" || $shape == "vertical") {

        switch ($shape) {
            case "horizontal":
                $newSize = [594, 427];
                break;

            case "vertical":
                $newSize = [271, 500];
                break;
        }

        if ($size[0] / $size[1] > $newSize[0] / $newSize[1]) {         // if the image width is higher than the given aspect ratio
            $source[0] = $size[1] * ($newSize[0] / $newSize[1]);
            $source[1] = $size[1];

            $offset[0] = ($size[0] - $source[0]) / 2;

        } else if ($size[0] / $size[1] < $newSize[0] / $newSize[1]) {  // if the image height is higher than the given aspect ratio
            $source[0] = $size[0];
            $source[1] = $size[0] / ($newSize[0] / $newSize[1]);

            $offset[1] = ($size[1] - $source[1]) / 2;
        }

    } else {
        // if the image is larger than the given number (width) and no shape is selected, resizes the image keeping the aspect ratio
        $newSize[0] = 765;
        $source = $size;
        if ($size[0] > $newSize[0]) {
            $newSize[1] = $newSize[0] / ($size[0] / $size[1]);
        } else {
            $newSize = $size;
        }
    }

    // create a resized copy of the image
    if (!$newImage = imagecreatetruecolor($newSize[0], $newSize[1])) {
        return false;
    }
    if (!imagecopyresampled($newImage, $image, 0, 0, $offset[0], $offset[1], $newSize[0], $newSize[1], $source[0], $source[1])) {
        return false;
    }

    return $newImage;
}
