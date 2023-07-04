<?php

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function fileNameTopath($filename)
{
  $dividedFileName = explode($filename,'_');
  echo $dividedFileName;
  return $filepath;
}

