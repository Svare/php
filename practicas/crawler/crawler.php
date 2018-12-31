<?php

# Jesús Enrique Pacheco Franco
# 31/Diciembre/2018 4:51 pm
# UNAM - CERT crawler

  class Crawler {

    public $visitedURLs;
    public $bufferURLs;
    public $pendingURLs;
    public $document;

    public $depth;
    public $rootURL;

    public function __construct($rootURL, $depth) {

      $this->rootURL = $rootURL;

      if($depth >= 0)
        $this->depth = $depth;
      else
        exit("La profundidad debe de ser mayor o igual que 0. <br/>");

      $this->visitedURLs = array();
      $this->bufferURLs = array();
      $this->pendingURLs = array();

      $this->document = new DOMDocument('1.0');

    }

    public function absolutURL($currentURL) {

      #if(!$this->bufferURLs)
        #exit("Error no hay urls en el buffer.");

      while($this->bufferURLs) {

        $url = array_pop($this->bufferURLs);

        if( $url ==  "" || $url[0] == "#" || ( $url[0] == "/" && strlen($url) == 1) ) {
          // pass
        } elseif( $url[0] == "/" && strlen($url) > 1 ) {
          array_push($this->pendingURLs, $currentURL.substr($url, 1));
        } elseif( strpos($url, $currentURL) === 0 ) {
          array_push($this->pendingURLs, $url);
        } else {
          // url que no es hija del sitio pero que sin embargo se encuentra ahí
        }

      } // Fin while

    } // Fin absolutURL

    public function init() {

      $this->document->loadHTMLFile($this->rootURL);
      $webAddresses = $this->document->getElementsByTagName('a');

      foreach($webAddresses as $webAddress) {
        array_push($this->bufferURLs, $webAddress->getAttribute('href'));
      }

      array_push($this->visitedURLs, $this->rootURL); // Funcion que las meta en orden chido

      // Funcion que pasa a pendingURLs como urls absolutas

      $this->absolutURL($this->rootURL);

      var_dump($this->pendingURLs);

      $this->crawl();

    }

    public function crawl() {

      if($this->depth > 0) {

        $this->depth -= 1;

        $absolutURLs = $this->pendingURLs;
        $this->pendingURLs = array();

        while($absolutURLs) {

          $url = array_pop($absolutURLs);

          $this->document->loadHTMLFile($url);
          $webAddresses = $this->document->getElementsByTagName('a');

          foreach($webAddresses as $webAddress) {
            array_push($this->bufferURLs, $webAddress->getAttribute('href'));
          }

          array_push($this->visitedURLs, $url);

          $this->absolutURL($url);

        }

        $this->crawl();

      } else { // Entonces depth es igual a 0 y terminamos

        $this->visitedURLs = array_unique($this->visitedURLs);
        asort($this->visitedURLs);
        $this->visitedURLs = array_values($this->visitedURLs);

        $this->printTree();
      }

    }

    public function printTree() {

      $tabNum = 0;

      echo $this->visitedURLs[0]."<br/>";

      for ($i = 1; $i < count($this->visitedURLs) - 1 ; $i++) {
        for ($j=0; $j < $i; $j++) {
          if( strpos($this->visitedURLs[$i], $this->visitedURLs[$j]) === 0 )
            $tabNum++;
        }

        echo str_repeat("------", $tabNum).$this->visitedURLs[$i]."<br/>";

        $tabNum = 0;

      }
    }

  }

  # Primer argumento url segundo argumento profundidad

  $crawler = new Crawler("https://www.unam.mx/", 1);
  $crawler->init();


?>
