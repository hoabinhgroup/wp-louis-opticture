<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! function_exists('_attributes_to_string'))
{
    /**
     * Attributes To String
     *
     * Helper function used by some of the form helpers
     *
     * @param	mixed
     * @return	string
     */
    function _attributes_to_string($attributes)
    {
        if (empty($attributes))
        {
            return '';
        }

        if (is_object($attributes))
        {
            $attributes = (array) $attributes;
        }

        if (is_array($attributes))
        {
            $atts = '';

            foreach ($attributes as $key => $val)
            {
                $atts .= ' '.$key.'="'.$val.'"';
            }

            return $atts;
        }

        if (is_string($attributes))
        {
            return ' '.$attributes;
        }

        return FALSE;
    }

}

// ------------------------------------------------------------------------

if ( ! function_exists('form_open'))
{
    /**
     * Form Declaration
     *
     *
     * @param	string	the URI segments of the form destination
     * @param	array	a key/value pair of attributes
     * @param	array	a key/value pair hidden data
     * @return	string
     */
    function form_open($action = '', $attributes = array(), $hidden = array())
    {

        $attributes = _attributes_to_string($attributes);

        if (stripos($attributes, 'method=') === FALSE)
        {
            $attributes .= ' method="post"';
        }

        if (stripos($attributes, 'accept-charset=') === FALSE)
        {
            $attributes .= ' accept-charset="utf-8"';
        }

        $form = '<form action="'.$action.'"'.$attributes.">\n";

        if (is_array($hidden))
        {
            foreach ($hidden as $name => $value)
            {
                $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" />'."\n";
            }
        }


        return $form;
    }
}


// ------------------------------------------------------------------------

if ( ! function_exists('form_hidden'))
{
    /**
     * Hidden Input Field
     *
     * Generates hidden fields. You can pass a simple key/value string or
     * an associative array with multiple values.
     *
     * @param	mixed	$name		Field name
     * @param	string	$value		Field value
     * @param	bool	$recursing
     * @return	string
     */
    function form_hidden($name, $value = '', $recursing = FALSE)
    {
        static $form;

        if ($recursing === FALSE)
        {
            $form = "\n";
        }

        if (is_array($name))
        {
            foreach ($name as $key => $val)
            {
                form_hidden($key, $val, TRUE);
            }

            return $form;
        }

        if ( ! is_array($value))
        {
            $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value)."\" />\n";
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $k = is_int($k) ? '' : $k;
                form_hidden($name.'['.$k.']', $v, TRUE);
            }
        }

        return $form;
    }
}


