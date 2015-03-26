<?php

kirbytext::$pre[] = function($kirbytext, $value) 
{
    
  $value = ktgrid_preprocess_col($value);
  $value = ktgrid_preprocess_row($value);
  
  
  return $value;
  
};

kirbytext::$post[] = function($kirbytext, $value) 
{
  
  preg_match_all("/^\s*<!--bs\|([[a-z]{2,3}]*?)(:([0-9]{1,2}))?(\+([0-9]{1,2}))?-->\s*$/m",$value,$matches,PREG_SET_ORDER);
  
  foreach($matches as $match) 
  {
   
    $replacement = $match[0];
    
    if (count($match) >= 2 && $match[1] === "row")
    {
      $replacement = '<div class="row">';
    }
    else if (count($match) >= 2 && $match[1] === "end")
    {
      $replacement = '</div>';
    }
    else if (count($match) >= 2 && $match[1] === "cf")
    {
      $replacement = '<div class="clearfix"></div>';
    }
    else if (count($match) >= 4 && !empty($match[1]) && is_numeric($match[3]))
    {
      $replacement = sprintf('<div class="col-%1$s-%2$d%3$s">', $match[1], $match[3],
                             count($match) >= 6 && is_numeric($match[5]) ? sprintf(" col-%s-offset-%d", $match[1], $match[5]) : "" );
    }
    else if (count($match) >= 6 && !empty($match[1]) && is_numeric($match[5]))
    {
      $replacement = sprintf('<div class="col-%1$s-offset-%2$d">', $match[1], $match[5]);                             
    }
    
    $value = str_replace($match[0], $replacement, $value);
    
  }
  
  return $value;
  
};

function ktgrid_preprocess_col($content) 
{

  while( preg_match("/(\s*--\s*col\s*(xs|sm|md|lg)?\s*([0-9]{1,2}\s*)?(\+([0-9]{1,2}))?\s*--\s*)(.*?)(\s*--\s*end\s*--\s*)/ms", $content, $match, PREG_OFFSET_CAPTURE) )
  {
      
    if (count($match) !== 8)
      return $content;

    $inside = $match[6][0];

    $mq_target = empty($match[2][0]) ? "md" : $match[2][0];
    $col_size = empty($match[3][0]) ? null : trim($match[3][0]);

    $offset = empty($match[5][0]) ? null : trim($match[5][0]);

    $replacement = sprintf("\n<!--bs|%s%s%s-->\n%s\n<!--bs|end-->\n",
                           $mq_target,
                           !is_null($col_size) ? ":$col_size" : "",
                           !is_null($offset)   ? "+$offset"   : "",
                           $inside);

    $content = substr_replace($content, $replacement, $match[0][1], strlen($match[0][0]));
    
  }
  
  return $content;
  
}

function ktgrid_preprocess_row($content)
{
  
  while ( preg_match("/(\s*--\s*row\s*(xs|sm|md|lg)?\s*([0-9\,\s]*)\s*--\s*)(.*?)(\s*--\s*end\s*--\s*)/ms", $content, $match, PREG_OFFSET_CAPTURE) ) 
  {
    if (count($match) !== 6)
      return $content;
      //what the heck happened here

    $inside = $match[4][0];
    
    $mq_target = empty($match[2][0]) ? "md" : $match[2][0];
    $columns = explode(",",$match[3][0]);
    if (!$columns || count($columns)<1)
      return $content;
      //not even sure if this is possible
    
    preg_match_all("/^\s*---\s*$/m",$inside,$sections,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
    if (count($sections)+1 !== count($columns))
      return $content;
      //number of sections don't match specified columns
    
    $replacement = "<!--bs|row-->\n<!--bs|{$mq_target}:".trim($columns[0])."-->\n" . $inside;
    
    foreach($sections as $index => $section) 
      $replacement = preg_replace("/{$section[0][0]}/m", "<!--bs|end-->\n<!--bs|{$mq_target}:".trim($columns[$index+1])."-->", $replacement, 1);    
                                  
    $replacement .= "\n<!--end-->\n<!--bs|end-->\n";
               
    $content = substr_replace($content, $replacement, $match[0][1], strlen($match[0][0]));
    
  }
  
  return $content;
  
}
