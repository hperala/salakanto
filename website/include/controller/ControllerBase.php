<?php
require_once 'include/model/Db.php';
require_once 'include/model/User.php';
require_once 'include/model/Text.php';
require_once 'include/model/Settings.php';
require_once 'include/controller/Request.php';

class ControllerBase {
    
    protected $db; 
    protected $user;
    protected $request;
    protected $settings;
    
    function __construct() {
    	try {
	        $this->db = Db::createFromConfig();
	        $this->db->connect();
	        $this->user = User::createAndStartSession($this->db);
	        $this->request = Request::createFromEnv();
	        $this->request->initLocalization();
	        $this->settings = Settings::createFromDb($this->db);
        } catch (Exception $e) {
        	echo '<html><body><h1>Error</h1><p>' . $e->getMessage() . '</p><p>' . $e->getTraceAsString() . '</p></body></html>';
        }
    }
    
    function run() {
    	try {
    	    if ($this->request->type() == Request::OPEN) {
    	        $this->handleOpen();
    	    } else if ($this->request->type() == Request::OPEN_EDIT) {
    	        $this->handleOpenEdit();
    	    } else if ($this->request->type() == Request::OPEN_CREATE) {
    	        $this->handleOpenCreate();
	        } else if ($this->request->type() == Request::OPEN_DELETE) {
	            $this->handleOpenDelete();
	        } else if ($this->request->type() == Request::UPDATE) {
	            $this->handleUpdate();
            } else if ($this->request->type() == Request::CREATE) {
                $this->handleCreate();
            } else if ($this->request->type() == Request::DELETE) {
                $this->handleDelete();
            } else if ($this->request->type() == Request::UPLOAD) {
                $this->handleUpload();
    	    } else {
    	        throw new Exception('Unknown request type');
    	    }
    		
    		$this->db->close();
    	} catch (Exception $e) {
    		echo '<html><body><h1>Error</h1><p>' . $e->getMessage() . '</p></body></html>';
    	}
    }
    
    function handleOpen() {
        $this->notImplemented();    
    }
    
    function handleOpenEdit() {
        $this->notImplemented();
    }
    
    function handleOpenCreate() {
        $this->notImplemented();
    }
    
    function handleOpenDelete() {
        $this->notImplemented();
    }
    
    function handleUpdate() {
        $this->notImplemented();
    }
    
    function handleCreate() {
        $this->notImplemented();
    }
    
    function handleDelete() {
        $this->notImplemented();
    }
    
    function handleUpload() {
        $this->notImplemented();
    }
    
    private function notImplemented() {
        throw new BadMethodCallException('Not implemented');
    }
}