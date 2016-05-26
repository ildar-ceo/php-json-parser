<?
namespace vistoyn\json_parser;

class JsonStream{
	
	protected $current_path=[];
	protected $rules=[];
	protected $look = null;
	protected function getChar(){
		return null;
	}
	
	protected function startBuffer(){
	}
	protected function getBuffer($start){
		return null;
	}
	
	protected function getString($n){
		$s = "";
		for ($i=0;$i<$n;$i++) $s.=$this->getChar();
		return $s;
	}
	protected function eof(){
		return $this->look == null;
	}
	protected function getEscapeChar(){
		$this->matchChar('\\');
		$str = '\\';
		if ($this->look == 'u'){
			$this->getChar();
			$code = $this->getString(4);
			$str = mb_convert_encoding(pack('H*', $code), 'UTF-8', 'UTF-16BE');;
		}
		else {
			$str.=$this->getChar(); 
			eval('$a="'.$str.'";');
			$str=$a;
		}
		return $str;
	}
	
	protected function expected($s){
		throw new Exception($s . " expected.");
	}
	
	protected function matchChar($ch){
		if ($this->look == $ch) return $this->getChar();
		else $this->expected($ch);
	}
	
	protected function lookChar($ch){
		if ($this->look == $ch)
			$this->getChar();
	}
	
	protected function lookNext($arr=['[', ']', '{', '}', '"', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.']){
		while (!in_array($this->look, $arr) && !$this->eof()){
			$this->getChar();
		}
	}
	
	protected function matchNumber(){
		$s = "";
		while (in_array($this->look, ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.']) && !$this->eof()){
			$s .= $this->getChar();
		}
		return $s;
	}
	
	protected function matchString(){
		$str = "";
		
		$this->matchChar('"');
		while ($this->look != "\"" && !$this->eof()){
			if ($this->look == "\\"){
				$str .= $this->getEscapeChar();
			}
			else{
				$str .= $this->getChar();
			}
		}
		$this->matchChar('"');
		
		return $str;
	}
	
	protected function matchKeyValue(){
		$s = $this->startBuffer();
		
		$key = $this->matchString();
		$this->matchChar(':');
		$this->current_path[] = $key;
		
		list($start, $rules) = $this->beginRules();
		$this->matchStatement(false);
		$this->endRules($s, $rules);
		
		array_pop($this->current_path);
	}
	
	protected function matchObject(){
		$this->matchChar('{');
		$this->lookNext();
		
		while ($this->look != "}" && !$this->eof()){
			$this->matchKeyValue();
		}
		
		$this->matchChar('}');
	}
	
	protected function matchArray(){
		$this->matchChar('[');
		$this->lookNext();
		
		$i = 0;
		while ($this->look != "]" && !$this->eof()){
			
			$this->current_path[] = $i;
			$this->matchStatement();
			array_pop($this->current_path);
			$i++;
			
		}
		$this->matchChar(']');
	}
	
	protected function matchStatement($lookRules = true){
		$this->lookNext();
		
		if ($lookRules)
			list($start, $rules) = $this->beginRules();
		
		if ($this->look == '{') $this->matchObject();
		if ($this->look == '[') $this->matchArray();
		else if ($this->look == '"') $this->matchString();
		else if (is_numeric($this->look)) $this->matchNumber();
		
		if ($lookRules)
			$this->endRules($start, $rules);
		
		$this->lookNext();
	}

	public function parse(){
		
		$this->current_path=[];
		$this->getChar();
		$this->lookNext();
		$this->matchStatement();
		
	}
	
	public function addRule($rule, $callback){
		$this->rules[]=[$rule, $callback];
	}
	
	public function beginRules(){
		$start = 0;
		$rules = $this->getRulesByPath();
		if (count($rules) > 0){
			$start = $this->startBuffer();
		}
		return [$start, $rules];
	}
	
	public function endRules($start, $rules){
		if (count($rules) > 0){
			$content = trim($this->getBuffer($start));
			
			foreach ($rules as $rule){
				$callback = $rule[1];
				$callback($content);
			}
		}
	}
	
	public function checkRulePath($current_path, $rule_path){
		if (count($current_path) == count($rule_path)){
			
			$sz = count($current_path);
			for ($i=0;$i<$sz;$i++){
				if ($rule_path[$i] == '*')
					continue;
				if ($rule_path[$i] != $current_path[$i])
					return false;
			}
			
			return true;
		}
		return false;
	}
	
	protected function getRulesByPath(){
		
		$rules = [];
		foreach ($this->rules as $rule){
			$rule_path = $rule[0];
			if ($this->checkRulePath($this->current_path, $rule_path)){
				$rules[] = $rule;
			}
		}
		
		
		return $rules;
	}
}

class JsonString extends JsonStream{
	
	protected $data = null;
	protected $pos = -1;
	protected $size = 0;
	
	public function setContent($content){
		$this->post = -1;
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