<?php 
     function mod_blank_blank() { 
          global $NeptuneCore; 
          $NeptuneCore->var_set("theme","altlayout","layout_blank"); 
     }
     $NeptuneCore->hook_function("blank","blank","blank");
?> 