<?php
class TilesMgrController{
    
    public $modx;
    public $core_path;
    public $assets_url;
    public $connector_url;
    public $upload_url;
    public $mgr_controller_url;
    public $max_image_width = 300;
    
    public function __construct($modx) {
        $this->modx = &$modx;
        $this->core_path = $this->modx->getOption('tiles.core_path','',MODX_CORE_PATH);
        $this->modx->addPackage('tiles',$this->core_path.'components/tiles/model/');
        $this->assets_url = $this->modx->getOption('tiles.assets_url', null, MODX_ASSETS_URL);
        $this->connector_url = $this->assets_url.'components/tiles/connector.php?f=';
        $this->upload_dir = 'images/tiles/'; // path & url: rel to MODX_ASSETS_PATH & MODX_ASSETS_URL
        $a = (int) $this->modx->getOption('a',$_GET);
        $this->mgr_controller_url = MODX_MANAGER_URL .'?a='.$a.'&f=';
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
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery-ui.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/dropzone.js');
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
    	$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
    		var connector_url = "'.$this->connector_url.'";
            var assets_url = "'.MODX_ASSETS_URL.'";
            jQuery(document).ready(function() {
                var myDropzone = new Dropzone("div#image_dropzone", {url: connector_url+"image_upload"});
            });
    		</script>
    	');

        $Tiles = $this->modx->getCollection('Tile');
        $data = array();
        $data['mgr_controller_url'] = $this->mgr_controller_url;
        $data['tiles'] = '';
        foreach ($Tiles as $T) {
            $tdata = $T->toArray();
            $tdata['mgr_controller_url'] = $this->mgr_controller_url;
            $data['tiles'] .= $this->_load_view('tile.php',$tdata);
        }
        return $this->_load_view('list.php',$data);
    }
    
