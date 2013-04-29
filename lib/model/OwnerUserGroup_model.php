<?php
class OwnerUserGroup_model {
	/* Fields */
	public $owner_id;
	public $group_id;

	/* Referenced tables */
	public $AccountOwner;
	public $UserGroup;

	/**
	 * Load all related models.
	*/
	public static function init() {
		sjcAuth::loadClass("Database");
		sjcAuth::loadClass("AccountOwner_model");
		sjcAuth::loadClass("UserGroup_model");
	}

	/**
	 * Create new OwnerUserGroup based on a row from the database.
	 * @param array $row The database row to use.
	*/
	public function OwnerUserGroup_model(array $row = array()) {
		$this -> owner_id = isset($row['owner_id']) ? $row['owner_id']: '';
		$this -> group_id = isset($row['group_id']) ? $row['group_id']: '';

		/* Fields from related tables */
		$this -> AccountOwner = new AccountOwner_model($row);
		$this -> UserGroup = new UserGroup_model($row);
	}

	public static function get($owner_id, $group_id) {
		$sql = "SELECT * FROM OwnerUserGroup LEFT JOIN AccountOwner ON OwnerUserGroup.owner_id = AccountOwner.owner_id LEFT JOIN UserGroup ON OwnerUserGroup.group_id = UserGroup.group_id LEFT JOIN Ou ON AccountOwner.ou_id = Ou.ou_id WHERE OwnerUserGroup.owner_id='%s' AND OwnerUserGroup.group_id='%s'";
		$res = Database::retrieve($sql, array($owner_id, $group_id));
		if($row = Database::get_row($res)) {
			return new OwnerUserGroup_model($row);
		}
		return false;
	}

	public static function list_by_owner_id($owner_id) {
		$sql = "SELECT * FROM OwnerUserGroup LEFT JOIN AccountOwner ON OwnerUserGroup.owner_id = AccountOwner.owner_id LEFT JOIN UserGroup ON OwnerUserGroup.group_id = UserGroup.group_id LEFT JOIN Ou ON AccountOwner.ou_id = Ou.ou_id WHERE OwnerUserGroup.owner_id='%s';";
		$res = Database::retrieve($sql, array($owner_id));
		$ret = array();
		while($row = Database::get_row($res)) {
			$ret[] = new OwnerUserGroup_model($row);
		}
		return $ret;
	}

	public static function list_by_group_id($group_id) {
		$sql = "SELECT * FROM OwnerUserGroup LEFT JOIN AccountOwner ON OwnerUserGroup.owner_id = AccountOwner.owner_id LEFT JOIN UserGroup ON OwnerUserGroup.group_id = UserGroup.group_id LEFT JOIN Ou ON AccountOwner.ou_id = Ou.ou_id WHERE OwnerUserGroup.group_id='%s';";
		$res = Database::retrieve($sql, array($group_id));
		$ret = array();
		while($row = Database::get_row($res)) {
			$ret[] = new OwnerUserGroup_model($row);
		}
		return $ret;
	}

	public function insert() {
		$sql = "INSERT INTO OwnerUserGroup(owner_id, group_id) VALUES ('%s', '%s');";
		return Database::insert($sql, array($this -> owner_id, $this -> group_id));
	}

	public function update() {
		$sql = "UPDATE OwnerUserGroup SET  WHERE owner_id ='%s' AND group_id ='%s';";
		return Database::update($sql, array($this -> owner_id, $this -> group_id));
	}

	public function delete() {
		$sql = "DELETE FROM OwnerUserGroup WHERE owner_id ='%s' AND group_id ='%s';";
		return Database::delete($sql, array($this -> owner_id, $this -> group_id));
	}
}
?>