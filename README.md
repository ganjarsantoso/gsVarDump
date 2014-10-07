gsVarDump
=========

__*gsVarDump:*__ a pure PHP class for `var_dump()` alternatives with editable beautified css and unlimited levels deep

To use **gsVarDump**, download the module and place it along with your php file(s). Inside the php file, you have to include the module as example below.

```
require_once('gs.vardump.php');
```

After you initiate the module, you can directly dump a variable. To dump a variable, there are two ways to do it. First, call it directly using function and second is using OOP-style.

**Call gsVarDump directly with `vardump()`**

```
vardump($vardump [,$limit [,$use_htmlcode [,$skin]]])
```

In this way, you don't need to create an object, the function will do it for you.

Note: like php `var_dump()`, if you call `vardump()` function it will directly show the result. You can't store the result to a new variable. If you want to store the result, use OOP-style instead.

There are four properties on `vardump()`.
1. `$vardump` : the variable you want to dump.
2. `$limit` (optional) : how much levels deep you want to display, default is 0 which mean unlimited.
3. `$use_htmlcode` (optional) : use if you want to display the result with html-design, the default is `true`. If you set it to `false`, the return value is just plain variable without html code.
4. `$skin` (optional) : which refer to css theme to beautify the dump structure to make it nicer look.

**Call gsVarDump using OOP-style by creating new object**

```
$dump = new gsVarDump($skin);

$string = $dump->vardump($vardump [,$limit [,$use_htmlcode]]);
echo $string;
```

Unlike directly calling `vardump()` function, this way you can store the result to a variable because the return of `$dump->vardump()` is a string (html code included). To omit the html code of returned value, set the `$use_htmlcode` value to `false`. The properties are the same as `vardump()` except for the `$skin` theme, this way you define a skin theme in different method.


**Set themes on gsVarDump**

*gsVarDump* has theme. If you want the style exactly look like the php `var_dump()` with xdebug-installed output, you can use default theme. To use it, refer `$skin` to where default css theme located.

```
$dump = new gsVarDump('themes/default.css')
```


At the example, css default theme is in the folder themes. Of course, you can modify the default css to match it with your own style, or create a new theme based on default css theme. Default css theme is included in the download package.

If you directly set theme when creating the object, it will force the html css link to be placed to where the object is created. If you want a clean html structure, leave `$skin` variable to empty then call `useSkin()` function and place it on html head where the css link is ordinary placed. See example below.

```
$dump = new gsVarDump();

//this is somewhere on html head
$dump->useSkin('themes/default.css');
```


If you don't wanna messed up with themes, and make this module independent without needed any external css sources, you have to take a look at the *gsVarDump* code module and change value of a constant `IN_LINE_CSS_STYLE` to `true` (default is `false`). If you set it to `true`, any themes setting will be ignored and default built in css which is in line with html code span/div will be used.
