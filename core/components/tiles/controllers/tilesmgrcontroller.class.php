<?php
class TilesMgrController{
    
    public $modx;
    public $core_path;
    
    public function __construct($modx) {
        $this->modx = &$modx;
        $this->core_path = $this->modx->getOption('tiles.core_path','',MODX_CORE_PATH);
        $this->modx->addPackage('tiles',$core_path.'components/tiles/model/');
        $this->assets_url = $this->modx->getOption('tiles.assets_url', null, MODX_ASSETS_URL);

    }

    /**
     * Load a view file. We put in some commonly used variables here for convenience
     *
     * @param string $file: name of a file inside of the "views" folder
     * @param array $data: an associative array containing key => value pairs, passed to the view
     * @return string
     */
    private function _load_view($file, $data=array(),$return=false) {
        $file = basename($file);
    	if (file_exists($this->core_path.'components/tiles/views/'.$file)) {
    	    if (!isset($return) || $return == false) {
    	        ob_start();
    	        include ($this->core_path.'components/tiles/views/'.$file);
    	        $output = ob_get_contents();
    	        ob_end_clean();
    	    }     
    	} 
    	else {
    		$output = $this->modx->lexicon('view_not_found', array('file'=> 'views/'.$file));
    	}
    
    	return $output;
    
    }
    
    public function show_all() {

        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/style.css');
        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/skeleton.css');
//        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/sequencejs-theme.modern-slide-in.css');
//        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/skin.css');
//        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/jquery.fancybox-1.3.4.css');                
//        $this->modx->regClientCSS('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,700,300');                
                
        $this->modx->regClientStartupScript($this->assets_url . 'components/tiles/js/jquery-2.0.3.min.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/superfish.js');
/*
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
*/
                

    
/*

<!-- CSS begin -->
		
		<!--[if lt IE 9]><link rel="stylesheet" type="text/css" media="screen" href="css/sequencejs-theme.modern-slide-in.ie.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="css/ie-warning.css" ><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" type="text/css" media="screen" href="css/style-ie.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="css/ei8fix.css" ><![endif]-->
 
<!-- CSS end -->
<!-- JS begin -->


		<script type="text/javascript" src="js/superfish.js"></script>
		<script type="text/javascript" src="js/sequence.jquery-min.js"></script>
		<script type="text/javascript" src="js/sequence-slider.js"></script>
		<script type="text/javascript" src="js/jquery.jcarousel.js"></script>
		<script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="js/jQuery.BlackAndWhite.min.js"></script>
		<script type="text/javascript" src="js/jquery.tweet.js"></script>
		<script type="text/javascript" src="js/jquery.accordion.2.0.min.js"></script>
		<script type="text/javascript" src="js/jquery.quicksand.js"></script>
		<script type="text/javascript" src="js/jflickrfeed.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>


*/    
        return $this->_load_view('list.php');
    }
    
    public function save() {
    
    }
}
/*EOF*/