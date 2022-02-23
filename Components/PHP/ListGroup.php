<?php

namespace Component {

  class Listgroup extends \Core\Component {
    
    private string $title = '';
    private string $bubble = '';
    private string $url = '';

    public function __construct(public array $data) {
      parent::__construct($this);
    }

    public function title(string $title): object {
      $this->title = $title;
      return $this;
    }

    public function bubble(string $bubble): object {
      $this->bubble = $bubble;
      return $this;
    }

    public function url(string $url): object {
      $this->url = $url;
      return $this;
    }

    public function show() {
      return "
        <dia-listgroup :params='{
          data: ".json_encode($this->data).",
          title: \"{$this->title}\",
          bubble: \"{$this->bubble}\",
          url: \"{$this->url}\"
        }'></dia-listgroup>
      ";
    }
  
  }

}