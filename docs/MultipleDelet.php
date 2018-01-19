<?php 

	//(case A) User fields indexed by number 0,1,2..
    $users_to_delete = array(
       '0'=> array('1','Frank','Smith','Whatever'), 
       '1'=> array('5','John','Johnson','Whateverelse'),
    );

    $ids_to_delete = array_map(function($item){ return $item[0]; }, $users_to_delete);

    DB::table('users')->whereIn('id', $ids_to_delete)->delete(); 

    //(case B) User fields indexed by key
    $users_to_delete = array(
       '0'=> array('id'=>'1','name'=>'Frank','surname'=>'Smith','title'=>'Whatever'), 
       '1'=> array('id'=>'5','name'=>'John','surname'=>'Johnson','title'=>'Whateverelse'),
    );

    $ids_to_delete = array_map(function($item){ return $item['id']; }, $users_to_delete);

    DB::table('users')->whereIn('id', $ids_to_delete)->delete(); 


    /**
     * PARA VER SI EXISTE O NO OS DADOS NO BANCO DE DADOS
     */
    
    $answer = Item::where('slug', $value)->exists(); // this returns a true or false
?>