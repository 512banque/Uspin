<?php

include_once './tools/Tools.php';
require_once 'vendor/autoload.php';

// permet d'éviter que la fonction ne "plante" si votre texte à spinner est trop long
ini_set('pcre.backtrack_limit', 10000000);
ini_set('pcre.recursion_limit', 10000000);

/*
	Script créé par Harisseo (www.harisseo.com) et 512banque (www.deliciouscadaver.com)
	Vous avez le droit d'inclure ce script dans un projet commercial. Vous n'avez pas le droit de le vendre en l'état ou de le donner.
	Merci de ne simplement pas supprimer ces lignes de commentaire, ce ne serait pas correct de votre part. Gardez un bon esprit, jouez le jeu et amusez-vous avec ce script ;)
	Bon spin à tous !
*/

class UltimateSpinning{
  public $DELIMITER_L = '{';
  public $DELIMITER_R = '}';
  
  public $spinnableText='';
  public $spinnedText='';
  public $kw=array();
  
  function __construct($spinContent, $kw=array())
  {
    if($spinContent=='')
      {
        $this->error('Texte vide');
      }elseif(!is_string($spinContent))
      {
         $this->error('Entrez une chaine de caractère...');
      }else
      {
        $this->spinnableText = ($spinContent);
      }
          
    $this->checkMySpin();
    if(!is_array($kw))
      {
        $this->error('Entrez une chaine de caractère...');
      }else
      {
        $this->kw = $kw;
        $this->parse_kw();
      }
    
  }
  
  
  function checkMySpin()
  {

    if(substr_count($this->spinnableText, $this->DELIMITER_L)!=substr_count($this->spinnableText, $this->DELIMITER_R)) $this->error('Délimiteurs mal fermés');      

    return true;
  }
  
  
  function setSpinnedText($spinnedText)
  {
    if($spinnedText=='')
      {
        $this->error('Texte vide');
      }elseif(!is_string($spinnedText))
      {
         $this->error('Entrez une chaine de caractère...');
      }else
      {
        $this->spinnedText = ($spinnedText);
      }
  }
  
  
  function error($error, $exit=true)
  {
    echo('[!-ERROR-!]] '.$error.'<br>');
    if($exit) exit();
  }
  
  function howManyKeyWords($words, $detail=false)
  {
    if($words=='')
    {
      $this->error('Texte vide');
    }else
    {
      $all_words = explode('|', $words);
      $counter = 0;
      $words_detail = array();
      foreach($all_words as $key=>$word)
        {
          $counter+= substr_count($this->spinnedText, $word);
          if($detail)
          {
            $words_detail[$word] = $counter;
            $counter = 0;
          }
        }
    }
    
    if($detail) return $words_detail; else return $counter;
  }
    
  
  function spinIt()
  { 
   
    $pattern = '#\{([^{}]*)\}#msi';
    $test = preg_match_all($pattern, $this->spinnableText, $out);
    
    if (!$test) {
      $this->spinnedText = $this->spinnableText;
    
    }else
    {    
    $atrouver = array();
    $aremplacer = array();
    foreach($out[0] as $id => $match)
      {
        $choisir = explode("|", $out[1][$id]);
        $atrouver = trim($match);
        $aremplacer = trim($choisir[rand(0, count($choisir)-1)]);           
    		$this->spinnableText = str_replace_once($atrouver, $aremplacer, $this->spinnableText);
      }
  
    return $this->spinIt($this->spinnableText);
    }
	  //traitement du #
	  $this->spinnedText = $this->parse_sharp($this->spinnedText);
		//traitement des blocks
		$this->spinnedText = $this->parse_blocks($this->spinnedText);	  
		//traitement du bbcode
		$this->spinnedText = $this->bb_parse($this->spinnedText);

	  return $this->spinnedText; 
  }
  
  function parse_sharp($string) {
	 preg_match_all('`#([\d]+)#`siU', $string, $matches);
		$alea = $matches[1][array_rand($matches[1])];
		$string = preg_replace('`#'.$alea.'#(.+)#/'.$alea.'#`siU', '$1', $string);
		$string = preg_replace('`#[^'.$alea.']#(.+)#/[^'.$alea.']#`siU', '', $string);
		return $string;
	}
  
  
  function parse_kw(){
    if(empty($this->kw)) return;
    preg_match_all('#\[\*(\d+)\*\]#', $this->spinnableText, $matches);
    foreach($matches[1] as $key=>$value){
      $this->spinnableText = str_replace($matches[0][$key], trim($this->kw[$value]), $this->spinnableText);
    }
  }
    
