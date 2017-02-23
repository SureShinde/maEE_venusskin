<?php
 
class Etheme_Idstore_IndexController extends Mage_Adminhtml_Controller_Action {
    
    	
    public function installBlocksAction(){
    	// variables
		$cmsModel = Mage::getModel('cms/block');
		$msg_success = '';
		$msg_errors = '';
		$success = 0;
		$errors = 0;
		
    	// load blocks from XML file (media/etheme/Dummy.xml)
		$blocks = $this->getBlocksXML();
		
		foreach($blocks as $block) {
			// Prepare Block array
			$toInstall = array(
			    'title' => $block['title'],
			    'identifier' => $block['id'],                   
			    'content' => $block['content'],
			    'is_active' => 1,                   
			    'stores' => array(0)
			);
			
			try {
				// Try to add Block to DB
				$cmsModel->setData($toInstall)->save();
				
				$success++;
			} catch (Exception $e) {
				// If block already exists we have error
				$errors++;
			}
			
		}
	    if($success > 0)
			$msg_success = '<li class="success-msg"><ul><li><span>Successfully installed '.$success.' blocks</span></li></ul></li>';

	    if($errors > 0)
			$msg_errors = '<li class="error-msg"><ul><li><span>Error with '.$errors.' blocks installation</span></li></ul></li>';
	    
    
        $this->loadLayout();
        
        
        $url = $this->getUrl('adminhtml/system_config'); 
        
        // Create Button HTML
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Return to configuration')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();
        
        // Show info
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block')
        ->setText('<div id="messages"><ul class="messages">'.$msg_success.$msg_errors.'</ul></div><br>'.$html);

        $this->_addContent($block);

        $this->renderLayout();
    }	
    
	public function getBlocksXML() { 
		$xml = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'etheme/Dummy.xml';
		$reader = new DOMDocument(); 
		$reader->load($xml);  //Loads the Above XML 
		
		$i = 0; 
	         
	    foreach ($reader->getElementsByTagName('cmsblock') as $node) { 
	    
	        $res[$i]['id'] = $node->getElementsByTagName('id')->item(0)->nodeValue; 
	        $res[$i]['title'] = $node->getElementsByTagName('title')->item(0)->nodeValue; 
	        $res[$i]['content'] = $node->getElementsByTagName('content')->item(0)->nodeValue; 
	
	        $i++; 
	    } 
		
		return $res; 
	}  
}