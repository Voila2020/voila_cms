<?php

namespace crocodicstudio\crudbooster\pdf;

use TCPDF;
use TCPDF_FONTS;

class PDF extends TCPDF
{
    public $page_title, $page_language, $background_img, $font_name, $header_function, $footer_function;

    public function setMyHeader(callable $header_function)
    {
        $this->header_function = $header_function;
    }

    public function setMyFooter(callable $footer_function)
    {
        $this->footer_function = $footer_function;
    }

    public function setDirection($page_language)
    {
        $this->page_language = $page_language;
        if ($this->page_language == 'rtl') {
            $lg = array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'ar';
            $lg['w_page'] = 'page';

            // set some language-dependent strings (optional)
            $this->setLanguageArray($lg);
            $this->setRTL(true);
        }
    }

    public function getFont($fontFile)
    {
        return TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
    }

    public function Header()
    {
        if ($this->header_function) ($this->header_function)();
        # example
        // $this->SetAutoPageBreak(false, 0);
        // $background_img = $_SERVER["DOCUMENT_ROOT"] . 'image/path...';
        // $this->Image($background_img, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        // $this->SetAutoPageBreak(true, 10);
        // $fontFile = $_SERVER["DOCUMENT_ROOT"] . "font/path....";
        // $fontname = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
        // $this->SetFont($fontname, '', 20, '', false);
        // $this->Cell(0, 0, $page_title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer()
    {
        if ($this->footer_function) ($this->footer_function)();
        # example
        // $this->setY(-9);
        // $imageX = 0; //
        // $footer_img = $_SERVER["DOCUMENT_ROOT"] . 'image/path...';
        // $img_width = 210; // 15mm
        // $img = $this->Image($footer_img, $imageX, $this->GetY(), $img_width);
        // $this->Cell(0, 0, $img, 0, 0, 'C');
    }
}
