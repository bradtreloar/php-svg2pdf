<?php
namespace Svg2Pdf;

class Svg2Pdf {

  /**
   * Spot colors
   * 
   * @var array $spotColors
   */
  protected $spotColors;

  /**
   * Converts an SVG file to a PDF.
   * 
   * @param $svg_filepath string
   *   The path to the SVG file.
   * @param $pdf_filepath string
   *   The path that the PDF file will be saved to.
   * @param $margin float
   *   The space to add around the SVG, in millimetres.
   * @param $title string
   *   The PDF title.
   * @param $creator string
   *   The PDF creator.
   * @param $author string
   *   The PDF author.
   */
  public function convert(string $svg_filepath, 
                          string $pdf_filepath, 
                          float $margin = 1,
                          string $title = "Converted SVG", 
                          string $creator = "bradtreloar/svg2pdf",
                          string $author = "bradtreloar/svg2pdf"): void {
    $svgBox = $this->getBox($svg_filepath);
    
    $orientation = 'Portrait';
    if (floatval($svgBox[0]) > floatval($svgBox[1])) {
      $orientation = 'Landscape';
    }
    $pdf = new \TCPDF($orientation, 'mm', [
      $svgBox[0] + ($margin * 2),
      $svgBox[1] + ($margin * 2),
    ]);
    if ($this->spotColors) {
      foreach ($this->spotColors as $spotColor) {
        $spotColor->register();
        $spotColor->addToPdf($pdf);
      }
    }
    $pdf->SetCreator($creator);
    $pdf->SetAuthor($author);
    $pdf->SetTitle($title);
    $pdf->SetMargins(0, 0);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->AddPage();
    // Remove bottom margin.
    $pdf->SetAutoPageBreak(TRUE, 0);
    $pdf->ImageSVG($svg_filepath, 
      $x=$margin, $y=$margin, $w=$svgBox[0], $h=$svgBox[1], 
      $link='', $align='', $palign='', $border=0, $fitonpage=false);
    $pdf->Output($pdf_filepath, 'F');
  }

  /**
   * Adds a spot color to be added to the PDF.
   * 
   * @param $id string
   *   The SVG-compliant name of the spot color.
   * @param $cyan int
   *   The percentage of cyan in the color.
   * @param $magenta int
   *   The percentage of magenta in the color.
   * @param $yellow int
   *   The percentage of yellow in the color.
   * @param $black int
   *   The percentage of black in the color.
   * @param $name string
   *   The actual name of the spot color. Defaults to $id if none given.
   */
  public function addSpotColor(string $id, int $cyan, int $magenta, int $yellow, int $black, string $name = ''): void {
    if (!$name) {
      $name = $id;
    }
    $this->spotColors[] = new SpotColor($id, $name, $cyan, $magenta, $yellow, $black);
  }

  /**
   * Converts an HTML unit into a unitless value in millimeters.
   * 
   * @param $value string
   *   The value to be read.
   * @return string
   *   The value in millimeters, without units.
   */
  protected function millimeters(string $value): string {
    if (strpos($value, "mm") !== FALSE) {
      return explode("mm", $value)[0];
    }
    $pixels = floatval(explode("px", $value)[0]);
    $millimeters = $pixels * (25.4 / 72.0);
    return number_format($millimeters, 4);
  }

  /**
   * Gets the width and height of an SVG.
   * 
   * @param $svg_filepath string
   *   The path to the SVG file.
   * @return string[]
   *   The width and height of the SVG.
   */
  protected function getBox(string $svg_filepath): array {
    $doc = new \DOMDocument();
    $doc->load($svg_filepath);
    $width = $doc->documentElement->getAttribute("width");
    $height = $doc->documentElement->getAttribute("height");
    return [
      $this->millimeters($width),
      $this->millimeters($height),
    ];
  }

}
