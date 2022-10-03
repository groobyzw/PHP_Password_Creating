# PHP_Password_Creating
PHP OOP Password creating class

  Hello, this is class to generate a string of random characters,
  it can be run in two modes and make output in two possible ways,
  the string where characters are unique, or they are repeating.


          The class can be called like this : 
    
        $modifiers = Possible Modifiers:
        - all                   - all characters possible.
        - spec0,spec1,spec,2    - special characters.
        - ints                  - integrers from 0 to 9.
        - alphL,alphU           - alpahbetic signs upper lower and upper case.
        
        $mode = 'unique' | 'repeat' - there are two possible modes :
         - unique - signs wont repeat in string.
         - repeat - signs will repeat in string.
         
        $amount = amount of passwords to generate, integrer between 1 and xxx.
        
        $length = length of string to generate, if its 'unique' mode, it will be 93 characters,
        otherwise i set max length to 500 characters, but its can be freely modified. 

        $result = $passw->generatePassword([$modifiers],$mode,$amount,$length);

          echo '<pre>':
            var_dump($result);
          echo '</pre>';

       Example: 
       
       $result = $passw->generatePassword(['all'],'unique','3','64');

          echo '<pre>':
            var_dump($result);
          echo '</pre>';
