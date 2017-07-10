use \{{appNamespace}}\{{bundleName}}\Page\{{name}}{{type}};

	/**
	 * @return {{name}}{{type}}
	 */
	public function create{{bundleName}}{{name}}{{type}}() {
		return new {{name}}{{type}}({{constructorParameter}});
	}
