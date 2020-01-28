<?php
namespace Svg2Pdf;

class Svg2Pdf {

  /**
   * Spot colors
   * 
   * @var array $spotColors
   */
  protected $spotColors;

  public function convert(string $svg_filepath, 
                          string $pdf_filepath, 
                          string $title = "Converted SVG", 
                          string $creator = "bradtreloar/svg2pdf",
                          string $author = "bradtreloar/svg2pdf"): void {
    $box = $this->getBox($svg_filepath);
    $orientation = 'Portrait';
    if (floatval($box[0]) > floatval($box[1])) {
      $orientation = 'Landscape';
    }
    $pdf = new \TCPDF($orientation, 'mm', $box);
    if ($this->spotColors) {
      foreach ($this->spotColors as $id => $color) {
        \TCPDF_COLORS::$spotcolor[$id] = [
          $color['cyan'],
          $color['magenta'],
          $color['yellow'],
          $color['black'],
          $color['name'],
        ];
        $pdf->AddSpotColor(
          $color['name'],
          $color['cyan'],
          $color['magenta'],
          $color['yellow'],
          $color['black']
        );
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
    $pdf->ImageSVG($svg_filepath, $x=0, $y=0, $w=$box[0], $h=$box[1], $link='', $align='', $palign='', $border=0, $fitonpage=false);
    $pdf->Output($pdf_filepath, 'F');
  }

  public function addSpotColor(string $id, int $cyan, int $magenta, int $yellow, int $black, string $name): void {
    $this->spotColors[$id] = [
      'cyan' => $cyan,
      'magenta' => $magenta,
      'yellow' => $yellow,
      'black' => $black,
      'name' => $name,
    ];
  }

  protected function millimetres(string $value): string {
    if (strpos($value, "mm") !== FALSE) {
      return explode("mm", $value)[0];
    }
    if (strpos($value, "px") !== FALSE) {
      $pixels = floatval(explode("px", $value)[0]);
      $millimeters = $pixels * (25.4 / 72.0);
      return number_format($millimeters, 4);
    }
    throw new \Exception("Unrecognised number format $value");
  }

  protected function getBox(string $svg_filepath): array {
    $doc = new \DOMDocument();
    $doc->load($svg_filepath);
    $width = $doc->documentElement->getAttribute("width");
    $height = $doc->documentElement->getAttribute("height");
    return [
      $this->millimetres($width),
      $this->millimetres($height),
    ];
  }

}
