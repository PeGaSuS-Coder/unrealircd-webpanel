<?php

/* Make notes against nicks, IPs and account names. */

class Notes { // OKAY CLASS GET YOUR NOTEPAD OUT
	/**
	 * Find function for Notes
	 * @param array $query The query to search for -- ["ip" => "127.0.0.1", "nick" => "bob", "account" => "bob", "id" => "lol] 
	 * @return array|NULL Returns an array of objects (notes)
	 */
	public static function find(array $query) : array|NULL
	{
		global $config;
		read_config_db();
		if (!isset($config['notes']))
			return NULL;

		$notes = [];
		foreach ($query as $key => $value)
		{	
			foreach (get_config("notes") as $nkey => $nvalue)
			{
				if ($value != $nvalue)
					continue;
					
				$note = (object)[];
				$note->id = $nkey;
				$note->type = $key;
				$note->data - $value;
				$note->note = $nvalue;
				$notes[] = $note;
			}
		}
		return !empty($notes) ? $notes : NULL;
	}
	
	/**
	 * Add a note to one or more peices of data
	 * @param array ["ip" => "127.0.0.1"]
	 * @param string $note "This is a note"
	 * @return void
	 */
	public static function add(array $params, string $note)
	{
		global $config;
		read_config_db();
		foreach ($params as $key => $value)
		{
			$id = md5(random_bytes(20)); // note ID (for linking)
			$config['notes'][$key][$value][$id] = $note;
		}
		write_config(); // write db
	} 
	
	public static function delete_by_id(string $id)
	{
		global $config;
		read_config_db();
		if (!isset($config['notes']))
			return NULL;

		foreach ($config['notes'] as $nkey => $nvalue)
			foreach ($nvalue as $key => $value)
				if ($value == $id)
				{	
					unset($config['notes'][$nkey][$key]);
					break;
				}

		write_config('notes');
	}
}
