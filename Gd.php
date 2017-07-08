<?php
class Gd 
{
	private $width;
	private $height;
	private $text;
	private $color;

	public function __construct($width, $height, $text) {
		$this->width  = $width;
		$this->height = $height;
		$this->text   = $text;
	}

	public function generate() {
		$srcImg = __DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."ramka.jpg";
        $font=__DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."albionic.ttf";
        $orig  = imagecreatetruecolor($this->width, $this->height);
        $image = imageCreateFromJpeg($srcImg);
        imageFill($orig, 0, 0, imagecolorallocate($orig, 0, 255, 0));
        imagesettile($orig, $image);

        $textColor=imagecolorallocate($image, 123, 104, 238);
        $txt=explode("\n", $this->text);

        //$center_x = (int)$this->width / 2; 
       // $text_array = 
      /*  $px= (imageSX($image) - 4.5*strlen($text)) / 2;*/
/*
        imagettftext($image, 20, 0, 0, 0, $textColor, $font, $text);
*//*
        header('Content-type: image/png');
        imagePng($image);
        imagedestroy($image);
*/	}
}
?>