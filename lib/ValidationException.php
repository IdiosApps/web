<?
use function Safe\json_encode;

class ValidationException extends Exception{
	public $Exceptions = [];
	public $HasExceptions = false;
	public $IsFatal = false;

	public function __toString(): string{
		$output = '';
		foreach($this->Exceptions as $exception){
			$output .= $exception->getMessage() . '; ';
		}

		return rtrim($output, '; ');
	}

	public function Add($exception, $isFatal = false){
		if(is_a($exception, static::class)){
			foreach($exception->Exceptions as $childException){
				$this->Add($childException);
			}
		}
		else{
			$this->Exceptions[] = $exception;
		}

		if($isFatal){
			$this->IsFatal = true;
		}

		$this->HasExceptions = true;
	}

	public function Serialize(): string{
		$val = '';
		foreach($this->Exceptions as $childException){
			$val .= $childException->getCode() . ',';
		}

		$val = rtrim($val, ',');

		return $val;
	}

	public function HasChild(string $exception): bool{
		foreach($this->Exceptions as $childException){
			if(is_a($childException, $exception)){
				return true;
			}
		}

		return false;
	}

	public function Clear(){
		unset($this->Exceptions);
		$this->Exceptions = [];
		$this->HasExceptions = false;
	}
}
