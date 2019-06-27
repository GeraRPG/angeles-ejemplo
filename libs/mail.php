<?php
class mails{
        private $to_Email;
        private $subject;
        private $contenido;
        private $headers;
        private $from;
        public function __construct()
        {
                $this->to_Email = "";
                $this->subject = "";
                $this->contenido = "";
                $this->headers = "";
                $this->from = "";
        }
        public function datosMail($to_Email="", $subject="")
        {
                $this->to_Email = $to_Email;
                $this->subject = $subject;
        }
        public function datosMailUser($to_Email="",$subject="",$from="")
        {       
                $this->to_Email = $to_Email;
                $this->subject = $subject;
                $this->from = $from;
        }
        public function contenido($data)
        {
                $arreglo = $data;
                $menssage_Body = "<strong>Datos enviados</strong>";
                for ($i=0; $i <count($arreglo) ; $i++) {
                        $menssage_Body .= $arreglo[$i];
                }
                $this->contenido = $menssage_Body;
        }
        public function contenidofile($data, $file,$nameFile,$sizeFile,$typeFile,$tempFile)
        {
                $arreglo = $data;
                $menssage_Body = "--=C=T=E=C=\r\n";
                $menssage_Body .= "Content-type:text/plain; charset=utf-8\r\n";
                $menssage_Body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $menssage_Body .= "\r\n";
                for ($i=0; $i <count($arreglo) ; $i++) {
                $menssage_Body .= $arreglo[$i];
                }
                $menssage_Body .= "--=C=T=E=C=\r\n";
                $menssage_Body .= "Content-Type: application/octet-stream; ";
                $menssage_Body .= "name=" . $nameFile . "\r\n";
                $menssage_Body .= "Content-Transfer-Encoding: base64\r\n";
                $menssage_Body .= "Content-Disposition: attachment; ";
                $menssage_Body .= "filename=" . $nameFile . "\r\n";
                $menssage_Body .= "\r\n"; 
                $fp = fopen($tempFile, "rb");
                $archivo = fread($fp, $sizeFile);
                $archivo = chunk_split(base64_encode($archivo));
                $menssage_Body .= "$archivo\r\n";
                $menssage_Body .= "\r\n"; // línea vacía
                // Delimitador de final del mensaje.
                $menssage_Body .= "--=C=T=E=C=--\r\n";
                $this->contenido = $menssage_Body;
        }
        public function user_mail($mail="",$val="")
        {
                if ($val == "html")
                {
                        $this->headers($mail);
                } else if ($val == "archivo")
                {
                        $this->headerfile($mail);
                }
        }
        private function headerfile($user_mail)
        {
                $headers = "From: " . strip_tags($user_mail) . "\r\n";
                $headers .= "Reply-To: ". strip_tags($user_mail) . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: multipart/mixed;";
                $headers .= "boundary=\"=C=T=E=C=\"\r\n";
                $this->headers = $headers;
        }
        private function headers($user_mail)
        {
                $headers = "From: " . strip_tags($user_mail) . "\r\n";
                $headers .= "Reply-To: ". strip_tags($user_mail) . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $this->headers = $headers;
        }
        public function enviarMAil()
        {
                $sentMail = @mail($this->to_Email,$this->subject,$this->contenido,$this->headers);
                if (!$sentMail)
                {
                        $bandera = false;
                }else
                {
                        $bandera = true;
                }

                return $sentMail;
        }
}