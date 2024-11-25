<?php
include_once('dataCommon.php');
  /*
    * dataCommon.php comparte los datos mas relevantes y los 
    * objetos mas utilizados como session,adodb, etc.
    */	
  
  $mensaje0		= "Parametros incorrectos";	 
  $mensaje1		= "NO SE MODIFICO LA TRD DE NINGUN RADICADO";	 
				  
  /** Retorna los radicados a los cuales se le cambia la trd
    *  Cambio y registro en el historico de trd se los radicados
    *  seleccionados 	 
    */	
	  
  if (empty($depenUsua) || empty($selectTipoDoc) || empty($selectSubSerie)){
	  salirError ($mensaje0);
	  return;
  }		
  
  //Buscamos en la matriz el valor que une a la dependencia, serie, subserie, tipoDoc.
  $isqlTRD = "
  select 
  SGD_MRD_CODIGO
	  from 
	  SGD_MRD_MATRIRD
	  where 
      SGD_SRD_CODIGO 	= $selectSerie
      and SGD_ID_SUBSERIE = $selectSubSerie
      and SGD_TPR_CODIGO 	= $selectTipoDoc";
  //$db->conn->debug = true;
  $rsTRD = $db->conn->Execute($isqlTRD);			
 
  //Se crean dos variables por que la clase esta creada de esta manera
  //y no se cambiara en este momento.
  $codiTRDS[] = $codiTRD = $rsTRD->fields['SGD_MRD_CODIGO'];    
  //echo ">>>>".$codiTRD."<<<<<";
  //Proceso de asginacion de trd para los radicados que no tienen
  //echo ">>>>> ($radConTrd) && $cambExiTrd  <<<<<<<<<<<";
  if(!empty($radSinTrd)){
	  
    $radSinTrdArr= explode(",",$radSinTrd);		

    foreach ($radSinTrdArr as $value){										
      $trd->insertarTRD($codiTRDS,$codiTRD,$value,$depenUsua, $codusuario);
      // GUARDAR LA FECHA DE VENCIMIENTO SEGUN LA TRD SELECCIONADO
      $trd->setFechVenci($value,$selectTipoDoc);

	// insercion de radicado en expediente --- cyberdyne soluciones informaticas
        
        $Usql="select usua_doc from usuario where depe_codi=$depenUsua and usua_codi='$codusuario' ";
	$rusql= $db->conn->Execute($Usql);
	$usua_doc= $rusql->fields["USUA_DOC"];

        $sql1= "select sgd_exp_numero from sgd_sexp_secexpedientes where depe_codi=$depenUsua and sgd_sexp_ano= $date and sgd_srd_codigo=$selectSerie and sgd_sbrd_codigo=$selectSubSerie";
        


        $Rsql1=$db->conn->Execute($sql1);
        $numExp=$Rsql1->fields["SGD_EXP_NUMERO"];
        $resultadoExp = $expediente->insertar_expediente( $numExp, $value, $depenUsua, $codusuario, $usua_doc );
       
        if( $resultadoExp == 1 )
        {
            $observa = "Inclusion automatica radicado en Expediente";
            $radicados[] = $nurad;
            $tipoTx = 53;
            $Historico->insertarHistoricoExp( $numExp, $nurad,$coddepe, $codusua, $observa, $tipoTx, 0 );
        }
      
        /// fin insercion
			
      //guardar el registro en el historico de tipo documental.
      //permite controlar cambios del TD de un radicado
      
      $queryGrabar	= "INSERT INTO SGD_HMTD_HISMATDOC(											
			  SGD_HMTD_FECHA,
			  RADI_NUME_RADI,
			  USUA_CODI,
			  SGD_HMTD_OBSE,
			  USUA_DOC,
			  DEPE_CODI,
			  SGD_MRD_CODIGO)
			      VALUES(
			      $sqlFechaHoy,
			      $value,
			      $codusuario,
			      'El usuario: $usua_nomb Cambio el tipo de documento',
			      $usua_doc,
			      $depenUsua,
			      '$codiTRD')";
      //$db->conn->Execute($queryGrabar);
      
      //Actulizar la TD en el radicado					
      $upRadiTdoc	="UPDATE 
			      RADICADO
		      SET  
			      TDOC_CODI = $selectTipoDoc
		      WHERE 
			      radi_nume_radi =  $value";
      
      $db->conn->Execute($upRadiTdoc);
	    
    }
    $observa   = "Asignar TRD de forma masiva";
    
    $radiModi  = $Historico->insertarHistorico(	$radSinTrdArr,
					$depenUsua,
					$codusuario,
					$depenUsua,
					$codusuario,
					$observa,
					32);	
	  $result = $radSinTrd;		
  }			
	
	
  //Proceso de asginacion de trd para los radicados que SI tienen
  //y se quiere es modificar.
  
  
  if(!empty($radConTrd) && $cambExiTrd == 111){
	  
    $radConTrdArr		= explode(",",$radConTrd);		
    
    foreach ($radConTrdArr as $radicadoCon){
	    
    //Buscamos los datos anteriores de la trd y los
    //colocamos en el mensaje del historico
    
    $sqlhis="	SELECT
		  SE.SGD_SRD_DESCRIP ||
		  '/'|| SU.SGD_SBRD_DESCRIP ||
		  '/'|| TD.SGD_TPR_DESCRIP AS TRD_ANTERIOR
		FROM
		  SGD_RDF_RETDOCF      SG
		,SGD_MRD_MATRIRD      MR
		,SGD_SBRD_SUBSERIERD  SU
		,SGD_SRD_SERIESRD     SE
		,SGD_TPR_TPDCUMENTO   TD
		WHERE
		  SG.RADI_NUME_RADI      = $radicadoCon
		  AND MR.SGD_MRD_CODIGO  = SG.SGD_MRD_CODIGO
		  AND MR.SGD_ID_SUBSERIE = SU.SGD_ID_SUBSERIE
		  AND MR.SGD_ID_SERIE  = SU.SGD_ID_SERIE
		  AND MR.SGD_ID_SERIE  = SE.SGD_ID_SERIE
		  AND MR.SGD_TPR_CODIGO  = TD.SGD_TPR_CODIGO";
    
    $resultHis	= $db->conn->Execute($sqlhis);			
    $histTrd 	= $resultHis->fields['TRD_ANTERIOR'];			

    $sqlUA 		= "	UPDATE 
					    SGD_RDF_RETDOCF 
				    SET 
					    SGD_MRD_CODIGO 	= '$codiTRD',
				    USUA_CODI 		= '$codusuario'
			    WHERE 
			      RADI_NUME_RADI 	= '$radicadoCon' 
			      AND DEPE_CODI 	= '$depenUsua'";
					    
    $rsUp 		= $db->conn->query($sqlUA);
	    
    //guardar el registro en el historico de tipo documental.
    //permite controlar cambios del TD de un radicado
    
    $queryGrabar	= "INSERT INTO SGD_HMTD_HISMATDOC(											
			SGD_HMTD_FECHA,
			RADI_NUME_RADI,
			USUA_CODI,
			SGD_HMTD_OBSE,
			USUA_DOC,
			DEPE_CODI,
			SGD_MRD_CODIGO
			)
			  VALUES(
			  $sqlFechaHoy,
			  $radicadoCon,
			  $codusuario,
				  'El usuario: $usua_nomb Cambio el tipo de documento',
				  $usua_doc,
				  $depenUsua,
				  '$codiTRD')";					
    
    //$db->conn->Execute($queryGrabar);	
    
    //Actulizar la TD en el radicado					
    $upRadiTdoc	=	"UPDATE 
			  RADICADO
			  SET  
			    TDOC_CODI = $selectTipoDoc
			  WHERE 
			    radi_nume_radi = $radicadoCon";
    
    $db->conn->Execute($upRadiTdoc);										
    }			
    
    $observa 	= "	Cambio masivo TRD por: Usuario: $usua_nomb - Dependencia: $depenUsua
				    TRD Anterior: $histTrd";
    
    $radiModi 	= $Historico->insertarHistorico(
			    $radConTrdArr
			    ,$depenUsua
			    ,$codusuario
			    ,$depenUsua
			    ,$codusuario
			    ,$observa
			    ,32);	
    $result 	.= $radConTrd;
						  
  }			
	
	$result = (empty($result))? $mensaje1 : $result;
	
	$accion= array( 'respuesta' => true,
					'mensaje'	=> $result);
	print_r(json_encode($accion));
?>
