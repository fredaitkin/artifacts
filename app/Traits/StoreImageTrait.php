<?php
 
namespace Artifacts\Traits;
 
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
 
trait StoreImageTrait {
 
    /**
     * Saves an image to storage
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $name Image name
     * @param string $directory Directory to save the image to
     * @param array $resize Resize values for image
     */
    public function storeImage( \Illuminate\Http\UploadedFile $image, $name, $directory, $resize = [] ) {
        $img = Image::make($image->getRealPath());
        if (!empty($resize)):
            $img->resize($resize[0], $resize[1], function ($constraint) {
                $constraint->aspectRatio();
            });
        endif;
        $img->stream();
        Storage::disk('public')->put($directory . $name, $img);
    }
 
}