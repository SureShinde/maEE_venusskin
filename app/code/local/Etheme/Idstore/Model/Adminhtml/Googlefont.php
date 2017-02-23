<?php class Etheme_Idstore_Model_Adminhtml_Googlefont
{
    public function toOptionArray()
    
    {
        
        /** @todo
         * Full list of available google fonts?
         * */
        $fonts = 'Open Sans,Oswald,Droid Sans,Lato,PT Sans,Yanone Kaffeesatz,Ubuntu,Arvo,Lobster,Lora';
        $fonts = explode(',', $fonts);
        
        $opts = array();
        
        for ($i = 0; $i < count($fonts); $i++) {
            
            $opts[$i] = array('value' => $fonts[$i], 'label' => $fonts[$i]);
        }

        return $opts;
    }

}?>