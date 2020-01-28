<?php 
use PHPUnit\Framework\TestCase;
use Svg2Pdf\Svg2Pdf;

final class Svg2PdfTest extends TestCase {
  public function testSvg2PdfConvert(): void {
    $svg_filepath = dirname(__FILE__) . "/fixture.svg";
    $pdf_filepath = dirname(__FILE__) . "/output.pdf";
    $svg2pdf = new Svg2Pdf();
    $svg2pdf->convert($svg_filepath, $pdf_filepath);
    $this->assertFileExists($pdf_filepath);
    unlink($pdf_filepath);
  }
  
  public function testSetSpotColor() {
    $svg2pdf = new Svg2Pdf();
    $svg2pdf->addSpotColor("testspotcolor", 0, 100, 0, 0, "TestSpotColor");
    $this->assertTrue(TRUE);
  }
  
  public function testSvg2PdfConvertWithSpotColor(): void {
    $svg_filepath = dirname(__FILE__) . "/fixture_spotcolor.svg";
    $pdf_filepath = dirname(__FILE__) . "/output_spotcolor.pdf";
    $svg2pdf = new Svg2Pdf();
    $svg2pdf->addSpotColor("cutcontour", 0, 100, 0, 0, "CutContour");
    $svg2pdf->convert($svg_filepath, $pdf_filepath);
    $this->assertFileExists($pdf_filepath);
    unlink($pdf_filepath);
  }
}