  function bb_parse($string) {        
        while (preg_match_all('`\[((?!blocks).+?)=?(.*?)\](.+?)\[/\1\]`si', $string, $matches)) foreach ($matches[0] as $key => $match) {
            //echo '<pre>';print_r($matches);echo '</pre>';
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {
                case 'b': $replacement = "<strong>$innertext</strong>"; break;
                case 'i': $replacement = "<em>$innertext</em>"; break;
                case 'size': $replacement = "<span style=\"font-size: $param;\">$innertext</span>"; break;
                case 'comment': $replacement = "<!--".$innertext."-->"; break;
                case 'color': $replacement = "<span style=\"color: $param;\">$innertext</span>"; break;
                case 'center': $replacement = "<center>".$innertext."</center>"; break;
                case 'url': $replacement = '<a href="' . ($param? $param : $innertext) . "\">$innertext</a>"; break;
                case 'list': 
                  $list = explode(';', $innertext);
                  shuffle($list);
                    if(!isset($param) || $param==''){
                      $params = array("=>", "*", ">>", "->", "|->", "#", "~", ",");
                      $randkey = array_rand($params);
                      $param = $params[$randkey];
                    }
                  switch($param){                    
                    case 'li':
                      $stringf = '<ul>';
                      foreach($list as $key=>$elt){
                        $stringf .= '<li>'.trim($elt).'</li>'; 
                      }
                      $stringf .= '</ul>';  
                      $replacement = $stringf;
                    break;
                    case ',':
                    case ',+':
                      $stringf = '';
                      foreach($list as $key=>$elt){
                      
                      	if($param==',+'&&$z==0) {$param = ','; $stringf .= ucfirst(strtolower($elt)).$param.' '; }
                        elseif($z==count($list)-1) { $stringf = mb_substr($stringf,0,-2,'utf-8'); $stringf .= ' et '.strtolower($elt); } // avant-dernier 
                        elseif($z==count($list)) { $stringf .= strtolower($elt); } // dernier
												else { $stringf .= strtolower($elt).$param.' '; }
												$z++; 
                      }
                      $replacement = $stringf;
                    break;
                    default:
                      $stringf = '';
                      foreach($list as $key=>$elt){
                        $stringf .= $param.' '.$elt.' '.'<br>'; 
                      }
                      $replacement = $stringf;
                    break;
                   }  
                               
                break;
                case 'flickr':
                $f = new \Samwilson\PhpFlickr\PhpFlickr("c7db253c116bbd8633f044e94515e612", "61048141d5a74cbd");
                $f->enableCache("fs", "./tools/phpFlickr/cache"); 
                $photos = $f->photos_search(array("text"=>"$innertext", "per_page"=>250));
                $randkey = array_rand($photos['photo']);
                $replacement = '<img src="'.$f->buildPhotoURL($photos['photo'][$randkey], "medium").'" /><br>';
                $replacement .= $photos['photo'][$randkey]['title'];
                break;
                case 'blocks':
                  $list = explode('|', $innertext);
                  shuffle($list);
                  $stringf = '';                  
                  foreach($list as $key=>$elt){
                        $stringf .= $elt; 
                      }
                $replacement = $stringf;
                break;
                case 'img': 
                    $replacement = "<img src=\"$innertext\" />";
                break;
                case 'video':
                    $videourl = parse_url($innertext);
                    parse_str($videourl['query'], $videoquery);
                    if (strpos($videourl['host'], 'youtube.com') !== FALSE) $replacement = '<embed src="http://www.youtube.com/v/' . $videoquery['v'] . '" type="application/x-shockwave-flash" width="425" height="344"></embed>';
                    if (strpos($videourl['host'], 'google.com') !== FALSE) $replacement = '<embed src="http://video.google.com/googleplayer.swf?docid=' . $videoquery['docid'] . '" width="400" height="326" type="application/x-shockwave-flash"></embed>';
                break;
                default: if($param!=''){$replacement = '<'.$param.'>'.$innertext.'</'.$param.'>';}else{$replacement = $innertext;} break;
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
  }
  
  function parse_blocks(){ // a traiter après le spin
		$this->spinnedText = str_replace('[blocks]','{',$this->spinnedText);
		$this->spinnedText = str_replace('[/blocks]','}',$this->spinnedText);
		
		$pattern = '#\{([^{}]*)\}#msi';
		$test = preg_match_all($pattern, $this->spinnedText, $out);
		if (!$test) return $this->spinnedText;
		$atrouver = array();
		$aremplacer = array();
		foreach($out[0] as $id => $match)
		{
			$choisir = explode("|", $out[1][$id]);
			shuffle($choisir);
			$atrouver[] = trim($match);
			$aremplacer[] = trim(implode('',$choisir));
		}
		$this->spinnedText = str_replace($atrouver, $aremplacer, $this->spinnedText);
		return $this->parse_blocks($this->spinnedText);
	}
}

function str_replace_once($search, $replace, $subject) {
    $firstChar = strpos($subject, $search);
    if($firstChar !== false) {
        $beforeStr = substr($subject,0,$firstChar);
        $afterStr = substr($subject, $firstChar + strlen($search));
        return $beforeStr.$replace.$afterStr;
    } else {
        return $subject;
    }
}	


?>
