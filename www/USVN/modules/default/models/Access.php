<?php
/**
 * Manipulate the table rights
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package default
 * @subpackage models
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */
class USVN_modules_default_models_Access extends USVN_Db_Table  {
	/**
	 * The primary key column (underscore format).
	 *
	 * Without our prefixe...
	 *
	 * @var string
	 */
	protected $_primary = "id";

	/**
	 * The field's prefix for this table.
	 *
	 * @var string
	 */
	protected $_fieldPrefix = "rights_";

	/**
	 * The table name derived from the class name (underscore format).
	 *
	 * @var array
	 */
    protected $_name = "rights";

    public function access($login, $project, $module, $controller, $action)
    {
    	$right = $module . "_" . $controller . "_" . $action;
    	$db = $this->getAdapter();
    	$res = $db->query("
			select count(*) as cn
			from usvn_to_attribute, usvn_projects, usvn_rights, usvn_to_have, usvn_to_belong, usvn_users
			where
				usvn_to_attribute.projects_id = usvn_projects.projects_id
				and usvn_to_attribute.rights_id = usvn_rights.rights_id
				and usvn_to_attribute.projects_id = usvn_to_have.projects_id
				and usvn_to_attribute.groups_id = usvn_to_belong.groups_id
				and usvn_to_have.users_id = usvn_to_belong.users_id
				and usvn_to_have.users_id = usvn_users.users_id
				and usvn_users.users_login = \"$login\"
				and lower(usvn_rights.rights_label) = lower(\"$right\");
			");
    	$tab = $res->fetchAll();
    	if (intval($tab[0]["cn"]) == 0) {
    		echo "<br>" . __FILE__ . ":" . "<b>L'utilisateur $login ne dispose pas du droit $right</b><br>";
    		return false;
    	} else {
    		echo "<br>" . __FILE__ . ":" . "<b>L'utilisateur $login dispose du droit $right</b><br>";
    		return true;
    	}
    }
}