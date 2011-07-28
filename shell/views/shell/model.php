<?="<?php defined('SYSPATH') or die('No direct access allowed.');".PHP_EOL;?>

/**
<?php foreach($columns as $column): ?>
 * @property <?php echo $column['type'].' $'.$column['column_name']."\n"; ?>
<?php endforeach; ?>
*/
class Model_<?=$name;?> extends ORM {
	
	/**
	 * "Has one" relationships
	 * @var array
	 */
	protected $_has_one = array();

	/**
	 * "Belongs to" relationships
	 * @var array
	 */
	protected $_belongs_to = array();

	/**
	 * "Has many" relationships
	 * @var array
	 */
	protected $_has_many = array();

	/**
	 * Relationships that should always be joined
	 * @var array
	 */
	protected $_load_with = array();

	/**
	 * @var array
	 */
	protected $_sorting;

	/**
	 * Foreign key suffix
	 * @var string
	 */
	protected $_foreign_key_suffix = '_id';

	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = '<?=$table;?>';

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = <?=Shell::export_array($columns, 1);?>;

	/**
	 * Auto-update columns for updates
	 * @var string
	 */
	protected $_updated_column = NULL;

	/**
	 * Auto-update columns for creation
	 * @var string
	 */
	protected $_created_column = NULL;

	/**
	 * Table primary key
	 * @var string
	 */
	protected $_primary_key = 'id';
	
	/**
	 * Filter definitions for validation
	 *
	 * @return array
	 */	
	public function filters()
	{
		return <?=Shell::export_array($filters, 2);?>;
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return <?=Shell::export_array($labels, 2);?>;
	}
} // End <?=$name;?>