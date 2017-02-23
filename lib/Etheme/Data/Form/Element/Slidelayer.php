<?php

class Etheme_Data_Form_Element_Slidelayer extends Varien_Data_Form_Element_Abstract
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    public function getElementHtml()
    {
        $gallery = $this->getValue();

        $html = '<table id="gallery" class="gallery" border="0" cellspacing="3" cellpadding="0">';
        $html .= '<thead id="gallery_thead" class="gallery"><tr class="gallery"><td class="gallery" valign="middle" align="center">Title</td><td class="gallery" valign="middle" align="center">Type</td><td class="gallery" valign="middle" align="center">Order</td></tr></thead>';
        $widgetButton = $this->getForm()->getParent()->getLayout();
        $addButtonHtml = $widgetButton->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
					    'label'     => 'Add New Image',
                        'onclick'   => 'addNewImg()',
                        'class'     => 'add'))
                ->toHtml();
        $removeButtonHtml = $widgetButton->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'     => 'Add New Image',
                        'onclick'   => 'removeImg()',
                        'class'     => 'remove'))
                ->toHtml();
        $html .= '<tfoot class="gallery">';
        $html .= '<tr class="gallery">';
        $html .= '<td class="gallery" valign="middle" align="left" colspan="5">'.$addButtonHtml.'</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';

        $html .= '<tbody class="gallery">';

        $i = 0;
        if (!is_null($this->getValue())) {
            foreach ($this->getValue() as $layer) {
                $i++;
                $html .= '<tr class="gallery">';
                $html .= '<td class="gallery" align="center" style="vertical-align:bottom;"><input type="input" name="'.parent::getName().'[title]['.$layer->getValueId().']" value="'.$layer->getTitle().'" id="'.$this->getHtmlId().'_title_'.$layer->getValueId().'" /></td>';
                $html .= '<td class="gallery" align="center" style="vertical-align:bottom;"><input type="input" name="'.parent::getName().'[type]['.$layer->getValueId().']" value="'.$layer->getType().'" id="'.$this->getHtmlId().'_type_'.$layer->getValueId().'"/></td>';
                $html .= '<td class="gallery" align="center" style="vertical-align:bottom;"><input type="input" name="'.parent::getName().'[order]['.$layer->getValueId().']" value="'.$layer->getOrder().'" id="'.$this->getHtmlId().'_order_'.$layer->getValueId().'"/></td>';
                $html .= '</tr>';
            }
        }
        if ($i==0) {
            $html .= '<script type="text/javascript">document.getElementById("gallery_thead").style.visibility="hidden";</script>';
        }

        $html .= '</tbody></table>';

/*
        $html .= '<script language="javascript">
                    var multi_selector = new MultiSelector( document.getElementById( "gallery" ),
                    "'.$this->getName().'",
                    -1,
                        \'<a href="file:///%file%" target="_blank" onclick="imagePreview(\\\''.$this->getHtmlId().'_image_new_%id%\\\');return false;"><img src="file:///%file%" width="50" align="absmiddle" class="small-image-preview" style="padding-bottom:3px; width:"></a> <div id="'.$this->getHtmlId().'_image_new_%id%" style="display:none" class="image-preview"><img src="file:///%file%"></div>\',
                        "",
                        \'<input type="file" name="'.parent::getName().'[new_image][%id%][%j%]" size="1" />\'
                    );
                    multi_selector.addElement( document.getElementById( "'.$this->getHtmlId().'" ) );
                    </script>';
*/

        $name = $this->getName();
        $parentName = parent::getName();

        $html .= <<<EndSCRIPT
        
        <script language="javascript">
        id = 0;

        function addNewImg(){

            document.getElementById("gallery_thead").style.visibility="visible";
            id--;
            typeSelect = '<select class="select required-option-select" name="{$name}[type][%id%]">';
            typeSelect += '<option value>--please select--</option>';
            typeSelect += '<option value="text">Text</option>';
            typeSelect += '<option value="image">Image</option>';
            typeSelect += '<option value="video">Video</option>';
            typeSelect += '</select>';
            //new_file_input = '<input type="file" name="{$name}_%j%[%id%]" size="1" />';
            layer_name = '<input type="input" name="{$name}[name][%id%]" size="25" />';
            layer_order = '<input type="input" name="{$name}[order][%id%]" size="5" />';

            table = document.getElementById( "gallery" );

            // no of rows in the table:
            noOfRows = table.rows.length;

            // no of columns in the pre-last row:
            noOfCols = table.rows[noOfRows-2].cells.length;

            // insert row at pre-last:
            var x=table.insertRow(noOfRows-1);

            // insert cells in row.
            for (var j = 0; j < noOfCols; j++) {

                newCell = x.insertCell(j);
                newCell.align = "center";
                newCell.valign = "middle";
                
                if (j==0) {
                    newCell.innerHTML =  layer_name.replace(/%id%/g, id);
                }

                if (j==1) {
		            newCell.innerHTML =  typeSelect.replace(/%id%/g, id);
                }
                
                if (j==2) {
                    newCell.innerHTML =  layer_order.replace(/%id%/g, id);
                }
                
                //@todo removeButton here
                /*
                else if (j==3) {
		            newCell.appendChild( new_row_button );
                }
                */
                
            }

		    // Delete function
            /*
		    new_row_button.onclick= function(){

                this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );

			    // Appease Safari
			    //    without it Safari wants to reload the browser window
			    //    which nixes your already queued uploads
			    return false;
		    };
            */

	    }
        </script>

EndSCRIPT;
        $html.= $this->getAfterElementHtml();
        return $html;
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getParentName()
    {
        return parent::getName();
    }
}
