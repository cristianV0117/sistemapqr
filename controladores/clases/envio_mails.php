<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase mail
 * Se incluyen:
 * El archivo PHPMailerAutoload.
 *
 */
require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/PHPMailerAutoload.php';

class mail
{

  /**
   * @property host null
  */
  private $host;
  /**
   * @property correoFuente null
  */
  private $correoFuente;
  /**
   * @property puerto null
  */
  private $puerto;
  /**
   * @property contrasena null
  */
  private $contrasena;
  /**
   * @property SMTP null
  */
  private $SMTP;

  /**
   *
   * Se declara las propiedades @property:$this->host,@var:$this->correoFuente,@var:$this->contrasena,@var:$this->puerto,@var:$this->SMTP
   * Se ejecuta el @method:$this->envioMail(argumentos).
   *
   */
  public function __construct($emailUsuario,$mensaje,$asunto,$datos = null,$occ = null)
  {
    try 
    {
      $this->host         = 'zk.com.co';
      $this->correoFuente = 'no-responder@zk.com.co';
      $this->contrasena   = 'RB60Ne^2UOr)';
      $this->puerto       = 465;
      $this->SMTP         = 'ssl';
      $this->envioMail($emailUsuario,$mensaje,$asunto,$datos,$occ);
    } catch (\Exception $e) 
    {
      	die('Excepción capturada: ' . $e->getMessage() . "\n");
    }
  }

  /**
   *
   * @method:envioMail(pametros) Se encarga del envio de los mails, Las caracteristicas de los mails dependeran de la informacion que llegue al metodo.
   * @param:$emailUsuario string.
   * @param:$mensaje string.
   * @param:$asunto string.
   * @param:$datos string || null.
   * @param:$occ array || null.
   * @var:$mail:Almacena la clase PHPMailer.
   * Se ejecuta el metodo isSMTP().
   * Se hace uso de las propiedades para el envio de los mails.
   * @return:$arreglo.
   *
   */
  private function envioMail($emailUsuario,$mensaje,$asunto,$datos,$occ)
  {
    try 
    {

      $mail = new PHPMailer;
      $mail->isSMTP();
      $mail->Host = $this->host;
      $mail->SMTPAuth = true;
      $mail->Username = $this->correoFuente;
      $mail->Password = $this->contrasena;
      $mail->SMTPSecure = $this->SMTP;
      $mail->Port = $this->puerto; 
      $mail->setFrom($this->correoFuente,$this->correoFuente);
      $mail->addAddress($emailUsuario, $emailUsuario);
      $mail->isHTML(true);
      $mail->Subject = $asunto;
      $mail->Body    = $mensaje;
      $mail->AltBody = 'Saludos ZK';
      // Si $occ no esta vacio se adjuntan los valores del array los cuales son emails para el envio tipo copia oculta.
      if (!empty($occ)) 
      {
        for ($i=0; $i < count($occ) ; $i++) 
        { 
          $mail->addBCC($occ[$i]);
        }
      }
      // Envio del respectivo mail.
      if(!$mail->send()) 
      {
          $arreglo = array(
            "respuesta" => $datos,
            "funcion" => 1,
            "mensaje" => $mail->errorInfo()
          );
          print_r(json_encode($arreglo));
      } else 
      {
         $arreglo = array(
            "respuesta" => $datos,
            "funcion" => 1,
            "mensaje" => 'bien'
         );
         print_r(json_encode($arreglo));
      }
    } catch (\Exception $e) 
    {
      die('Excepción capturada: ' . $e->getMessage() . "\n");
    }
  }
}
