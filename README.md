gsVarDump
=========

gsVarDump: a pure PHP var_dump() replacement for unlimited levels deep

Using gsVarDump is easy, just download the module and place it along with your php file(s). Inside the php file, you have to include the module as example below.

require_once('gs.vardump.php');

After you initiate the module, you can directly dump a variable. To dump a variable, there are two ways to do it. The simplest way to do this is just call the gs_vardump() function. See below.

gs_vardump($vardump [,$limit [,$use_htmlcode]])

Note: like php var_dump, if you call gs_vardump function it will directly show the result. You can't store the result to a new variable. If you want to store the result, use OOP-style instead.

There are three properties on gs_vardump. The first is $vardump which is the variable you want to dump, Second is $limit (optional) is the limit of level you want to display, default is 0 which mean unlimited, or set it to 3 to exactly match the php var_dump (three levels deep). And the last is $use_htmlcode (optional) use if you want to display the result with html-design, the default is true. If you set it to false, the return value is just plain variable without html code.

The other way to dump a variable is use OOP-style. First, create new gsVarDump object. See below.

$dump = new gsVarDump($skin);

$string = $dump->vardump($vardump [,$limit [,$use_htmlcode]]);
echo $string;

Unlike directly call gs_vardump() function, in this way you can store the result to a variable because the return of vardump() is a string. The properties are the same as gs_vardump().

Set themes on gsVarDump

gsVarDump has theme. If you want the style exactly look like the php var_dump() output, you can use default theme. To use it, refer $skin to where default css theme located. See example below.

$dump = new gsVarDump('themes/default.css')

At the example, css default theme is in the folder themes. Of course, you can modify the default css to match it with your own style, or create a new theme based on default css theme. Default css theme is included in the download package.

If you directly set theme when creating the object, it will force the html css link to be placed to where the object is created. If you want a clean html structure, leave $skin variable to empty then call useSkin() function and place it on html head where the css link is ordinary placed. See example below.

$dump = new gsVarDump();

//this is somewhere on html head
$dump->useSkin('themes/default.css');
