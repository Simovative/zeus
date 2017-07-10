use \{{appNamespace}}\{{bundleName}}\Command\{{name}}CommandHandler;
use \{{appNamespace}}\{{bundleName}}\Command\{{name}}CommandBuilder;
use \{{appNamespace}}\{{bundleName}}\Command\{{name}}CommandValidator;
	
	/**
	* @return {{name}}CommandHandler
	*/
	public function create{{bundleName}}{{name}}CommandHandler() {
		return new {{name}}CommandHandler();
	}
	
	/**
	* @return {{name}}CommandValidator
	*/
	public function create{{bundleName}}{{name}}CommandValidator() {
		return new {{name}}CommandValidator(
			$this->getMasterFactory()->getTranslator()
		);
	}
	
	/**
	* @return {{name}}CommandBuilder
	*/
	public function create{{bundleName}}{{name}}CommandBuilder() {
		return new {{name}}CommandBuilder(
			$this->create{{bundleName}}{{name}}CommandValidator()
		);
	}
