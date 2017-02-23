<?php class Etheme_Idstore_Block_Flickr_Screen extends Mage_Core_Block_Template {

    protected function _construct() {
        
        /* add five minutes cache lifetime */
        $this->addData(array(
        'cache_lifetime' => 300,
        ));
    }
    
    
    private function getParsed($url) {
        
        $request = curl_init();
        curl_setopt($request, CURLOPT_TIMEOUT, 30);
    	curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);  
        $raw = curl_exec($request);
        if ($raw == '' || !$raw) {
            $error_text = curl_error($request); 
            Mage::log($error_text, false, 'etheme_flickr.log');
            curl_close($request);
    		return Array("error" => "Error while processing request, see logs for details");
        }
        else {
            $raw = trim($raw, 'jsonFlickrApi()');
            curl_close($request);
            return json_decode($raw);
        }
        
    }
    
    
    private function grabScreen($screen_name, $number, $api_key) {
        
        $person_url = 'http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key='.$api_key.'&username='.urlencode($screen_name).'&format=json';
        $person = $this->getParsed($person_url);
        if ($person->user->id) {
            $photos_url = 'http://api.flickr.com/services/rest/?method=flickr.urls.getUserPhotos&api_key='.$api_key.'&user_id='.$person->user->id.'&format=json';
            $photos_url = $this->getParsed($photos_url);
            $photos = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key='.$api_key.'&user_id='.$person->user->id.'&per_page='.$number.'&format=json';
            $photos = $this->getParsed($photos);
            return Array("photos_url" => $photos_url,
                        "photos" => $photos,);
        }
        else {
            return Array('error' => "Invalid username");
        }
    }
    
    public function getScreen() {
        
        $username = Mage::getStoreConfig('idstore_general/footer/flickr_username');
        $count = Mage::getStoreConfig('idstore_general/footer/flickr_count');
        $api_key = Mage::getStoreConfig('idstore_general/footer/flickr_api_key');
        $screen = $this->grabScreen($username, $count, $api_key);
        return $screen;
    }
    
}