    /**
     * Save the tile
     *
     */
    public function save($args) {
        $this->modx->log(MODX_LOG_LEVEL_DEBUG,'Tile save: '.print_r($args,true));

/*
        if (!is_object($this->modx->user)) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR,'spec_save 401 '.print_r($args,true));
            return $this->_send401();
        }
*/
        $out = array(
            'success' => true,
            'msg' => '',
        );
        
/*
        $token = $this->modx->getOption('HTTP_MODAUTH', $args);   
        if ($token != $this->modx->user->getUserToken($this->modx->context->get('key'))) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR,'spec_save FAILED. Invalid token: '.print_r($args,true));
            $out['success'] = false;
            $out['msg'] = 'Invalid token';
        }
*/
        
        $action = $this->modx->getOption('action', $args);
        unset($args['action']);        
        
        switch ($action) {
            case 'update':
                $Tile = $this->modx->getObject('Tile',$this->modx->getOption('id', $args));
                $Tile->fromArray($args);
                if (!$Tile->save()) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to update Tile.';    
                }
                $out['msg'] = 'Tile updated successfully.';    
                break;
            case 'delete':
                $Tile = $this->modx->getObject('Tile',$this->modx->getOption('id', $args));
                if (!$Tile->remove()) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to delete Tile.';    
                }
                $out['msg'] = 'Tile deleted successfully.';    
                break;
            case 'create':
            default:
                $Tile = $this->modx->newObject('Tile');    
                $Tile->fromArray($args);
                if (!$Tile->save()) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to save Tile.';    
                }
                $out['msg'] = 'Tile created successfully.';    
        }
                
        return json_encode($out);
    }
    
    public function save_order($args) {
        $this->modx->log(1, print_r($args,true));
        
        // all tile ids in the new order
        $seqs = $this->modx->getOption('seq',$args,array());
        $i = 0;
        foreach ($seqs as $tile_id) {
            $Tile = $this->modx->getObject('Tile',$tile_id);
            if (!$Tile) {
                $this->modx->log(1, 'Unable to load Tile '.$tile_id);   
                continue;
            }
            $Tile->set('seq',$i);
            $Tile->save();
            $i++;
        }
    }
    /**
     * Used to update a single tile
     *
     *
     *
     */
    public function update($args) {
        $id = (int) $this->modx->getOption('id', $args);
        
        $Tile = $this->modx->getObject('Tile',$id);
        
        if (!$Tile) {
            return 'Invalid id.';
        }
        
        $data = $Tile->toArray();
        $data['mgr_controller_url'] = $this->mgr_controller_url;
        $data['wide_load'] = '';
        $data['visible_height'] = $data['height'];
        $data['visible_width'] = $data['width'];        
        if ($data['width'] > $this->max_image_width) {
            $data['wide_load'] = 'Warning! This image is larger than it appears.';
            $ratio = $this->max_image_width / $data['width'];
            $data['visible_height'] = $data['height'] * $ratio;
            $data['visible_width'] = $this->max_image_width;
        }
        
        $this->modx->regClientStartupScript($this->assets_url . 'components/tiles/js/jquery-2.0.3.min.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/dropzone.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jcrop.js');        
    	$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
    		var connector_url = "'.$this->connector_url.'";
            var assets_url = "'.MODX_ASSETS_URL.'";
            jQuery(document).ready(function() {
                var myDropzone = new Dropzone("div#image_dropzone", {url: connector_url+"image_upload&id='.$id.'"});
            });
    		</script>
    	');
        
        return $this->_load_view('update.php',$data);
        
    }
    /**
     * Post data here to upload an image. This happens when an image is dragged into the 
     * canvas (thus creating a new tile), or when an existing image is replaced.
     */
    public function image_upload($args) {
        
        $this->modx->log(MODX_LOG_LEVEL_DEBUG, 'Tiles image_upload. $_FILES: '.print_r($_FILES,true)."\n".'$_POST:'.print_r($_POST,true));

        $action = $this->modx->getOption('action', $args);     
        unset($args['action']);
        $out = array(
            'success' => true,
            'msg' => '',
        );


        $tile_id = (int) $this->modx->getOption('id',$args);
        
        $Tile = $this->modx->newObject('Tile');
        if ($tile_id) {
            $Tile = $this->modx->getObject('Tile',$tile_id);
        }
        if (!$Tile) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR,'Error fetching tile '.$tile_id);
        }
        
        $out = array(
            'success' => true,
            'msg' => '',
        );
        if (isset($_FILES['file']['name']) ) {
            // Relative to either MODX_ASSETS_URL or MODX_ASSETS_PATH
            $rel_file =  $this->upload_dir.basename($_FILES['file']['name']);
            $target_path = MODX_ASSETS_PATH.$rel_file;
            if (!file_exists(MODX_ASSETS_PATH.$this->upload_dir)) {
                if (!mkdir(MODX_ASSETS_PATH.$this->upload_dir,0777,true)) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to create directory at '.MODX_ASSETS_PATH.$this->upload_dir;    
                    $this->modx->log(MODX_LOG_LEVEL_ERROR, 'Failed to create directory at '.MODX_ASSETS_PATH.$this->upload_dir);
                    return json_encode($out);
                }
            }
            // Image already exists? 
            // TODO: rename and get on with it
            if (file_exists(MODX_ASSETS_PATH.$rel_file)) {
                $out['success'] = false;
                $out['msg'] = 'Upload Cannot Continue. File of same name exists '.MODX_ASSETS_PATH.$rel_file;
                $this->modx->log(MODX_LOG_LEVEL_ERROR, 'Upload Cannot Continue. File of same name exists '.MODX_ASSETS_PATH.$rel_file);
                return json_encode($out);
            }
            if(move_uploaded_file($_FILES['file']['tmp_name'],MODX_ASSETS_PATH.$rel_file)) {
                $this->modx->log(MODX_LOG_LEVEL_DEBUG, 'SUCCESS UPLOAD: '.MODX_ASSETS_PATH.$rel_file);
            } 
            else {
                $out['success'] = false;
                $out['msg'] = 'FAILED UPLOAD: '.MODX_ASSETS_PATH.$rel_file;
                $this->modx->log(MODX_LOG_LEVEL_ERROR, 'FAILED UPLOAD: '.MODX_ASSETS_PATH.$rel_file);
                return json_encode($out);
            }
            $out['rel_file'] = $rel_file;
            $out['file_size'] = $_FILES['file']['size'];
            
        }
        
        list($width, $height) = getimagesize(MODX_ASSETS_PATH.$rel_file);
        
        $Tile->set('image_location', $rel_file);
        $Tile->set('width', $width);
        $Tile->set('height', $height);
        $Tile->set('size', $_FILES['file']['size']);
        
        if (!$Tile->save()) {
            $out['success'] = false;
            $out['msg'] = 'There was a problem uploading the image. Tile not saved.';        
        }
        else {
            $this->modx->log(MODX_LOG_LEVEL_DEBUG, 'Successfully saved tile '.$Tile->getPrimaryKey() .' '.MODX_ASSETS_PATH.$rel_file);
            $out['msg'] = 'Successfully saved image';
        }
        
        return json_encode($out);
    }
    
    /**
     * Used when cropping an image
     *
     *
     */
    public function image_crop($args) { 
        $this->modx->log(1, print_r($args,true));
        // http://www.php.net/manual/en/function.imagecopy.php
        
    }
}
/*EOF*/