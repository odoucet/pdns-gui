<?php
sfPropelBehavior::registerHooks('audit', array(
    ':delete:post' => array(
        'sfPropelAuditBehavior',
        'postDelete'
    ) ,
    'Peer:doInsert:post' => array(
        'sfPropelAuditBehavior',
        'postDoInsert'
    ) ,
    'Peer:doUpdate:post' => array(
        'sfPropelAuditBehavior',
        'postDoUpdate'
    ) ,
));
