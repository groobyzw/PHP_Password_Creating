<?php

    //  Class for password generating from random characters, 
    //  password characters depending on chosen mode, will or wont repeat themselves.
    //  Password max. length depends on ASCII range of digits.

    //  033-047    !"#$%'()*+,-_/   [14]
    //  048-057    0-9              [10]
    //  058-064    :;<=>?@          [7]
    //  065-090    A-Z              [26]
    //  091-096    [\]^_`           [6]
    //  097-122    a-z              [26]
    //  123-126    {|}~             [4]

    define('DEF_ERR_ARGS','Invalid Arguments');
    define('DEF_ERR_MODE','Invalid Mode');
    define('DEF_ERR_LEN',"Invalid Password's Length");

    class Generate{

        public $result;
        protected $active_modifiers;
        protected $max_length;
        protected $pass_length;
        protected $mode;

        protected function setModifiers(){
            return [

                // 0 => ASCII min value,
                // 1 => ASCII max value ,
                // 2 => amount of chars 

                'all'=>[33,126,93],
                'spec0'=>[33,47,14],
                'ints'=>[48,57,10],
                'spec1'=>[58,64,7],
                'alphU'=>[65,90,26],
                'spec2'=>[91,96,6],
                'alphL'=>[97,122,26],
                'spec3'=>[123,126,4]];
        }

        protected function activeModifiers($modifiers){

            $mods = $this->setModifiers();            

            if(is_array($modifiers) && !empty($modifiers)){

                for($i =0;$i >= count($modifiers);$i++){
                    if(!array_key_exists($modifiers[$i],$mods)){
                        throw new Exception(DEF_ERR_ARGS);
                    }
                }

                foreach($mods as $name=>$value){
                    if(in_array('all',$modifiers)){
                        $this-> active_modifiers = NULL;
                        return $this->active_modifiers[$name] = $value;
                    } elseif (in_array($name,$modifiers)){
                        $this->active_modifiers[$name] = $value;
                    }
                }

                        return $this->active_modifiers;

            } else {
                throw new Exception(DEF_ERR_ARGS);
            }

        }

        protected function setMode($value){
            if($value == 'unique' || $value == 'repeat'){
                $this->mode = $value;
            } else {
                throw new Exception(DEF_ERR_MODE);
            }
        }

        protected function setMaxLength($length){

            if($length > 0){
                
                ($length <= 500)? $this->pass_length = $length : $this->pass_length = '500' ;
                $this->max_length = 500;
            } else {

                foreach($this->active_modifiers as $name=>$value){
                    if(isset($value[2])){
                        $this->max_length += $value[2];
                    }
                }

            }
            
        }

        protected function setLength($length = 0){
          
            switch($this->mode){
                case 'unique':
                    $this->setMaxLength($length);
                  break;
                case 'repeat':
                    $this->setMaxLength($length);
                    return;
                  break;
            }
            
                

            if($length == 0){
                $this->pass_length = $this->max_length;
            } elseif(($length != 0) && ($this->max_length >= $length)){
                $this->pass_length = $length;
            } else {
                throw new Exception(DEF_ERR_LEN);
            }

        }

        protected function retRandMod(){
            shuffle($this->active_modifiers);
            return ['min'=>$this->active_modifiers[0][0],'max'=>$this->active_modifiers[0][1]];
        }

        protected function randomPassword($mode){

            $password = '';
            $arr_cnt = 1;
            $arr = [];

            switch($mode){
                case 'unique':

                    while($arr_cnt < $this->pass_length){

                        $mods = $this->retRandMod();
        
                            $char = chr(rand($mods['min'],$mods['max']));
        
                            if(!in_array($char,$arr)){
                            
                                $password .= $char;
                            
                                $arr_cnt++;
                            
                            }
            
                    }

                  break;
                case 'repeat':

                    while($arr_cnt <= $this->pass_length){

                        $mods = $this->retRandMod();
        
                            $char = chr(rand($mods['min'],$mods['max']));

                                $password .= $char;
                            
                                ++$arr_cnt;

                    }

                  break;
            }


            return htmlspecialchars($password);

        }

        public function generatePassword($modifiers = [],$mode,$amount = 1,$length = 0){

            try{

                $this->setMode($mode);
                $this->activeModifiers($modifiers);
                $this->setLength($length);

                for($i = 0;$i < $amount;$i++){      
                    $this->result[] = $this->randomPassword($mode);
                }

            } catch(Exception $e) {
                $this->result = $e->getMessage();
            }

            return $this->result;

        }

    }

    class ViewController{

        public function show_input(){
            ?>

            <div class="content">
                <form action="" method="POST">
                    <table class="user_table">
                        <tr><td><label for="q0">Special characters</label></td>
                            <td><input  type="checkbox" id="q0" name="special" ></td></tr>
                        <tr><td><label for="q1">Numbers</label></td>
                            <td><input  type="checkbox" id="q1" name="numbers" ></td></tr>
                        <tr></td><td><label for="q2">Lower Case</label></td>
                            <td><input type="checkbox" id="q2" name="lower_case" ></tr>
                        <tr></td><td><label for="q3">Upper Case</label></td>
                            <td><input  type="checkbox" id="q3" name="upper_case" ></tr>

                            <tr><td><label for="q4">Password Length</label></td>
                        <td><input type="text" id="q4" value="" name="length"></td></tr>
                            <tr><td><label for="q4">Password amount's</label></td>
                        <td><input type="text" id="q4" value="" name="amount" ></td></tr>
                            <tr><td></td><td><input type="submit" name="generate"></td></tr>

                            </table>
                </form>



                </div>

            <?php
        }

        public function show_output($data){
            ?>
                <div class="content">
                    <textarea id="passwords" style="width:740px; height:320px;"class="user_text_area"><?php foreach($data as $n=>$v) echo $v.'<br><br>' ; ?></textarea>
                </div>
            <?php
        }

    }

    $passw = new Generate;
    $view = new ViewController;

    if(isset($_POST['generate'])){
        $result = $passw->generatePassword(['all'],'unique',100000,80);
        $view->show_output($result);
    } else {
        $view ->show_input();
    }








    //$passw->generatePassword(['spec0','ints','spec1']);
    //echo '<pre>';
    //var_dump($passw);


    //echo $passw['result'];

?>