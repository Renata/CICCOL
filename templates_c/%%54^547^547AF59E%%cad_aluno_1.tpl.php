<?php /* Smarty version 2.6.26, created on 2010-05-14 21:56:30
         compiled from cad_aluno_1.tpl */ ?>
<html>
<body>

<p>RENATA</p>
<select id="estado">
    <?php unset($this->_sections['dados']);
$this->_sections['dados']['name'] = 'dados';
$this->_sections['dados']['loop'] = is_array($_loop=$this->_tpl_vars['contacts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dados']['show'] = true;
$this->_sections['dados']['max'] = $this->_sections['dados']['loop'];
$this->_sections['dados']['step'] = 1;
$this->_sections['dados']['start'] = $this->_sections['dados']['step'] > 0 ? 0 : $this->_sections['dados']['loop']-1;
if ($this->_sections['dados']['show']) {
    $this->_sections['dados']['total'] = $this->_sections['dados']['loop'];
    if ($this->_sections['dados']['total'] == 0)
        $this->_sections['dados']['show'] = false;
} else
    $this->_sections['dados']['total'] = 0;
if ($this->_sections['dados']['show']):

            for ($this->_sections['dados']['index'] = $this->_sections['dados']['start'], $this->_sections['dados']['iteration'] = 1;
                 $this->_sections['dados']['iteration'] <= $this->_sections['dados']['total'];
                 $this->_sections['dados']['index'] += $this->_sections['dados']['step'], $this->_sections['dados']['iteration']++):
$this->_sections['dados']['rownum'] = $this->_sections['dados']['iteration'];
$this->_sections['dados']['index_prev'] = $this->_sections['dados']['index'] - $this->_sections['dados']['step'];
$this->_sections['dados']['index_next'] = $this->_sections['dados']['index'] + $this->_sections['dados']['step'];
$this->_sections['dados']['first']      = ($this->_sections['dados']['iteration'] == 1);
$this->_sections['dados']['last']       = ($this->_sections['dados']['iteration'] == $this->_sections['dados']['total']);
?>
    <option><?php echo $this->_tpl_vars['contacts'][$this->_sections['dados']['index']]['estado']; ?>
</option>
    <?php endfor; endif; ?>
</select>


</body>
</html>