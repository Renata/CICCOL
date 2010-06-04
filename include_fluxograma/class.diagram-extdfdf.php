<?php
    /**
     * Diagram
     * Class to draw diagrams from a xml file/data
     *
     * @author Diogo Resende <me@diogoresende.net>
     * @version 2.0 (extended)
     *
     **/
    class DiagramExtended extends Diagram {
        /**
         * DiagramExtended::DiagramExtended()
         *
         * Class constructor
         **/
        function DiagramExtended($xml_file = '') {
            Diagram::Diagram($xml_file);
        }

        /**
         * DiagramExtended::getNodePositions()
         * Returns an multi-dimensional array with the dimensions
         * of all nodes
         **/
        function getNodePositions() {
            if (!$this->prepared) {
                $this->__prepare($this->struct[0], true);
                $this->prepared = true;
            }
            $data = $this->__nodePosition($this->struct[0]['childs'][0], 0, 0, $this->struct[0]['childs'][0]['attrib']['NODEHEIGHT']);
            return $data;
        }

        /**
         * Diagram::__nodePosition()
         *
         * Draw a node
         **/
        function __nodePosition(&$node, $left = 0, $top = 0, $total_height = 0) {
            $x = round(($node['attrib']['TOTALWIDTH'] - $node['attrib']['NODEWIDTH']) / 2);

            $x += $left + $node['attrib']['MARGINLEFT'];
            $y = $top + $node['attrib']['MARGINTOP'];

            $return = array(
                'x'      => $x,
                'y'      => $y,
                'w'      => $node['attrib']['NODEWIDTH'] - $node['attrib']['MARGINLEFT'] - $node['attrib']['MARGINRIGHT'],
                'h'      => $node['attrib']['NODEHEIGHT'] - $node['attrib']['MARGINBOTTOM'] - $node['attrib']['MARGINTOP'],
                'name'   => (isset($node['attrib']['NAME']) ? $node['attrib']['NAME'] : ''),
                'childs' => array()
            );

            $left_offset = $left;
            $top += $total_height;
            for ($i = 0; $i < count($node['childs']); $i++) {
                $return['childs'][$i] = $this->__nodePosition($node['childs'][$i], $left_offset, $top, $node['attrib']['TOTALHEIGHT']);

                $left_offset += $node['childs'][$i]['attrib']['TOTALWIDTH'];
            }

            return $return;
        }
    }
?>
