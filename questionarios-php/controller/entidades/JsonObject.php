<?php
interface JsonObject
{
 /**
  * Cria um objeto a partir de um Json
  */
 public function fromJson($json);

 /**
  * Gera um Json a partir de um objeto
  */
 public function toJson() : array;
}
?>