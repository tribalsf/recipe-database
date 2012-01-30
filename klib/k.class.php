<?


class k {

  public function hpr($obj, $return=false) {

    $output = '';

    if (PHP_SAPI != 'cli') {

    $output = <<<HTML
    <pre style="
      font-size: 12px;
      font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;
      color: #333;
      border: 1px solid #d0d0d0; 
      background-color: #efefef;
      border-radius: 5px;
      margin: 5px; 
      padding: 5px;
    ">
HTML;

  }

    ob_start();
    var_dump($obj);
    $output .= ob_get_contents();
    ob_end_clean();
    
    if (PHP_SAPI != 'cli') {
      $output .= '</pre>';
    }

    if ($return == true) {
      return $output;
    }

    echo $output;

  }

  public function highlight($text,$type='php',$nopre=false) {

    $text = preg_replace("/^\n/i", "", $text);

    $file = 'highlight'.rand(100,10000);
    file_put_contents('/tmp/'.$file,$text);
    $highlighted = shell_exec("highlight -f --style neon --syntax=$type < /tmp/$file");
    unlink('/tmp/'.$file);
    if ($nopre == false) {
      return str_replace("\n", '<br />', $highlighted);
    }

    return $highlighted;

  }

  public function cpr($code, $type='sql', $return=false, $br=false) {

    $data = null;

    if ($type == 'xml') {
      $code = xmlindent($code);
    }

    $highlighted  = highlight($code,$type);
    $highlighted = str_replace("\t", '&nbsp;&nbsp;', $highlighted);

    $data = <<<HTML
    <style type="text/css">
    .num  { color:#7D26Cd; }
    .esc  { color:#ff00ff; }
    .str  { color:#888; }
    .dstr { color:#818100; }
    .slc  { color:#838183; font-style:italic; }
    .com  { color:#838183; font-style:italic; }
    .dir  { color:#008200; }
    .sym  { color:#528B8B; }
    .line { color:#555555; }
    .kwa  { color:#222299; font-weight:bold; }
    .kwb  { color:#830000; }
    .kwc  { color:#000000; font-weight:bold; }
    .kwd  { color:#010181; }
    .kdebug_cpr {
      border-radius: 5px;
      border: 1px solid #e7e7e7;
      font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;
      font-size: 14px;
      margin: 5px;
      padding: 5px;
      background-color: #efefef;
    }

    </style>
    <div class="kdebug_cpr">
HTML;

    $data .= $highlighted;
    $data .= '</div>';

    if ($return == true) {
      return $data;
    }

    echo $data;

  }

  function xmliindent($xml) {  

    $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
    $token      = strtok($xml, "\n");
    $result     = '';
    $pad        = 0;
    $matches    = array();

    while ($token !== false) {
      if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches))
        $indent=0;
      elseif (preg_match('/^<\/\w/', $token, $matches)) 
        $pad--;
      elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches))
        $indent=1;
      else
        $indent = 0; 

      $line    = str_pad($token, strlen($token)+$pad, "\t", STR_PAD_LEFT);
      $result .= $line . "\n";
      $token   = strtok("\n");
      $pad    += $indent;
    }

    return $result;
  }

  function xmlindent($xml, $html_output=false) {  

      $xml_obj = new SimpleXMLElement($xml);  
      $level = 4;  
      $indent = 0;
      $pretty = array();  
        
      $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));  
    
      if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {  
        $pretty[] = array_shift($xml);  
      }  
    
      foreach ($xml as $el) {  
        if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {  
            $pretty[] = str_repeat("\t", $indent) . $el;  
            $indent += $level;  
        } else {  
          if (preg_match('/^<\/.+>$/', $el)) {              
            $indent -= $level;
          }  
          if ($indent < 0) {  
            $indent += $level;  
          }  
          $pretty[] = str_repeat("\t", $indent) . $el;  
        }  
      }     
      $xml = implode("\n", $pretty);     
      return ($html_output) ? htmlentities($xml) : $xml;  
  }  
    

}
