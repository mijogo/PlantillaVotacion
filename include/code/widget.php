<?php
function widget()
{
	$text = "";
	$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Widget 1 
            </div>
            <div class=\"panel-body\">
				<ul >
					<li>
						Texto del widget
					</li>                
                </ul>
             </div>
             <div class=\"panel-footer\">
			  	<a href=\"#\" class=\"btn btn-default btn-block\"> 
				<i class=\"glyphicon glyphicon-repeat\"></i> Boton opcional</a>
             </div>
		</div>
    </div>";
	
		$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Widget 2
            </div>
            <div class=\"panel-body\">
				<ul >
					<li>
						Texto del widget
					</li>                
                </ul>
             </div>
		</div>
    </div>";
	
	return $text;	
}
?>