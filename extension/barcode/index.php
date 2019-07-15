<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class barcode_lib extends _init_lib
{
    private $font;
    public function __construct()
    {
        parent::__construct();
        require_once('class/BCGFont.php');
        require_once('class/BCGColor.php');
        require_once('class/BCGDrawing.php');
        require_once('class/BCGcode128.barcode.php');
    }
    public function font($font='',$size=12)
    {
        if($font){
            $this->font = new BCGFont($font,$size);
        }
        return $this->font;
    }

    public function create($txt='',$filename='',$scale=1,$thickness=30)
    {
        /*if($font){
            $this->font($font,$size);
        }*/
        $color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);
        $code = new BCGcode128();
        $code->setScale($scale); // Resolution
        $code->setThickness($thickness); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($this->font); // Font (or 0)
        $code->parse($txt); // Text
        $drawing = new BCGDrawing($filename, $color_white);
        $drawing->setBarcode($code);
        $drawing->draw();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }
}
?>