<? namespace vistoyn\json_parser;

class JsonString extends JsonStream{
	
	protected $data = null;
	protected $pos = -1;
	protected $size = 0;
	
	public function reset(){
		$this->pos = -1;
		$this->look = null;
	}
	
	public function setContent($content){
		$this->reset();
		$this->data = $content;
		$this->size = strlen($content);
	}
	
	protected function getChar(){
		$ch = $this->look;
		
		$this->pos++;
		if ($this->pos >= $this->size){
			$this->look = null;
		}
		else {
			$this->look = $this->data[$this->pos];
		}
		
		return $ch;
	}
	
	protected function startBuffer(){
		return $this->pos;
	}
	protected function getBuffer($start){
		return substr($this->data, $start, $this->pos - $start);
	}
	
}