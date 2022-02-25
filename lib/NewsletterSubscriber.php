<?

class NewsletterSubscriber extends PropertiesBase{
	public $NewsletterSubscriberId;
	public $Email;
	public $FirstName;
	public $LastName;
	public $IsConfirmed = false;
	public $IsSubscribedToSummary = true;
	public $IsSubscribedToNewsletter = true;

	public function Create(): void{
		$this->Validate();

		#Db::Query('insert into NewsletterSubscribers (Email, FirstName, LastName, IsConfirmed, IsSubscribedToNewsletter, IsSubscribedToSummary) values (?, ?, ?, false, ?, ?);', [$this->Email, $this->FirstName, $this->LastName, $this->IsSubscribedToNewsletter, $this->IsSubscribedToSummary]);

		#$this->NewsletterSubscriberId = Db::GetLastInsertedId();
	}

	public function Validate(): void{
		$error = new ValidationException();

		if($this->Email == '' || !filter_var($this->Email, FILTER_VALIDATE_EMAIL)){
			$error->Add(new InvalidEmailException());
		}

		if(!$this->IsSubscribedToSummary && !$this->IsSubscribedToNewsletter){
			$error->Add(new NewsletterRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}
}
