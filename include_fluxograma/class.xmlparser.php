<?php
    class XmlParser {
        var $xml;
        var $lastnode = array();
        var $struct = array();

        function XmlParser($xml) {
            $this->xml = xml_parser_create();
            xml_set_object($this->xml, $this);
            xml_set_element_handler($this->xml, 'tag_open', 'tag_close');
            xml_set_character_data_handler($this->xml, 'cdata');
            $this->lastnode = array(&$this->struct);
            if (!xml_parse($this->xml, $xml)) {
                die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->xml)), xml_get_current_line_number($this->xml)));
            }
            xml_parser_free($this->xml);
        }

        function tag_open($parser, $tag, $attributes) {
            $c = count($this->lastnode) - 1;
            $this->lastnode[$c][] = array('tag'    => $tag, 'attrib' => $attributes,
                                          'data'   => '',   'childs' => array());
            $this->lastnode[] = &$this->lastnode[$c][count($this->lastnode[$c]) - 1]['childs'];
        }

        function tag_close($parser, $tag) {
            array_pop($this->lastnode);
        }

        function cdata($parser, $cdata) {
            if (strlen(ltrim($cdata)) > 0) {
                $p = count($this->lastnode) - 2;
                $this->lastnode[$p][count($this->lastnode[$p]) - 1]['data'] .= str_replace('\n', "\n", trim($cdata));
            }
        }
    }
?>
