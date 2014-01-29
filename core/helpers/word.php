<?php

namespace core\helpers;

class Word {

  public static function singular($word)
  {
    return rtrim($word, "s");
  }

  public static function plural($word)
  {
    return $word.'s';
  }

}