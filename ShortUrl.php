<?php
class ShortUrl
{
	public $Con;
	public $hostname = "http://localhost:8012/ShortUrl";
	public $newUrl = "";
	
	
	//Connect to Database 
	public function __construct()
	{
		$this->Con = mysqli_connect("localhost","root","","url");
		
		return $this->Con; 
	}
	
	public function GlobalRecordFetch($table)
	{
			$query = mysqli_query($this->Con,"SELECT * FROM {$table}  ORDER BY id DESC LIMIT 1") or die(mysqli_error($this->Con)); 			
			return mysqli_fetch_array($query);			
	}
	
	//Check if Url has been Shortened
	
	public function CheckExistingUrl($table,$long_url)
	{
			$query = mysqli_query($this->Con,"SELECT * FROM {$table} WHERE long_url = '{$long_url}'"); 			
			return mysqli_fetch_array($query);	
	}
	// check if the URL is valid
	public function CheckValidUrl($url_to_shorten)
	{
		$state = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		$response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($response_status == '404')
		{
			$state = false;
		}else{
			$state = true;
		}
		
		return $state;
	}
	//Generate short Url and safe to database;
	public function SaveUrl($url_to_shorten)
	{
		if($this->CheckValidUrl($url_to_shorten)==false)
		{
			die("Invalid Url");
			
		}
		if($this->CheckExistingUrl('shortenedurls',$url_to_shorten)==null)
		{
			$shortUrl = $this->hostname."/".$this->FetchLastId($url_to_shorten);
			mysqli_query($this->Con,"INSERT INTO shortenedurls(long_url,Shorturl) VALUES ('{$url_to_shorten}','{$shortUrl}')");
			$CurrentURL = $this->CheckExistingUrl('shortenedurls',$url_to_shorten)['Shorturl'];
			$this->newUrl = $CurrentURL;
			echo "Short Url Version ".$this->newUrl;
		}else{
			$CurrentURL = $this->CheckExistingUrl('shortenedurls',$url_to_shorten)['Shorturl'];
			$this->newUrl = $CurrentURL;
			echo "This Url Exists ".$this->newUrl;
		}		
	}
	//Get last Inser Id;
	public function FetchLastId($url_to_shorten)
	{
		$LastId = 1;
		$IsArrays = $this->GlobalRecordFetch("shortenedurls")['id'];
		if($IsArrays!=null)
		{
		$LastId = $IsArrays[0]+1;
		}
		
	return $LastId;
	}
	//Fetch long URL from provided short Url
	public function GetLongUrl($Id)
	{
			$query = mysqli_query($this->Con,"SELECT * FROM shortenedurls WHERE id = '{$Id}'"); 			
			return mysqli_fetch_array($query)['long_url'];	
			
	}
}


?>