if (!function_exists('form_input')) {
    /**
     * @param $layout, $module
     * @return mixed
     * @author Tuan Louis
     */
    function form_input($data, $value = '', $extra = '')
    {

            $defaults = array(
            'type' => 'text',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );


        return "<input "._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
    }

}

    if ( ! function_exists('form_password'))
    {
    /**
     * Password Field
     *
     * Identical to the input function but adds the "password" type
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	string
     */
    function form_password($data = '', $value = '', $extra = '')
    {
        is_array($data) OR $data = array('name' => $data);
        $data['type'] = 'password';
        return form_input($data, $value, $extra);
    }
    }

    if ( ! function_exists('form_textarea'))
    {
    /**
     * Textarea field
     *
     * @param	mixed	$data
     * @param	string	$value
     * @param	mixed	$extra
     * @return	string
     */
    function form_textarea($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'name' => is_array($data) ? '' : $data,
            'cols' => '40',
            'rows' => '10'
        );

        if ( ! is_array($data) OR ! isset($data['value']))
        {
            $val = $value;
        }
        else
        {
            $val = $data['value'];
            unset($data['value']); // textareas don't use the value attribute
        }

        return '<textarea '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
            .html_escape($val)
            ."</textarea>\n";
    }
    }


    if ( ! function_exists('selectBox'))
    {
    function selectBox($name, $value = null, $options, $default='None', $attribs = array()){
                 $strAttribs = '';
      if(count($attribs) > 0){
          foreach($attribs as $key =>$val){
              $strAttribs .= $key.' = "'. $val . '"';
          }
      }
       // $xhtml = '<div class="styled-select">';
        $xhtml = '<select name="'.$name.'" id="'.$name.'" '.$strAttribs.'>';
        $xhtml .='<option label="'.$default.'" value="0">'.$default.'</option>';
         foreach($options as $key=>$info):
         $strSelect = '';
             if (in_array($info['id'],$value)){
             $strSelect = ' selected="selected"';
             }

         $xhtml .='<option value="'.$info['id'].'" '.$strSelect.'> '.$info['name'].'</option>';
       endforeach;
       $xhtml .= '</select>';
       //$xhtml .= '</div>';
    return $xhtml;

    }
    }


    if ( ! function_exists('selectMultiBox'))
    {
    function selectMultiBox($name, $value = null, $options, $default='None', $attribs = array()){
                 $strAttribs = '';
      if(count($attribs) > 0){
          foreach($attribs as $key =>$val){
              $strAttribs .= $key.' = "'. $val . '"';
          }
      }
       // $xhtml = '<div class="styled-select">';
        $xhtml = '<select name="'.$name.'" id="'.$name.'" '.$strAttribs.'>';
        $xhtml .='<option label="'.$default.'" value="0">'.$default.'</option>';
         foreach($options as $key=>$info):
         $strSelect = '';
             if (in_array($info['id'],$value)){
             $strSelect = ' selected="selected"';
             }

              if($info['level'] == 1){

         $xhtml .='<option value="'.$info['id'].'" '.$strSelect.'> '.$info['name'].'</option>';
        }else{
        $string = '&nbsp;';
        $newString = '';
        for($i = 1;$i<$info['level'];$i++){
          $newString .= $string;
        }
        $name = $newString . '-' . $info['name'];
     $xhtml .=' <option label="'.$info['name'].'" value="'.$info['id'].'" '.$strSelect.'>'.$name.'</option>';
      }
       endforeach;
       $xhtml .= '</select>';
       //$xhtml .= '</div>';
    return $xhtml;

    }
    }

    if ( ! function_exists('form_multiselect'))
    {
    /**
     * Multi-select menu
     *
     * @param	string
     * @param	array
     * @param	mixed
     * @param	mixed
     * @return	string
     */
    function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
    {
        $extra = _attributes_to_string($extra);
        if (stripos($extra, 'multiple') === FALSE)
        {
            $extra .= ' multiple="multiple"';
        }

        return form_dropdown($name, $options, $selected, $extra);
       }
    }

    if ( ! function_exists('form_dropdown'))
{
    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @return	string
     */
    function form_dropdown($data = '', $options = array(), $selected = array(), $extra = '')
    {
        $defaults = array();

        if (is_array($data))
        {
            if (isset($data['selected']))
            {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options']))
            {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        }
        else
        {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected))
        {
            if (is_array($data))
            {
                if (isset($data['name'], $_POST[$data['name']]))
                {
                    $selected = array($_POST[$data['name']]);
                }
            }
            elseif (isset($_POST[$data]))
            {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

        foreach ($options as $key => $val)
        {
            $key = (string) $key;

            if (is_array($val))
            {
                if (empty($val))
                {
                    continue;
                }

                $form .= '<optgroup label="'.$key."\">\n";

                foreach ($val as $optgroup_key => $optgroup_val)
                {
                    $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                    $form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
                        .(string) $optgroup_val."</option>\n";
                }

                $form .= "</optgroup>\n";
            }
            else
            {
                $form .= '<option value="'.html_escape($key).'"'
                    .(in_array($key, $selected) ? ' selected="selected"' : '').'>'
                    .(string) $val."</option>\n";
            }
        }

        return $form."</select>\n";
        }
    }

    if ( ! function_exists('form_checkbox'))
    {
    /**
     * Checkbox Field
     *
     * @param	mixed
     * @param	string
     * @param	bool
     * @param	mixed
     * @return	string
     */
    function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '')
    {
        $defaults = array('type' => 'checkbox', 'name' => ( ! is_array($data) ? $data : ''), 'value' => $value);

        if (is_array($data) && array_key_exists('checked', $data))
        {
            $checked = $data['checked'];

            if ($checked == FALSE)
            {
                unset($data['checked']);
            }
            else
            {
                $data['checked'] = 'checked';
            }
        }

        if ($checked == TRUE)
        {
            $defaults['checked'] = 'checked';
        }
        else
        {
            unset($defaults['checked']);
        }

        return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
    }
}

    // ------------------------------------------------------------------------

    if ( ! function_exists('form_radio'))
    {
    /**
     * Radio Button
     *
     * @param	mixed
     * @param	string
     * @param	bool
     * @param	mixed
     * @return	string
     */
    function form_radio($data = '', $value = '', $checked = FALSE, $extra = '')
    {
        is_array($data) OR $data = array('name' => $data);
        $data['type'] = 'radio';

        return form_checkbox($data, $value, $checked, $extra);
    }
    }

    // ------------------------------------------------------------------------

if ( ! function_exists('form_submit'))
{
    /**
     * Submit Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	string
     */
    function form_submit($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'type' => 'submit',
            'name' => is_array($data) ? '' : $data
        );

        return '<button '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." >".$value ."</button>";
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('form_reset'))
{
    /**
     * Reset Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	string
     */
    function form_reset($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'type' => 'reset',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('form_button'))
{
    /**
     * Form Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	string
     */
    function form_button($data = '', $content = '', $extra = '')
    {
        $defaults = array(
            'name' => is_array($data) ? '' : $data,
            'type' => 'button'
        );

        if (is_array($data) && isset($data['content']))
        {
            $content = $data['content'];
            unset($data['content']); // content is not an attribute
        }

        return '<button '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
            .$content
            ."</button>\n";
    }
    }

    // ------------------------------------------------------------------------

    if ( ! function_exists('form_label'))
    {
    /**
     * Form Label Tag
     *
     * @param	string	The text to appear onscreen
     * @param	string	The id the label applies to
     * @param	mixed	Additional attributes
     * @return	string
     */
    function form_label($label_text = '', $id = '', $attributes = array())
    {

        $label = '<label';

        if ($id !== '')
        {
            $label .= ' for="'.$id.'"';
        }

        $label .= _attributes_to_string($attributes);

        return $label.'>'.$label_text.'</label>';
    }
}

    // ------------------------------------------------------------------------

    if ( ! function_exists('form_fieldset'))
    {
    /**
     * Fieldset Tag
     *
     * Used to produce <fieldset><legend>text</legend>.  To close fieldset
     * use form_fieldset_close()
     *
     * @param	string	The legend text
     * @param	array	Additional attributes
     * @return	string
     */
    function form_fieldset($legend_text = '', $attributes = array())
    {
        $fieldset = '<fieldset'._attributes_to_string($attributes).">\n";
        if ($legend_text !== '')
        {
            return $fieldset.'<legend>'.$legend_text."</legend>\n";
        }

        return $fieldset;
    }
    }

    // ------------------------------------------------------------------------

    if ( ! function_exists('form_fieldset_close'))
    {
    /**
     * Fieldset Close Tag
     *
     * @param	string
     * @return	string
     */
    function form_fieldset_close($extra = '')
    {
        return '</fieldset>'.$extra;
    }
    }

    // ------------------------------------------------------------------------

    if ( ! function_exists('form_close'))
    {
    /**
     * Form Close Tag
     *
     * @param	string
     * @return	string
     */
    function form_close($extra = '')
    {
        return '</form>'.$extra;
    }
    }

    // ------------------------------------------------------------------------
