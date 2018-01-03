<?php


echo '<!doctype html>';

if( $this->elem("html")->attr() ){

    $attributes = "";
    foreach ($this->elem("html")->attr() as $key => $value) {
        $attributes .= " {$key}=\"{$value}\"";
    }

    echo '<html'.$attributes.'>';
}
else{
    echo '<html>';
}

echo '<head>';

// Page title
/*if( $this->pageTitle=='Index' ){
    $this->pageTitle = $this->pageSiteName;
}*/

// Page title
echo '<title id="pageTitle">'. $this->getPage('title') .'</title>';
echo '<meta charset="utf-8" />';

/* set Touch Zooming  */
if( $this->fn->check_user_agent('mobile') ){

    $this->elem('body')->addClass('touch');
    echo '<meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1">';
    // echo '<link rel="mask-icon" href="'.IMAGES.'favicon.svg">';

}
echo '<link rel="shortcut icon" href="'.IMAGES.'favicon.png">';
$_content = $this->getPage('color');
if( !empty($color) ){
    echo '<meta name="theme-color" content="'.$_content.'">';
}


/* Set Meta SEO */
$og = array(0=>'title','url','site_name', 'description', 'keywords', 'image');
foreach ($og as $i => $val) {
    $_content = $this->getPage($val);
    
    if( !empty($_content) ){
        echo '<meta name="'.$val.'" content="'.$_content.'">';
    }
}


/* Set Meta Twitter SEO */
$og = array(0=>'card','title','site','creator', 'description', 'image', 'domain');
foreach ($og as $i => $val) {
    $_content = $this->getPage($val);
    
    if( !empty($_content) ){
        echo '<meta name="twitter:'.$val.'" content="'.$_content.'">';
    }
}

/* Set Meta Google SEO */
$og = array(0=>'title','site_name', 'url', 'description','type', 'keywords', 'locale', 'image');
foreach ($og as $i => $val) {
    $_content = $this->getPage($val);
    
    if( !empty($_content) ){
        echo '<meta property="og:'.$val.'" content="'.$_content.'">';
    }
}

$og = array(0=>'type','width', 'height');
// Set Meta Image
foreach ($og as $i => $val) {
    $_content = $this->getPage("image_{$val}");
    
    if( !empty($_content) ){
        echo '<meta property="og:image:'.$val.'" content="'.$_content.'">';
    }
}


// Set Meta Facebook
$_content = $this->getPage('facebook_app_id');
if( !empty($_content) ){
    echo '<meta name="fb:app_id" content="'.$_content.'">';
    echo '<meta name="article:author" content="https://www.facebook.com/'.$_content.'">';
    echo '<meta name="article:publisher" content="https://www.facebook.com/'.$_content.'">';

    // fb.me
}

// echo '<meta name="mobile-web-app-capable" content="yes">';
// echo '<meta name="apple-mobile-web-app-capable" content="yes">';
// echo '<meta name="apple-mobile-web-app-status-bar-style" content="black">';
// echo '<meta name="apple-mobile-web-app-title" content="'.$this->getPage('title').'">';

echo '<link rel="apple-touch-icon" href="'.IMAGES.'/logo/25x25.jpg" />';
echo '<link rel="apple-touch-icon" sizes="180x180" href="'.IMAGES.'/logo/25x25.jpg" />';
echo '<link rel="apple-touch-icon" sizes="76x76" href="'.IMAGES.'/logo/25x25.jpg" />';
echo '<link rel="apple-touch-icon" sizes="152x152" href="'.IMAGES.'/logo/25x25.jpg" />';
echo '<link rel="apple-touch-icon" sizes="58x58" href="'.IMAGES.'/logo/25x25.jpg" />';
echo '<link href="'.IMAGES.'/logo/25x25.jpg" rel="icon" sizes="192x192" />';
echo '<link href="'.IMAGES.'/logo/25x25.jpg" rel="icon" sizes="128x128" />';


echo $this->head('css');
echo $this->head('js');
echo $this->head('style');

// <!--[if lt IE 10]>
// <script>var ie = true;</script>
// <![endif]-->

echo '</head>';

if( $this->elem("body")->attr() ){

    $attributes = "";
    foreach ($this->elem("body")->attr() as $key => $value) {
        $attributes .= " {$key}=\"{$value}\"";
    }

    echo '<body'.$attributes.'>';
	
}
else{
    echo '<body>';
}
?>