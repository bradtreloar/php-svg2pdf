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

  public function __construct(string $id, string $name, int $cyan, int $magenta, int $yellow, int $black) {
    $this->id = $id;
    $this->name = $name;
    $this->cyan = $cyan;
    $this->magenta = $magenta;
    $this->yellow = $yellow;
    $this->black = $black;
  }

  public function register() {
    \TCPDF_COLORS::$spotcolor[$this->id] = [
      $this->cyan,
      $this->magenta,
      $this->yellow,
      $this->black,
      $this->name,
    ];
  }

  public function addToPdf(&$pdf) {
    $pdf->AddSpotColor(
      $this->name,
      $this->cyan,
      $this->magenta,
      $this->yellow,
      $this->black
    );
  }

}