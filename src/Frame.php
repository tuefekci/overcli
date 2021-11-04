namespace tuefekci\overcli;

class Frame
{
	private $matrix;
	public $width;
	public $height;

	public function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
		$this->matrix = array_fill(0, $width, array_fill(0, $height, 0));
	}
}
