<?php
/**
 * Subclass for representing a row from the 'bulk_upload_result' table.
 *
 * @package Core
 * @subpackage model
 */ 
class BulkUploadResultKuser extends BulkUploadResult
{
    //kUser property names
    const SCREEN_NAME = "screen_name";
    const EMAIL = "email";
    const DATE_OF_BIRTH = "date_of_birth";
    const COUNTRY = "country";
    const STATE = "state";
    const CITY = "city";
    const ZIP = "zip";
    const GENDER = "gender";
    const FIRST_NAME = "first_name";
    const LAST_NAME = "last_name";
    const IS_ADMIN = "is_admin";
    const TAGS = "tags";
    const ROLE_IDS = "role_ids";
    
    /* (non-PHPdoc)
     * @see BulkUploadResult::handleRelatedObjects()
     */
    public function handleRelatedObjects()
    {
        $kuser = $this->getObject();
        if ($kuser)
        {
            $kuser->setBulkUploadId($this->getBulkUploadJobId());       
            $kuser->save();
        }
    }
    
    
    /* (non-PHPdoc)
     * @see BulkUploadResult::getObject()
     */
    public function getObject()
    {
        //The object Id received through the API is not the actual kuser ID, but the puser ID.
        return kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $this->getObjectId());
    }
    
    //Set properties for users
	
    public function getScreenName()	{return $this->getFromCustomData(self::SCREEN_NAME);}
	public function setScreenName($v)	{$this->putInCustomData(self::SCREEN_NAME, $v);}
	
    public function getEmail()	{return $this->getFromCustomData(self::EMAIL);}
	public function setEmail($v)	{$this->putInCustomData(self::EMAIL, $v);}
	
    public function getDateOfBirth()	{return $this->getFromCustomData(self::DATE_OF_BIRTH);}
	public function setDateOfBirth($v)	{$this->putInCustomData(self::DATE_OF_BIRTH, $v);}
	
    public function getCountry()	{return $this->getFromCustomData(self::COUNTRY);}
	public function setCountry($v)	{$this->putInCustomData(self::COUNTRY, $v);}
	
	public function getState()	{return $this->getFromCustomData(self::STATE);}
	public function setState($v)	{$this->putInCustomData(self::STATE, $v);}
	
	public function getCity()	{return $this->getFromCustomData(self::CITY);}
	public function setCity($v)	{$this->putInCustomData(self::CITY, $v);}
	
	public function getZip()	{return $this->getFromCustomData(self::ZIP);}
	public function setZip($v)	{$this->putInCustomData(self::ZIP, $v);}
	
	public function getGender()	{return $this->getFromCustomData(self::GENDER);}
	public function setGender($v)	{$this->putInCustomData(self::GENDER, $v);}
	
	public function getFirstName()	{return $this->getFromCustomData(self::FIRST_NAME);}
	public function setFirstName($v)	{$this->putInCustomData(self::FIRST_NAME, $v);}

	public function getLastName()	{return $this->getFromCustomData(self::LAST_NAME);}
	public function setLastName($v)	{$this->putInCustomData(self::LAST_NAME, $v);}
	
	public function getIsAdmin()	{return $this->getFromCustomData(self::IS_ADMIN);}
	public function setIsAdmin($v)	{$this->putInCustomData(self::IS_ADMIN, $v);}
	
    public function getTags()	{return $this->getFromCustomData(self::TAGS);}
	public function setTags($v)	{$this->putInCustomData(self::TAGS, $v);}
	
    public function getRoleIds()	{return $this->getFromCustomData(self::ROLE_IDS);}
	public function setRoleIds($v)	{$this->putInCustomData(self::ROLE_IDS, $v);}
}