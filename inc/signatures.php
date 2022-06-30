<?

/* ---------------------------------------------
*  News management module
*  Easy news management class
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/*
	Thanks to Davidejones.com for the sample code
 */

class ImgMgt {

	public function __construct($imagefolder)
	{
		global $config;
		$this->imf = $config['context']['path'].'/'.$imagefolder.'/';
	}

	public function idCard($player)
	{
		header('Content-Type: image/png'); 
		$image = imagecreatefrompng($this->imf.'idcard.png');

		$white = imagecolorallocate($image, 255, 255, 255);
		$whitesemi  = imagecolorallocatealpha($image, 255, 255, 255, 60);
		$black = imagecolorallocate($image, 0, 0, 0);
		$red = imagecolorallocate($image, 165,10,10);

		imagefilledrectangle($image, 0, 0, 600, 20, $whitesemi);

		$font = 2;
		imagestring($image, $font, 25, 4, 'Player Name: ', $black); 
		imagestring($image, $font, 100, 4, 'Invisible Man', $red);
		imagestring($image, $font, 200, 4, 'Rank: ', $black);
		imagestring($image, $font, 235, 4, '1', $red);
		imagestring($image, $font, 260, 4, 'Points:', $black);
		imagestring($image, $font, 305, 4, '999999',$red);
		imagestring($image, $font, 360, 4, 'Kills:', $black);
		imagestring($image, $font, 400, 4, '99999999999999', $red);
		imagestring($image, $font, 500, 4, 'Deaths:', $black);
		imagestring($image, $font, 550, 4, 'nevar!', $red);

		$src = imagecreatefrompng('./images/flags/gb.png');
		imagecopymerge($image, $src, 5, 5, 0, 0, 16, 11, 75);

		$src1 = imagecreatefrompng('./images/tf2.png');
		imagecopymerge_alpha($image, $src1, 5, 280, 0, 0, 74, 15, 75);

		imagepng($image);
		imagedestroy($image);
	}

	public function signature($player)
	{

	}

	public function getIdCard($player)
	{

	}

	public function getSignature($player)
	{
		
	}
}