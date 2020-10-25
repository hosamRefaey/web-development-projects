<?php
function lang($phrase){
  static  $eng=array(
        'ECOMMERCE SHOP' => 'ECOMMERCE SHOP',
        'CATEGOREIS'     => 'Categoreis',
        'ITEMS'          => 'Items',
        'STATISTCS'      => 'Statistcs',
        'LOGS'           => 'Logs',
        'MEMBERS'        => 'Members',
        'COMMENTS'       => 'Comments'   
    );
    return $eng[$phrase];
}
?>