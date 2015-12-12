<?php
class UserInfo{
	
	private $userId;
	private $username;
	private $ownTemplates;
	private $ownSourceImages;
	private $isAdmin;
	
	function __construct($db, $userId){
		$this->userId = $userId;
		$result = $db->query('SELECT username FROM Users WHERE userId = ?', array($userId), array(SQLITE3_TEXT))->fetchArray();
		$this->username = $result['username'];
		
		$result = $db->query("SELECT * FROM Templates WHERE userId = ? AND reviewState = 'a'", array($userId), array(SQLITE3_TEXT));
		$templates = array();
		while($row = $result->fetchArray()){
			$templateId = $row['templateId'];
			$filetype = $row['filetype'];
			$overlayFiletype = $row['overlayFiletype'];
			$positions = $row['positions'];
			$timeAdded = $row['timeAdded'];
			$timeReviewed = $row['timeReviewed'];
			$reviewedBy = $row['reviewedBy'];
			
			$template = new Template($templateId, $userId, $filetype, $overlayFiletype, $positions, 'a', $timeAdded, $timeReviewed, $reviewedBy);
			array_push($templates, $template);
		}
		$this->ownTemplates = $templates;
		
		$result = $db->query("SELECT * FROM SourceImages WHERE userId = ? AND reviewState = 'a'", array($userId), array(SQLITE3_TEXT));
		$templates = array();
		while($row = $result->fetchArray()){
			$sourceId = $row['sourceId'];
			$filetype = $row['filetype'];
			$timeAdded = $row['timeAdded'];
			$timeReviewed = $row['timeReviewed'];
			$reviewedBy = $row['reviewedBy'];
			
			$template = new SourceImage($sourceId, $userId, $filetype, $timeAdded, $timeReviewed, $reviewedBy);
			array_push($templates, $template);
		}
		
		$this->isAdmin = $db->queryHasRows('SELECT userId FROM Admins WHERE userId = ?', array($userId), array(SQLITE3_TEXT));
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function getUsername(){
		return $this->username;
	}
	
	public function getOwnTemplates(){
		return $this->ownTemplates;
	}
	
	public function getOwnSourceImages(){
		return $this->ownSourceImages;
	}
	
	public function isAdmin(){
		return $this->isAdmin;
	}
	
}
?>