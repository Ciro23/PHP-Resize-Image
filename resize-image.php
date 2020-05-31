<?php
function resizeImage($source, $imageExt, $shape = false) {

    if ($imageExt == "jpg" || $imageExt == "jpeg") {
        $image = imagecreatefromjpeg($source);

    } else if ($imageExt == "png") {
        $image = imagecreatefrompng($source);

    }

    // size of the uploaded image
    $size = getimagesize($source);

    $source = [];
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

        } else {
            $source[0] = $size[0];
            $source[1] = $size[0];
        }
    } else if ($shape == "horizontal" || $shape == "vertical") {

        switch ($shape) {
            case "horizontal":
                $newSize = [430, 242];
                break;

            case "vertical":
                $newSize = [271, 500];
                break;
        }

        if ($size[0] / $size[1] > $newSize[0] / $newSize[1]) {         // if the image width is bigger than the image aspect ratio
            $source[1] = $size[1];
            $source[0] = $size[1] * ($newSize[0] / $newSize[1]);

            $offset[0] = ($size[0] - $source[0]) / 2;

        } else if ($size[0] / $size[1] < $newSize[0] / $newSize[1]) {  // if the image height is bigger than image aspect ratio
            $source[0] = $size[0];
            $source[1] = $size[0] / ($newSize[0] / $newSize[1]);

            $offset[1] = ($size[1] - $source[1]) / 2;

        } else {
            $source[0] = $size[0];
            $source[1] = $size[1];
        }

    } else {
        // if the image is larger than the given number (width) and no shape is selected, resizes the image keeping the aspect ratio
        $newSize = [765];
        if ($size[0] > $newSize[0]) {
            $newSize[1] = $newSize[0] / ($size[0] / $size[1]);

            $source[0] = $size[0];
            $source[1] = $size[1];
        } else {
            $newSize = $size;
            $source = $size;
        }
    }

    // create a resized copy of the image
    $newImage = imagecreatetruecolor($newSize[0], $newSize[1]);
    imagecopyresampled($newImage, $image, 0, 0, $offset[0], $offset[1], $newSize[0], $newSize[1], $source[0], $source[1]);

    return $newImage;
}
?>
