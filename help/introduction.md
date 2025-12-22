# JavaScript Introduction

?>This page contains some examples of what JavaScript can do.

## JavaScript Can Change HTML Content

One of many JavaScript HTML methods is `getElementById()`.

The example below "finds" an HTML element (with id="demo"), and changes the element content (innerHTML) to "Hello JavaScript":

> A magical documentation site generator.

#### Example

```JS
document.getElementById("demo").innerHTML = "Hello JavaScript";
```

?>JavaScript accepts both double and single quotes:

### JavaScript Can Hide HTML Elements

Hiding HTML elements can be done by changing the display style:

#### Example

```JS
document.getElementById("demo").style.display = "none";
```

### JavaScript Can Show HTML Elements

Showing hidden HTML elements can also be done by changing the display style

#### Example

```JS
document.getElementById("demo").style.display = "block";
```

### JavaScript Can Change HTML Styles (CSS)

Changing the style of an HTML element, is a variant of changing an HTML attribute:

#### Example

```JS
document.getElementById("demo").style.fontSize = "35px";
```

### Did You Know?

?>
JavaScript and Java are completely different languages, both in concept and design.<br/>
JavaScript was invented by Brendan Eich in 1995, and became an ECMA standard in 1997.<br/>
ECMA-262 is the official name of the standard. ECMAScript is the official name of the language.
![](assets/image1.png)

