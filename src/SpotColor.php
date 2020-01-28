<?php
namespace Svg2Pdf;

class SpotColor {

  /**
   * The SVG-compliant name of the spot color.
   * 
   * @var string $id
   */
  protected $id;

  /**
   * The actual name of the spot color.
   * 
   * @var string $name
   */
  protected $name;

  /**
   * The percentage of cyan in the color.
   * 
   * @var int $cyan
   */
  protected $cyan;

  /**
   * The percentage of magenta in the color.
   * 
   * @var int $magenta
   */
  protected $magenta;

  /**
   * The percentage of yellow in the color.
   * 
   * @var int $yellow
   */
  protected $yellow;

  /**
   * The percentage of black in the color.
   * 
   * @var int $black
   */
  protected $black;

  /**
   * @param $id string
   *   The SVG-compliant name of the spot color.
   * @param $name string
   *   The actual name of the spot color.
   * @param $cyan int
   *   The percentage of cyan in the color.
   * @param $magenta int
   *   The percentage of magenta in the color.
   * @param $yellow int
   *   The percentage of yellow in the color.
   * @param $black int
   *   The percentage of black in the color.
   */
  public function __construct(string $id, string $name, int $cyan, int $magenta, int $yellow, int $black) {
    $this->id = $id;
    $this->name = $name;
    $this->cyan = $cyan;
    $this->magenta = $magenta;
    $this->yellow = $yellow;
    $this->black = $black;
  }

  /**
   * Adds this color to TCPDF's list of spot colors so that it can be
   * identifed by it's lowercase name found in the svg.
   */
  public function register(): void {
    \TCPDF_COLORS::$spotcolor[$this->id] = [
      $this->cyan,
      $this->magenta,
      $this->yellow,
      $this->black,
      $this->name,
    ];
  }

  /**
   * Adds this color to a PDF document.
   * 
   * @param $pdf \TCPDF
   *   The TCPDF object.
   */
  public function addToPdf(&$pdf): void {
    $pdf->AddSpotColor(
      $this->name,
      $this->cyan,
      $this->magenta,
      $this->yellow,
      $this->black
    );
  }

}