<?php

sfRouting::getInstance()->prependRoute('javascript', '/js/:target_module/:target_action/*.pjs', array('module' => 'sfPJS', 'action' => 'index', 'target_action' => 'index'));
