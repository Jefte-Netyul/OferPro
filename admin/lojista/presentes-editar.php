﻿<?php require_once('../verificar-login.php');?>
<?php require_once('../../Connections/dboferapp.php'); ?>
<?php
require_once('../../sistema/classes/W3_Image.class.php');
require_once('../../sistema/constantes.php');
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
$colname_Presenteseditar = "-1";
if (isset($_GET['id'])) {
  $colname_Presenteseditar = $_GET['id'];
}
mysql_select_db($database_dboferapp, $dboferapp);
$query_Presenteseditar = sprintf("SELECT * FROM presentes WHERE id_lojista = %s", GetSQLValueString($colname_Presenteseditar, "int"));
$Presenteseditar = mysql_query($query_Presenteseditar, $dboferapp) or die(mysql_error());
$row_Presenteseditar = mysql_fetch_assoc($Presenteseditar);
$totalRows_Presenteseditar = mysql_num_rows($Presenteseditar);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$imagemTemp = $_FILES['img']['tmp_name'];
	$imagetype = explode('/', $_FILES['img']['type']);
	if($imagetype[1] =='jpeg'){
		$imagetype[1] = 'jpg';
	}
	if(!empty($imagemTemp)){
		$imgperfil = IMGPRESENTES;
		$editeImg = explode('.',$row_Presenteseditar['img']);
		$img = new W3_Image;
		$img->create($imagemTemp, 218, 147,'../../'.$imgperfil. $editeImg[0].'.thumb.'.$imagetype[1]);
		$img->create($imagemTemp, 570, 450,'../../'.$imgperfil. $editeImg[0].'.'. $imagetype[1]);
	}
  $updateSQL = sprintf("UPDATE presentes SET titulo=%s, descricao=%s, img=%s, datatermino=%s WHERE id=%s",
                       GetSQLValueString($_POST['titulo'], "text"),
                       GetSQLValueString($_POST['descricao'], "text"),
                       GetSQLValueString($editeImg[0].'.'.$imagetype[1], "text"),
                       GetSQLValueString(date('d/m/Y', strtotime($_POST['datatermino'])), "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_dboferapp, $dboferapp);
  $Result1 = mysql_query($updateSQL, $dboferapp) or die(mysql_error());

  $updateGoTo = "presentes.php?id=" . $row_Presenteseditar['id_lojista'] . "&action=editado";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


?>
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="pt" dir="ltr"><!-- InstanceBegin template="/Templates/ModeloOferappAdm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administração da OferApp</title>
<!-- InstanceEndEditable -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="../css/oferapp.css" rel="stylesheet" type="text/css">
<link href="../css/oferapp-boilerplate.css" rel="stylesheet" type="text/css" />
<link href="../css/oferapp-admin.css" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/respond.min.js"></script>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
</div>
<header>
    <div class="container" >
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" >
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo BASEURL; ?>"><img  src="../images/logo.png" alt="OferApp Ofertas de Produtos e serviços mais proximo de você" title="OferApp Ofertas de Produtos e serviços mais proximo de você"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                	<?php 
					$level = LEVEL;
					if($level == 'superadmin'){
					?>                 
                    <li><a href="<?php echo BASEURL; ?>/admin/cidade">Cidades</a></li>
                    <li><a href="<?php echo BASEURL; ?>/admin/administradores">Administradores</a></li>
                    <?php } ?>
                    <li><a href="<?php echo BASEURL; ?>/admin/lojista">Lojistas</a></li>
                    <li class="dropdown ">
                        <a href="#" class="dropdown-toggle cadastrar" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo ADMNOME; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo BASEURL; ?>/admin/logout">sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav><!-- /.container-fluid -->
       
    </div>
</header><!--/#header-->
<main>
    <div class="container">
        <div class="area principal">
            <div class="top page-header">
            <!-- InstanceBeginEditable name="tituloPagina" -->
            <?php 
				
			?>
            <h2> <span class="glyphicon glyphicon-bookmark icon-destaque"></span>Administração</h2>
            <?php ?>
            <!-- InstanceEndEditable -->
            </div>
            <div class="row">
            <!-- InstanceBeginEditable name="conteudo" -->
             <div class="col-md-12" style="padding:5px;" align="right">
            	
            	<a href="ofertas.php?id=<?php echo $colname_presentes; ?>" class="btn btn-Oferapp">Ofertas</a>
              	<a href="" class="btn btn-Oferapp">Tabloides</a>
                <a href="presentes.php?id=<?php echo $colname_presentes; ?>" class="btn btn-Oferapp">Presentes</a>
                
            </div>
            <div class="col-sm-12">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
            <div class="panel panel-default">
                	<div class="panel-heading">Editar Presente</div>
                    <div class="panel-body">
                      <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal">
                        <div class="form-group">
                           <label class="col-sm-4 control-label" >Titulo do Presente:</label>
                           <div class="col-sm-8">
                            <input class="form-control" type="text" name="titulo" value="<?php echo htmlentities($row_Presenteseditar['titulo'], ENT_COMPAT, 'utf-8'); ?>" required>
                          </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-12">Descricao:</label>
                          </div>
                          <div class="form-group">
                          <div class="col-sm-12">
                          <textarea class="col-sm-12 form-control" name="descricao" required><?php echo htmlentities($row_Presenteseditar['descricao'], ENT_COMPAT, 'utf-8'); ?></textarea>
                          </div>
                          </div>
                         <div class="form-group">
                            <label class="col-sm-4 control-label">Imagen:</label>
                            <div class="col-sm-7">
                            <input class="form-control" type="file" name="img" value="<?php echo htmlentities($row_Presenteseditar['img'], ENT_COMPAT, 'utf-8'); ?>" required>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-4 control-label">Data do termino:</label>
                            <div class="col-sm-4">
                            <input type="date" name="datatermino" value="<?php echo date('Y-m-d', strtotime(str_replace('/','-', $row_Presenteseditar['datatermino']))); ?>" size="32" required>
                            </div>
                            </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                          </tr>
                        </table>
                        <input type="hidden" name="MM_update" value="form1">
                        <input type="hidden" name="id" value="<?php echo $row_Presenteseditar['id']; ?>">
                      </form>
                      <p>&nbsp;</p>
                    </div>
              </div>
            </div>
            <div class="col-md-2">
            </div>
            </div>
            
            <!-- InstanceEndEditable -->
            </div>
        </div>
    </div>
</main>
<footer>
<!-- InstanceBeginEditable name="footer" -->
 <div class="footer"></div>

</footer>
</body>
<!-- InstanceEndEditable -->
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Presenteseditar);
?>
