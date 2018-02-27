# HappyCssClasses [Processwire](https://www.processwire.com) module

## [UNMAINTAINED]
looking for a new maintainer

[Forum topic in Processwire forums](https://processwire.com/talk/topic/12005-happyclasses-let-you-dynamically-addremove-bodyclasses-on-the-go/)

**Install**
Install like any other module. Grab a copy from Github, unzip to your /site/modules/ folder (can be a subfolder).
Then go to your backend /processwire/module/ click on refresh and install HappyCssClasses.

After enabling this module you will have the new $bodyClasses variable available in your template files.

**Output body classes**
Open your head.inc, _main.php or _out.php
Search for <body> (might got some attributes already)
and replace with
```
<body class="<?= $bodyClasses; ?>">
```
or just place `<?= $bodyClasses; ?>`within existing list of classes (make sure to seperate this with a space to other classes)

**Adding classes**
```
$bodyClasses->add("className");
$bodyClasses->add("className", 'key'); // the key is only necessary to remove a class later
$bodyClasses->add("className secondClass third-class");
$bodyClasses->add(array('first-class', 'secondClass'));
$bodyClasses->add(array('key1' => 'first-class', 'key2' => 'secondClass'));
```
There are some dynamic classes build in which you can enable by just adding the key
```
$bodyClasses->add("language") to add "lang-{langname}" // You can enter a field name as second parameter, defaults to "name" if omitted
$bodyClasses->add("template") = "template-{templatename}"
$bodyClasses->add("published") = "published" / "unpublished"
$bodyClasses->add("pageNum") = "page-1" for pages greater than 1 it also adds "not-first"
or
$bodyClasses->add("defaults") = adds all of the 4 above (language, template, published, pageNum)
or
you could define multiple like
$bodyClasses->add("language template")
```

**Removing classes**
```
$bodyClasses->remove('keyDos');
$bodyClasses->remove('key1 keyDos');
$bodyClasses->remove(array('key1', 'keyDos'));
```

