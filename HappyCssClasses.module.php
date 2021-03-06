<?php
/**
 * Happy CSS Classes allow you to add css classes to e.g. the body element. It'll let you define classes per template or included file / module
 * 
 * To add a body class
 * $bodyClasses->add("className");
 * $bodyClasses->add("className", 'key'); // the key is only necessary to remove a class later
 * $bodyClasses->add("className secondClass third-class");
 * $bodyClasses->add(array('first-class', 'secondClass'));
 * $bodyClasses->add(array('key1' => 'first-class', 'key2' => 'secondClass'));
 * 
 * 
 * To define one of the currently two default classes
 * $bodyClasses->add("language") to add "lang-{langname}"
 * $bodyClasses->add("template") = "template-{templatename}"
 * 
 * 
 * I'm thinking about making it more flexible to allow one to define different "groups" or purposes
 * to be able to have dynamic class in other places, too..
 * 
 * HappyCssClasses
 * Copyright
 * 
 * 
 * ProcessWire 2.x 
 * Copyright (C) 2015 by Ryan Cramer 
 * http://www.processwire.com
 * http://www.ryancramer.com
 * 
 * see LICENSE.TXT in root folder
 * 
 */
class HappyCssClasses extends Wire implements Module {

	public static function getModuleInfo() {
		return array(
			'title' => "Happy CSS Classes",
			'summary' => 'Define CSS classes dynamically.',
			'version' => 6,
			'href' => 'http://www.happygaia.com',
			'author' => 'Can Rau',
			'autoload' => true,
			'singular' => true
		);
	}


	protected $classes = array();


	/**
	 * initiate HappyCssClasses by adding some default vars
	 * @todo if needed ad $clean param, if true don't prepopulate $classes
	 */
	public function init() {
		$this->wire('bodyClasses', new HappyCssClasses());
	}


	/**
	 * add "unpublished" class to unpublished pages
	 */
	public function ready() {
	}


	/**
	 * add one or multiple classes
	 * 
	 * @param str|array $class
	 * string single class or multiple space seperated classes
	 * array of class names like array('classOne', 'classTwo')
	 * assoc array of keys and class names like array('key1' => 'classOne', 'key2' => 'classTwo')
	 * 			
	 * @param str $key optional array key (expecially usefull for easier removal of e.g. language class)
	 * @todo find a nicer way to avoid $key and is_null
	 * 
	 */
	public function add($class, $key = null) {

		if (!is_array($class) && strpos($class, ' ') !== false) {
			$class = explode(' ', $class);
		}

		// add some base classes
		if (in_array($class, array('language', 'defaults'))) {
			$key = is_null($key) ? 'name' : $key;
			$this->classes['language'] = 'lang-'.$this->wire('user')->language->$key;
			if ($class !== 'defaults') return;
		}

		if (in_array($class, array('template', 'defaults'))) {
			$this->classes['template'] = 'template-'.$this->wire('page')->template->name;
			if ($class !== 'defaults') return;
		}

		if (in_array($class, array('published', 'defaults'))) {
			$this->classes['published'] = $this->wire('page')->is(Page::statusUnpublished) ? 'unpublished' : 'published';
			if ($class !== 'defaults') return;
		}

		if (in_array($class, array('pageNum', 'defaults'))) {
			$input = $this->wire('input');
			$this->classes['pageNum'] = $input->pageNum === 1 ? 'page-1' : 'not-first page-'.$input->pageNum;
			return;
		}

		$key = is_null($key) ? $class : $key;

		if (is_array($class)) {
			$this->classes = array_merge($this->classes, $class);

		} else {
			$this->classes[$key] = $class;
		}
	}


	/**
	 * remove one or more classes from $classes
	 * @param str|array $key Key Either single key or array of keys to remove
	 * 
	 */
	public function remove($key) {
		if (strpos($key, ' ') !== false) {
			// if $key is string and contains spaces we convert to array
			$key = explode(' ', $key);
			$remove = array();
			foreach ($key as $k) {
				$remove[$k] = '';
			}
		
		} else {
			$remove = array($key => '');
		}
		$this->classes = array_diff_key($this->classes, $remove);
	}


	/**
	 * output $classes array as space seperated string
	 */
	public function render() {
		return implode(' ', $this->classes);
	}


	/**
	 * let's you render $classes by just echoing $bodyClasses
	 */
	public function __toString() {
		return $this->render();
	}

	/**
	 * for internal testing
	 */
	public function debug() {
		return "<pre style=\"background:#fff; overflow: auto; padding: 0.5em;margin:0;\">".
			var_dump($this->classes)
			."</pre>\n";
	}

}
