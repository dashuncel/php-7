<?php
class Gd 
{
	private $width;
	private $height;
	private $text;
	private $color;

	public function __construct($width, $height, $text, $color) {
		$this->width  = $width;
		$this->height = $height;
		$this->text   = $text;
		$this->color  = $color;
	}

	public function generate {
		$srcImg = __DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."ramka.jpg";
        $font=__DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."albionic.ttf";
        $image = imageCreate($this->width, $this->height);
        $image = imageCreateFromJpeg($srcImg);

        //$center_x = (int)$this->width / 2; 
       // $text_array = 
      /*  $px= (imageSX($image) - 4.5*strlen($text)) / 2;*/

        $backColor=imagecolorallocate($image, 200, 225, 225);
        imagettftext($image, $px, 0, 0, 0, $textColor, $font, $text);
        header('Content-type: image/png');
        imagePng($image);
        imagedestroy($image);
	}
}
?>