<?php
// RISOLVERE I COMMENTI DELLE FUNZIONI
if(!class_exists("mia_mail")){
class mia_mail {

    // proprieta' in ordine alfabetico
	public $From;
	public $ReplyTo;
	public $ReturnPath;
	public $FromName;
	public $ReplyToName;
	public $html="";
	public $Subject="";
	public $text="";
	public $firma=false;
	public $SSL=false;
	
	protected $altsep;
	protected $BCC=array();
	protected $body="";
	protected $CC=array();
	protected $fineriga;
	protected $files=array();
	protected $filesinline=array();
	protected $filetype=array();
	protected $first=true;
	protected $header="";
	protected $imagescid=array();
	protected $imagesinline=array();
	protected $imagesnames=array();
	protected $imagestype=array();
	protected $mixedsep;
	protected $relsep;
	protected $result;
	protected $sep;
	protected $smtp_pass;
	protected $smtp_port;
	protected $smtp_server;
	protected $smtp_timeout;
	protected $smtp_user;
	protected $immaginefirma;
	protected $testofirma;
	protected $html_header;
	protected $html_footer;
	protected $TO=array();

	// Metodi 
	public function __construct() {
		$this->sep=md5(time());
		$this->mixedsep="my-mixed-".$this->sep;
		$this->altsep="my-alt-".$this->sep;
		$this->relsep="my-related-".$this->sep;
		$this->fineriga="\r\n";
		$this->smtp_server="localhost";
		$this->smtp_port="25";
		$this->smtp_user="****";
		$this->smtp_pass="****";
		$this->smtp_timeout="30";
		$this->FromName="*****";
		$this->From="****";
		$this->ReplyTo="*****";
		$this->ReplyToName="*****";
		$this->ReturnPath="*****";
		$this->immaginefirma="";
		$this->testofirma="";
		$this->html_header="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">\n</head>\n<body>";
		$this->html_footer="</body></html>";
		$this->SSL=false;
	}
	/**
	* Configura l account per autenticarsi sul server SMTP
 	* 
	* @param string server Nome del server smtp
 	* @param string port   Numero porta SMTP
 	* @param string account Nome utente
	* @param string password Password
	* @param string timeout timeout del server
 	* @return void 
 	*/
	public function setAccount($server,$port,$account,$password,$timeout="30") {
		if ($port=="465") {
			$this->SSL=true;
		}
		$this->smtp_server=$server;
		$this->smtp_port=$port;
		$this->smtp_user=$account;
		$this->smtp_pass=$password;
		$this->smtp_timeout=$timeout;
	}

	
	/**
	* Allega un immagine in linea
 	* 
	* @param string $FileName Path del file da allegare
 	* @param string $Type Mimetype del file da allegare 
 	* @return void 
 	*/
	public function ImmagineInLinea($FileName,$Type) {
		$idimg=basename(str_replace(" ","",$FileName));
		$this->imagesinline[$idimg]=chunk_split(base64_encode(file_get_contents($FileName)));
		$this->imagestype[$idimg]=$Type;
		$this->imagescid[$idimg]="cid:my-img-$idimg-".$this->sep;
		$this->imagesnames[$idimg]=$FileName;
	}

	/**
	* Allega un file in linea
 	* 
	* @param string $FileName Path del file da allegare
 	* @param string $Type Mimetype del file da allegare 
 	* @return void 
 	*/
	public function AllegaInLiena($FileName,$Type) {
		//$this->filesinline[$FileName]=file_get_contents($FileName);
		$FileName2=basename($FileName);
		$this->filesinline[$FileName2]=chunk_split(base64_encode(file_get_contents($FileName)));
		$this->filetype[$FileName2]=$Type;
	}
	
	/**
	* Aggiunge un destinatario in Carbon Copy
 	* 
	* @param string Mail mail del destinatario
 	* @param string Name   Nome del destinatario
 	* @return void 
 	*/
	public function addCC($Mail,$Name="") {
		$this->CC[$Mail]=$Name;
	}

	/**
	* Aggiunge un destinatario in Blind Carbon Copy
 	* 
	* @param string Mail mail del destinatario
 	* @param string Name   Nome del destinatario
 	* @return void 
 	*/
	public function addBCC($Mail,$Name="") {
		$this->BCC[$Mail]=$Name;
	}

	/**
	* Aggiunge un destinatario
 	* 
	* @param string Mail mail del destinatario
 	* @param string Name   Nome del destinatario
 	* @return void 
 	*/
	public function addTO($Mail,$Name="") {
		$this->TO[$Mail]=$Name;
	}

	/**
	* Allega un file alla mial
 	* 
	* @param string Name path del file
 	* @param string Type Mime type del file.
 	* @return void 
 	*/
	public function AllegaFile($Name,$Type,$NewName="") {
		$Name2 = $NewName!="" ? $NewName : addslashes(basename($Name));
		$this->files[$Name2]= chunk_split(base64_encode(file_get_contents($Name)));
		$this->filetype[$Name2]= $Type;
	}
	
	/**
	* Allega un file alla mail partendo da una stringa
 	* 
	* @param string Name path del file
 	* @param string Type Mime type del file.
	* @param string Data Contenuti del file.
 	* @return void 
 	*/
	public function AllegaFileDaStringa($Name,$Type,$Data) {
		$Name2=addslashes(basename($Name));
		$this->files[$Name2]= chunk_split(base64_encode($Data));
		$this->filetype[$Name2] = $Type;
	}
	/**
	* Invia la mail
 	* 
	* @return array con il log di quello che e' successo 
 	*/
	public function Invia() {
		$this->createHeader();
		if ($this->firma) {
			$this->addfirma("ciao");
			$this->firma=false;
		}
		$this->processHtml();
		$this->createBody();
		return $this->authSendEmail();
	}

	// le funzioni protected non si possono chiamare da fuori la classe, ma sono solo per uso interno.
	
	/**
	* Crea la parte testo della Mail a partire dall html
 	* 
	* @return string 
 	*/
	private function createTextFromHtml() {
		$temptxt=str_replace("<br />","\n",$this->html);
		$temptxt=htmlspecialchars_decode($temptxt);
		$temptxt=strip_tags($temptxt);
		return $temptxt;
	}

	/**
	* Aggiunge in fondo al messaggio html la parte della firma.
 	* 
	* @return string 
 	*/
	private function addfirma() {
		
		$firma="<table><tr><td><img src=\"".$this->immaginefirma."\" ></td><td style=\"text-align:left;vertical-align:top;\">".$this->testofirma."</td></tr></table>";

		$this->ImmagineInLinea($this->immaginefirma,"image/png");
		$this->html.=$firma;
	}
	
	protected function processHtml() {
		$this->html=str_replace($this->imagesnames,$this->imagescid,$this->html);
		$this->html=str_replace("<br />","<br />\n",$this->html);
		$this->html=str_replace("<br/>","<br />\n",$this->html);
		$this->html=str_replace("<br>","<br />\n",$this->html);
	}

	protected function authSendEmail() {
	
		$logArray=array();
		$output="";
		$this->result=true;
		if($this->SSL) {
			$this->smtp_server="ssl://".$this->smtp_server;
		}
		//Connect to the host on the specified port
		$smtpConnect = fsockopen($this->smtp_server, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);
		$smtpResponse = fgets($smtpConnect, 515);
		if(!$smtpConnect) {
			$logArray['connection'] = "Failed to connect: $smtpResponse $smtpConnect";
			$this->result=false;
		} else {
			$logArray['connection'] = "Connected: $smtpResponse";
			
			//Say Hello to SMTP
			fputs($smtpConnect, "EHLO localhost" . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['heloresponse'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="250"){
				$this->result=false;
			}

			//Request Auth Login
			fputs($smtpConnect,"AUTH LOGIN" . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['authrequest'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="334"){
				$this->result=false;
			}
		
			//Send username
			fputs($smtpConnect, base64_encode($this->smtp_user) . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['authusername'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="334"){
				$this->result=false;
			}

			//Send password
			fputs($smtpConnect, base64_encode($this->smtp_pass) . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['authpassword'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="235"){
				$this->result=false;
			}

		
			//Email From
			fputs($smtpConnect, "MAIL FROM: <".$this->From.">".$this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['mailfromresponse'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="250"){
				$this->result=false;
			}
		
			//Email To
			foreach ($this->TO as $key=>$value) {
				fputs($smtpConnect, "RCPT TO: <".$key.">".$this->fineriga);
				$smtpResponse = fgets($smtpConnect, 515);
				@$logArray['rcpttoresponse1'].=$smtpResponse;
				if(substr($smtpResponse,0,3)!="250"){
					$this->result=false;
				}
			}

			foreach ($this->CC as $key=>$value) {
				fputs($smtpConnect, "RCPT TO: <".$key.">".$this->fineriga);
				$smtpResponse = fgets($smtpConnect, 515);
				@$logArray['rcpttoresponse2'].=$smtpResponse;			
				if(substr($smtpResponse,0,3)!="250"){
					$this->result=false;
				}
			}		

			foreach ($this->BCC as $key=>$value) {
				fputs($smtpConnect, "RCPT TO: <".$key.">".$this->fineriga);
				$smtpResponse = fgets($smtpConnect, 515);
				@$logArray['rcpttoresponse3'].=$smtpResponse;
				if(substr($smtpResponse,0,3)!="250"){
					$this->result=false;
				}
			}	
			
			//The Email
			fputs($smtpConnect, "DATA" . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['data1response'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="354"){
				$this->result=false;
			}

		
			fputs($smtpConnect, $this->header.$this->body.$this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['data2response'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="250"){
				$this->result=false;
			}
			
		
			// Say Bye to SMTP
			fputs($smtpConnect,"QUIT" . $this->fineriga);
			$smtpResponse = fgets($smtpConnect, 515);
			$logArray['quitresponse'] = $smtpResponse;
			if(substr($smtpResponse,0,3)!="221"){
				$this->result=false;
			}
		}
		
		foreach ($logArray as $key=>$value) {
			$output.="$key=$value<br />";
		}
		return $output;
	}

	
	protected function createHeader() {
		// non riesco a farlo in dichiarazione quindi me lo tengo così
		$this->header="";
		$this->header.="MIME-Version: 1.0".$this->fineriga;
		$this->first=true;
		foreach ($this->TO as $key=>$value) {
			if ($this->first) {
				$this->header.= "To: ";
				$this->first=false;
			}
			$this->header.=$value." <".$key.">, ";
		}
		/*$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		$this->header.= "From: \"".$this->FromName."\" <".$this->From.">".$this->fineriga;
		$this->header.= "Reply-To: \"".$this->ReplyTo."\" <".$this->ReplyTo.">".$this->fineriga;  // aggiungere ;
		$this->header.= "Return-Path: \"".$this->FromName."\" <".$this->From.">".$this->fineriga;
		$this->header.= "Envelope-from: \"".$this->FromName."\" <".$this->From.">".$this->fineriga;
		$this->header.= "Message-ID: <".$this->sep."-noreply@taxrefund.it".$this->fineriga;
		$this->header.= "X-Sender: \"".$this->FromName."\" <".$this->From.">".$this->fineriga;
		$this->header.= "X-Mailer: \"PHP-ABTAX v 1.2\"".$this->fineriga;
		$this->first=true;
		foreach ($this->CC as $key=>$value) {
			if ($this->first) {
				$this->header.= "CC: ";
				$this->first=false;
			}
			$this->header.="\"".$value."\" <".$key.">, ";
		}
		$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		//encoded-word = "=?" charset "?" encoding "?" encoded-text "?="
		//=?UTF-8?B?".base64_encode($subject)."?="
		//era : $this->header.="Subject: ".$this->Subject.$this->fineriga;
		$this->header.="Subject: =?UTF-8?B?".base64_encode($this->Subject)."?=".$this->fineriga;*/
		$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		$this->header.= "From: =?ISO-8859-1?Q?".imap_8bit("\"".$this->FromName."\"")."?= <".$this->From.">".$this->fineriga;
		$this->header.= "Reply-To: =?ISO-8859-1?Q?".imap_8bit("\"".$this->ReplyToName."\"")."?= <".$this->ReplyTo.">".$this->fineriga;
		$this->header.= "Return-Path: =?ISO-8859-1?Q?".imap_8bit("\"".$this->FromName."\"")."?= <".$this->From.">".$this->fineriga;
		$this->header.= "Envelope-from: =?ISO-8859-1?Q?".imap_8bit("\"".$this->FromName."\"")."?= <".$this->From.">".$this->fineriga;
		$this->header.= "Message-ID: <".$this->sep."-noreply@taxrefund.it".$this->fineriga;
		$this->header.= "X-Sender: =?ISO-8859-1?Q?".imap_8bit("\"".$this->FromName."\"")."?= <".$this->From.">".$this->fineriga;
		$this->header.= "X-Mailer: PHP-AB v 1.3".$this->fineriga;
		$this->first=true;
		foreach ($this->CC as $key=>$value) {
			if ($this->first) {
				$this->header.= "CC: ";
				$this->first=false;
			}
			$this->header.="=?ISO-8859-1?Q?".imap_8bit($value)."?= <".$key.">, ";
		}
		$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		$this->Subject = str_replace(" ", "_", trim($this->Subject));
		$this->Subject = str_replace("?", "=3F", str_replace("=\r\n", "", imap_8bit($this->Subject)));
		$this->Subject = str_replace("\r\n", "?=\r\n =?ISO-8859-1?Q?", chunk_split($this->Subject, 55));
		$this->Subject = substr($this->Subject, 0, strlen($this->Subject)-20);
		$this->header.="Subject: =?ISO-8859-1?Q?".$this->Subject."?=".$this->fineriga;
	}
	
	protected function createBody() {

		if ($this->text=="") {
			$this->text=$this->createTextFromHtml();
		}
		$this->body="";
			if (count($this->files)>0 || count($this->filesinline)>0) {
				$this->body.="Content-Type: multipart/mixed;".$this->fineriga." boundary=".$this->mixedsep.$this->fineriga;
				$this->body.=$this->fineriga;
				$this->body.="--".$this->mixedsep.$this->fineriga;	
			}
			$this->body.="Content-Type: multipart/alternative;".$this->fineriga." boundary=".$this->altsep.$this->fineriga;
			$this->body.=$this->fineriga;
			$this->body.="This is a multi-part message in MIME format.".$this->fineriga;
			// parte testo del messsagggio
			$this->body.="--".$this->altsep.$this->fineriga;
				$this->body.="Content-Type: text/plain; charset=ISO-8859-15; format=flowed".$this->fineriga;
				$this->body.="Content-Transfer-Encoding: 8bit".$this->fineriga;
				$this->body.=$this->fineriga;
				$this->body.=$this->text.$this->fineriga;
			$this->body.=$this->fineriga;
			// parte HTML del messsagggio
			$this->body.="--".$this->altsep.$this->fineriga;
				// questa parte la faccio apparire solo se ho immagini in linea
				if (count($this->imagesinline)>0) {
					$this->body.="Content-Type: multipart/related; boundary=".$this->relsep.$this->fineriga;
					$this->body.=$this->fineriga;
					$this->body.="--".$this->relsep.$this->fineriga;
				}
					$this->body.="Content-Type: text/html; charset=ISO-8859-15".$this->fineriga;
					$this->body.="Content-Transfer-Encoding: 8bit".$this->fineriga;
					$this->body.=$this->fineriga;
					$this->body.=$this->html_header.$this->fineriga;
					$this->body.=$this->html.$this->fineriga;
					$this->body.=$this->html_footer.$this->fineriga;
					$this->body.=$this->fineriga;
					foreach ($this->imagesinline as $key=>$value) {
						$this->body.="--".$this->relsep.$this->fineriga;
						$this->body.="Content-Type: ".$this->imagestype[$key].";".$this->fineriga;
						$this->body.=" name=\"".basename($this->imagesnames[$key])."\"".$this->fineriga;
						$this->body.="Content-Transfer-Encoding: base64".$this->fineriga;
						$this->body.="Content-ID: <".substr($this->imagescid[$key],4).">".$this->fineriga;
						$this->body.="Content-Disposition: inline;".$this->fineriga;
						$this->body.=" filename=\"".basename($this->imagesnames[$key])."\"".$this->fineriga;
						$this->body.=$this->fineriga;
						$this->body.=$value.$this->fineriga;
						$this->body.=$this->fineriga;
					}
				if (count($this->imagesinline)>0) {
					$this->body.=$this->fineriga;
					$this->body.="--".$this->relsep."--".$this->fineriga;
				}
				$this->body.=$this->fineriga;
			$this->body.="--".$this->altsep."--".$this->fineriga;
			$this->body.=$this->fineriga;			
		foreach ($this->filesinline as $key=>$value) {
			// qui ci va la dichiarazione id content type degli allegati
			//	e il contenuto degli allegati
			$this->body.="--".$this->mixedsep.$this->fineriga;
			$this->body.="Content-Type: ".$this->filetype[$key]."; name=\"".$key."\"".$this->fineriga;
			$this->body.="Content-Transfer-Encoding: base64".$this->fineriga;
			$this->body.="Content-Disposition: inline;".$this->fineriga;
			$this->body.=$this->fineriga;
			$this->body.=$value.$this->fineriga;
			$this->body.=$this->fineriga;
		}
		foreach ($this->files as $key=>$value) {
			// qui ci va la dichiarazione id content type degli allegati
			//	e il contenuto degli allegati
			$this->body.="--".$this->mixedsep.$this->fineriga;
			$this->body.="Content-Type: ".$this->filetype[$key]."; name=\"".$key."\"".$this->fineriga;
			$this->body.="Content-Transfer-Encoding: base64".$this->fineriga;
			$this->body.="Content-Disposition: attachment;".$this->fineriga;
			$this->body.=" filename=\"".$key."\"".$this->fineriga;
			$this->body.=$this->fineriga;
			$this->body.=$value.$this->fineriga;
			$this->body.=$this->fineriga;
		}
		// la mail dovrebbe esserer finita
		if (count($this->files)>0 || count($this->filesinline)>0) {
			$this->body.="--".$this->mixedsep."--".$this->fineriga;
		}
		$this->body.=".";
	}
	
	public function getFile() {
		$stringa="";
		//$this->header.$this->body
		if($this->header=="") {
			$this->createHeader();
		} 
		if($this->body=="") {
			$this->createBody();
		} 
		$stringa=$this->header.$this->body.$this->fineriga;
		return $stringa;
	}
}

//// ESTENSIONE DELLA CLASSE PER CALENDARIO ////

/*class mia_mail_calendar extends mia_mail {
	
	protected $filetype;
	protected $filesinline;

	public function AllegaInLiena($FileName,$Type) {
		$this->filesinline=file_get_contents($FileName);
		$this->filetype=$Type;
	}	
	
	protected function createHeader() {
		// non riesco a farlo in dichiarazione quindi me lo tengo così
		$this->header.="MIME-Version: 1.0".$this->fineriga;
		$this->header.="Content-Type: ".$this->filetype."; method=REQUEST; charset=UTF-8;".$this->fineriga;
		$this->header.="Content-transfer-encoding: 8BIT".$this->fineriga;
		$this->first=true;
		foreach ($this->TO as $key=>$value) {
			if ($this->first) {
				$this->header.= "To: ";
				$this->first=false;
			}
			$this->header.=$key." <".$value.">, ";
		}
		$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		$this->header.= "From: ".$this->FromName." <".$this->From.">".$this->fineriga;
		$this->first=true;
		foreach ($this->CC as $key=>$value) {
			if ($this->first) {
				$this->header.= "CC: ";
				$this->first=false;
			}
			$this->header.=$key." <".$value.">, ";
		}
		$this->header=substr($this->header,0,-2);
		$this->header.=$this->fineriga;
		$this->header.="Subject: ".$this->Subject.$this->fineriga;
	}

	protected function createBody() {
		$this->body.=$this->fineriga;
		$this->body.=$this->filesinline.$this->fineriga;
		$this->body.=$this->fineriga;
		$this->body.=".";
	}	

	public function Invia() {
		$this->createHeader();
		$this->createBody();
		$this->authSendEmail();
	}	
}*/
}
?>
