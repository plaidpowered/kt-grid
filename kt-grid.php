<?php

kirbytext::$pre[] = function($kirbytext, $value) 
{
    
  preg_match_all("/^\s*--\s*row\s*(xs|sm|md|lg)?\s*([\w\,\s]*)\s*--\s*$/m",$value,$row_matches,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
  preg_match_all("/^\s*---\s*$/m",$value,$section_matches,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
  preg_match_all("/^\s*--\s*end\s*--\s*$/m",$value,$end_matches,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
  
  $value_length = strlen($value);
  //$global_offset = 0;
    
  foreach($row_matches as $row)
  {
    $beginning = $row[0][1];
    
    $end = null;
    $offset = $beginning;    
    while($offset <= $beginning && count($end_matches)) 
    {
      $end = array_shift($end_matches);
      $offset = $end[0][1];
    }
        
    $sections = [];
    $offset = $beginning;
    while($offset < $end[0][1] && count($section_matches))     
    {      
      $section = array_shift($section_matches);
      if ($section[0][1] > $beginning)
        $sections[] = $section;
      $offset = $section[0][1];
    }
        
    $grid_sizes = explode(",",$row[2][0]);
    $grid_target = empty($row[1][0]) ? "md" : $row[1][0];
    
    if (is_null($end) || count($grid_sizes)-1 !== count($sections))
      return $value; 
      // something is broken
        
    $value = preg_replace("/{$row[0][0]}/m", "<!--bs|row-->\n<!--bs|{$grid_target}:".trim($grid_sizes[0])."-->", $value, 1);
    foreach($sections as $index => $section)
      $value = preg_replace("/{$section[0][0]}/m","<!--bs|end-->\n<!--bs|{$grid_target}:".trim($grid_sizes[$index+1])."-->",$value,1);
    $value = preg_replace("/{$end[0][0]}/m","<!--bs|end-->",$value,1);
    
    //$global_offset = strlen($value) - $value_length;
  }
  
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
    else if (count($match) >= 4 && !empty($match[1]) && is_numeric($match[3]))
    {
      $replacement = sprintf('<div class="col-%1$s-%2$d%3$s">', $match[1], $match[3],
                             count($match) >= 6 && is_numeric($match[5]) ? sprintf("col-%s-offset-%d", $match[1], $match[5]) : "" );
    }
    else if (count($match) >= 6 && !empty($match[1]) && is_numeric($match[5]))
    {
      $replacement = sprintf('<div class="col-%1$s-offset-%2$d">', $match[1], $match[5]);                             
    }
    
    $value = str_replace($match[0], $replacement, $value);
    
  }
  
  return $value;
  
};