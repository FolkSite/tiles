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
    
    /**
     * Shows all tiles in a grid or list.
     *
     *
     *
     */
    public function show_all($args) {
        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/style.css');
        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/mgr.css');
        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/dropzone.css');
           
                
        $this->modx->regClientStartupScript($this->assets_url . 'components/tiles/js/jquery-2.0.3.min.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery.easing.1.3.js');
        
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jquery-ui.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/dropzone.js');
   
        // Get a javascript compatible version of the tile template so it can better format upload progress
        // We send it default parameters from a default new Tile object
        $Tile = $this->modx->newObject('Tile');
        $previewTemplate = $this->_load_view('preview.php',$Tile->toArray());
    	$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
    		var connector_url = "'.$this->connector_url.'";
            var assets_url = "'.MODX_ASSETS_URL.'";
            jQuery(document).ready(function() {
                var myDropzone = new Dropzone("div#image_dropzone", 
                    {
                        url: connector_url+"image_upload",
                        previewTemplate: '.json_encode($previewTemplate).'
                    }
                );
                
                // Refresh the list on success (append new tile to end)
                myDropzone.on("success", function(file,response) {
                    response = jQuery.parseJSON(response);
                    console.log(response);
                    if (response.success) {
                        var url = connector_url + "get_tile&id=" + response.id;
                        jQuery.post( url, function(data){
                            jQuery("#product_images").append(data);
                            jQuery(".dz-preview").remove();
                        });
	               }
	               // TODO: better formatting
	               else {
	                   alert(response.msg);
	               }
                });
            });
    		</script>
    	');

        $sort = $this->modx->getOption('sort',$args,'seq');
        $dir = $this->modx->getOption('dir',$args,'ASC');
        
        $criteria = $this->modx->newQuery('Tile');

        $criteria->sortby($sort,$dir);
        $Tiles = $this->modx->getCollection('Tile',$criteria);

        $data = array();
        $data['mgr_controller_url'] = $this->mgr_controller_url;
        $data['tiles'] = '';
        if ($Tiles) {
            foreach ($Tiles as $T) {
                $tdata = $T->toArray();
                $tdata['mgr_controller_url'] = $this->mgr_controller_url;
                $data['tiles'] .= $this->_load_view('tile.php',$tdata);
            }
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
        
        $action = $this->modx->getOption('action', $args);
        unset($args['action']);        
        
        switch ($action) {
            case 'update':
                $Tile = $this->modx->getObject('Tile',$this->modx->getOption('id', $args));
                if (!$Tile) {
                    $out['success'] = false;
                    $out['msg'] = 'Invalid Tile.';
                    return json_encode($out);
                }
                $Tile->fromArray($args);
                if (!$Tile->save()) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to update Tile.';    
                }
                $out['msg'] = 'Tile updated successfully.';    
                break;
            case 'delete':
                $Tile = $this->modx->getObject('Tile',$this->modx->getOption('id', $args));
                if (!$Tile) {
                    $out['success'] = false;
                    $out['msg'] = 'Invalid Tile.';
                    return json_encode($out);
                }
                // Remove the file
                if ($Tile->get('image_location') && file_exists(MODX_ASSETS_PATH.$Tile->get('image_location'))) {
                    @unlink(MODX_ASSETS_PATH.$Tile->get('image_location'));
                }
                if (!$Tile->remove()) {
                    $out['success'] = false;
                    $out['msg'] = 'Failed to delete Tile.';
                    return json_encode($out);
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
        $tiles = $this->modx->getOption('tiles',$args,array());
        $out = array(
            'success' => true,
            'msg' => 'Tiles Sorted Successfully.',
        );
        // all tile ids in the new order
       
        $i = 0;
        foreach ($tiles as $tile_id) {
            $Tile = $this->modx->getObject('Tile',$tile_id);
            if (!$Tile) {
                $out['success'] = false;
                $out['msg'] = 'Invalid Title ID'; 
            }
            $Tile->set('seq',$i);
            $Tile->save();
            $i++;
        }
        
       return json_encode($out); 
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
        
        $this->modx->regClientCSS($this->assets_url . 'components/tiles/css/mgr.css');
        $this->modx->regClientStartupScript($this->assets_url . 'components/tiles/js/jquery-2.0.3.min.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/dropzone.js');
        $this->modx->regClientStartupScript($this->assets_url.'components/tiles/js/jcrop.js');        
    	$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
    		var connector_url = "'.$this->connector_url.'";
            var assets_url = "'.MODX_ASSETS_URL.'";
            jQuery(document).ready(function() {
                var myDropzone = new Dropzone("div#image_dropzone", 
                    {
                        url: connector_url+"image_upload&id='.$id.'"
                    }
                );
                myDropzone.on("success", function(file) {
                    // Refresh the image on success
                    var url = connector_url + "get_image_tag&id='.$id.'";
            	    jQuery.post( url, function(data){
                        jQuery("#target_image").html(data);
                        jQuery(".dz-preview").remove();
	               });
                });
            });
    		</script>
    	');
        
        return $this->_load_view('update.php',$data);
        
    }
    /**
     * Post data here to upload an image. This happens when an image is dragged into the 
     * canvas (thus creating a new tile), or when an existing image is replaced.
     * 
     * @return JSON message with success, msg, and id 
     */
    public function image_upload($args) {
        
        $this->modx->log(MODX_LOG_LEVEL_DEBUG, 'Tiles image_upload. $_FILES: '.print_r($_FILES,true)."\n".'$_POST:'.print_r($_POST,true));

        $action = $this->modx->getOption('action', $args);     
        unset($args['action']);
        $out = array(
            'success' => true,
            'msg' => '',
            'id' => ''
        );

        $tile_id = (int) $this->modx->getOption('id',$args);
        
        $Tile = $this->modx->newObject('Tile');
        if ($tile_id) {
            $Tile = $this->modx->getObject('Tile',$tile_id);
            $out['id'] = $tile_id;
        }
        if (!$Tile) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR,'Error fetching tile '.$tile_id);
            $out['msg'] = 'Error fetching tile '.$tile_id;
            return json_encode($out);
        }
        

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
            //$out['rel_file'] = $rel_file;
            //$out['file_size'] = $_FILES['file']['size'];
            
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
            $out['id'] = $Tile->get('id');
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
    
    /**
     * Get a single image tag for Ajax update
     *
     */
    public function get_image_tag($args) {
        $id = (int) $this->modx->getOption('id', $args);
        
        $Tile = $this->modx->getObject('Tile',$id);
        
        if (!$Tile) {
            return 'Error loading image.';
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
                
        return $this->_load_view('image.php',$data);
    }

    /**
     * Get a single tile for Ajax update
     *
     */
    public function get_tile($args) {
        $id = (int) $this->modx->getOption('id', $args);
        
        $Tile = $this->modx->getObject('Tile',$id);
        
        if (!$Tile) {
            return 'Error loading tile. '.print_r($args,true);
        }
        
        $data = $Tile->toArray();
        $data['mgr_controller_url'] = $this->mgr_controller_url;
        return $this->_load_view('tile.php',$data);
    }
        
}
/*EOF*/