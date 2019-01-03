<div class="card" >
  <div class="card-body">
    <h3 class="card-title">
    
  <?php echo anchor('Welcome/index','Kaposvári Egyetem, Egyetemi Könyvtár fénymásoló kártya generáló program');?></h3>
    <div class="alert alert-secondary" role="alert">Kérem adja meg miből mennyit szeretne generálni!</div></div>
</div>
</div>
<div class="row">
<div class="col-sm-3"> 

    <?php
    if(isset($tizes))
{
  echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Tízesből ".count($tizes)." generálva!    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";  
}
    if(isset($huszas))
{
    echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>Húszasból ".count($huszas)." generálva!    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>"; 
}
    if(isset($otvenes))
{
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Ötvenesből ".count($otvenes)." generálva!    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>"; 
}
    ?>
</div>    
<div class="col-sm-3">    
	<?php
	$this->load->helper('form');
	echo form_open('Welcome/generate');
	$data = array(
		'type'=>'number',
        'name'  => 'tizes',
        'id'    => 'tizes',
		'placeholder'=>"10 egység fénymásolása",
		'class'=>"form-control"
);
echo '<div class="input-group ">';
echo form_input($data)."db</div>";	
$data = array(
		'type'=>'number',
        'name'  => 'huszas',
        'id'    => 'huszas',
		'placeholder'=>"20 egység fénymásolása",
		'class'=>"form-control"
);
echo '<div class="input-group ">';
echo form_input($data)."db</div>";	
$data = array(
		'type'=>'number',
        'name'  => 'otvenes',
        'id'    => 'otvenes',
		'placeholder'=>"50 egység fénymásolása",
		'class'=>"form-control"
);
echo '<div class="input-group ">';
echo form_input($data)."db</div>";
$attributes = array(
					'class' => 'btn btn-info',
					);
				  echo form_submit("mehet", "Generálj",$attributes);
				  echo "<br>";
    echo "</div>
<div class='col-sm-3'> ";
if(isset($fileName)) 
{
     echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>A fájlt a következő helyről lehet <a href=".base_url()."csvk/".$fileName." target='_blank'>letölteni</a>.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>"; 
}
    echo "</div></div><div class='row'><div class='col-sm-3'></div><img src=".base_url()."img/copycat.gif style='width:10%;height:10%;' class='rounded-circle' /></div>";
?>	
