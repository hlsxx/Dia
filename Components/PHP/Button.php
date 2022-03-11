<?php


namespace Component;

class Button extends \Core\Component {

  private string $title = "";
  private string $button = "";
  private string $icon = "";
  private string $modalComponentToRender = "";
  private array $modalComponentToRenderParams = [];
  
  public function __construct(public string $tableName) {
    parent::__construct($this);
  }

  public function title(string $title) {
    $this->title = $title;
    return $this;
  }

  public function button(string $button) {
    $this->button = $button;
    return $this;
  }
  
  public function icon(string $icon) {
    $this->icon = $icon;
    return $this;
  }

  public function modalComponentToRender(string $modalComponentToRender) {
    $this->modalComponentToRender = $modalComponentToRender;
    return $this;
  }

  public function modalComponentToRenderParams(array $modalComponentToRenderParams = []) {
    $this->modalComponentToRenderParams = $modalComponentToRenderParams;
    return $this;
  }
  
  public function show() {
    return "
      <dia-button :params='{
        tableName: \"{$this->tableName}\",
        conditions: ".json_encode($this->conditions).",
        data: ".json_encode($this->data).",
        title: \"{$this->title}\",
        button: \"{$this->button}\",
        icon: \"{$this->icon}\",
        modalComponentToRender: \"{$this->modalComponentToRender}\",
        modalComponentToRenderParams: ".json_encode($this->modalComponentToRenderParams)."
      }'></dia-button>
    ";
  }

}
