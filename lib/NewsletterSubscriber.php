<?
use Ramsey\Uuid\Uuid;

class NewsletterSubscriber extends PropertiesBase{
	public $NewsletterSubscriberId;
	public $Uuid;
	public $Email;
	public $FirstName;
	public $LastName;
	public $IsConfirmed = false;
	public $IsSubscribedToSummary = true;
	public $IsSubscribedToNewsletter = true;
	public $Timestamp;

	public function Create(): void{
		$this->Validate();

		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();

		try{
			Db::Query('insert into NewsletterSubscribers (Email, Uuid, FirstName, LastName, IsConfirmed, IsSubscribedToNewsletter, IsSubscribedToSummary, Timestamp) values (?, ?, ?, ?, ?, ?, ?, utc_timestamp());', [$this->Email, $this->Uuid, $this->FirstName, $this->LastName, false, $this->IsSubscribedToNewsletter, $this->IsSubscribedToSummary]);
		}
		catch(PDOException $ex){
			if($ex->getCode() == '23000'){
				// Duplicate unique key; email already in use
				throw new Exceptions\NewsletterSubscriberExistsException();
			}
		}

		$this->NewsletterSubscriberId = Db::GetLastInsertedId();

		// Send the double opt-in confirmation email
		$em = new Email(true);
		$em->To = $this->Email;
		$em->Body = 'Test';
		$em->Send();
	}

	public function Confirm(): void{
		Db::Query('update NewsletterSubscribers set IsConfirmed = true where NewsletterSubscriberId = ?;', [$this->NewsletterSubscriberId]);
	}

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Email == '' || !filter_var($this->Email, FILTER_VALIDATE_EMAIL)){
			$error->Add(new Exceptions\InvalidEmailException());
		}

		if(!$this->IsSubscribedToSummary && !$this->IsSubscribedToNewsletter){
			$error->Add(new Exceptions\NewsletterRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}
}
