<?php

namespace Component {

  class Chart extends \Core\Component {

    private string $type = "";
    private string $label = "Chart";
    private string $borderWidth = "1";
    private array $labels = [];
    private array $backgroundColor = [
      'rgba(255, 99, 132, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(255, 206, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(255, 159, 64, 0.2)'
    ];
    private $borderColor = [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)',
      'rgba(255, 206, 86, 1)',
      'rgba(75, 192, 192, 1)',
      'rgba(153, 102, 255, 1)',
      'rgba(255, 159, 64, 1)'
    ];
    private $list = NULL;
    private int $width = 400;
    private int $height = 400;

    public function __construct(string $type) {
      parent::__construct($this);

      $this->type = $type;
    }

    public function labels(array $labels): object {
      $this->labels = $labels;
      return $this;
    }

    public function label(string $label): object {
      $this->label = $label;
      return $this;
    }

    public function borderWidth(string $borderWidth): object {
      $this->label = $borderWidth;
      return $this;
    }

    public function backgroundColor(array $backgroundColor): object {
      $this->backgroundColor = $backgroundColor;
      return $this;
    }

    public function borderColor(array $borderColor): object {
      $this->borderColor = $borderColor;
      return $this;
    }

    public function width(int $width): object {
      $this->width = $width;
      return $this;
    }

    public function height(int $height): object {
      $this->height = $height;
      return $this;
    }

    public function show() {
      return "
        <chart :params='{
            id: \"{$this->uid}\",
            height: {$this->height},
            width: {$this->width},
            type: \"{$this->type}\",
            labels: ".json_encode($this->labels).",
            data: ".json_encode($this->data).",
            label: \"{$this->label}\",
            uid: \"{$this->uid}\",
            borderWidth: \"{$this->borderWidth}\",
            backgroundColor: ".json_encode($this->backgroundColor).",
            borderColor: ".json_encode($this->borderColor)."
          }'
        ></chart>
      ";
    }

  }

}

?>