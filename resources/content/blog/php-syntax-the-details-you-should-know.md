---
title: PHP Syntax - The details you should know
excerpt: The absolute most important thing that we need to learn about php syntax are the PHP Delimiters. PHP can only parse code that is within its Delimiters.
cover: /media/blog/php-syntax-the-details-you-should-know/cover.webp
published_at: "2016-08-03 10:05:00 -0400"
updated_at: "2016-08-03 10:05:00 -0400"
tags: ["Development"]
---

The absolute most important thing that we need to learn about php syntax are the PHP Delimiters. PHP can only parse code that is within its Delimiters. Anything outside its delimiters is sent directly to the output and not parsed by PHP. There are only two opening delimiters that are allowed by PHP, which are <code>&lt;?php</code> or <code>&lt;?=</code> and only one closing delimiter which is <code>?&gt;</code>. If the file ONLY contains PHP code, then the closing delimiter can and should be omitted.

The purpose of the delimiting tags are to separate PHP code from non-PHP code (mostly HTML). Everything outside of the delimiters are ignored by the PHP parser and is passed through as output. When writing PHP code, you Absolutely should separate your PHP code from all HTML code.

<blockquote>
  There are very rare occasions when it is necessary to echo HTML code from within PHP, but this should only be done if absolutely necessary.
</blockquote>

The best php semantics when writing code is that you should always only write the code in its original language. This means that if you are going to write JavaScript, you should never have that JavaScript write out HTML and the same goes for PHP. This will always make your code much cleaner and easier to interpret. Here is a list of basic language constructs:

<ul>
<li>All PHP statements should always be terminated with a semicolon (";").</li>
<li>You can display text with an "echo" statement.</li>
<li>All variables must start with a dollar-prefix ("$") and are case sensitive ($test, $tesT, $newTest, etc.).</li>
<li>The assignment operator is "=".</li>
<li>The markup can be modularized with functions (or methods) defined with the keyword "function".</li>
<li>The control structures include: if, while, for, foreach, and switch.</li>
</ul>

Grouping of control structures can be specified by brackets ("{…}"), but some can use a colon syntax with end keywords, such as in this statement

<pre><code class="language-php">&lt;?php
    if ($x == 0) {
        echo "zero";
    }
?&gt;
</code></pre>

or this statement

<pre><code class="language-php">&lt;?php
    while ($x &lt; 5) {
        echo $x;
    }
?&gt;
</code></pre>

Comments are another thing that should also be used. There are 3 different ways to do this in PHP. The first being:

<pre><code>/*
    This type of
    comment can
    be spread
    out amongst
    several lines
*/
</code></pre>

which serves as block comments which I have displayed. <code>//</code> and <code>#</code> are both used for inline comments meaning they can only be used on a single line.

<hr />

Now that we have learned a little bit about PHP, lets write out our first PHP statement which is probably the easiest statement that you will ever write.

<pre><code class="language-php">&lt;?php
    echo "Hello World!";
?&gt;
</code></pre>

The example above outputs the following: <code>Hello World!</code>. Instead of using 

<pre><code class="language-php">&lt;?php
    ``` and the ``` echo ``` statement, an optional "shortcut" is the
    use of ``` &lt;?= ``` instead of ``` &lt;?php echo ``` which implicitly
    echoes data. For example, to repeat what we did above with the
    "shortcut": ``` &lt;?= "Hello World!";
?&gt;
</code></pre>

And once again the example above outputs the following: <code>Hello World!</code>.

In my experience, the shortcut above does not always validate as proper PHP syntax. Although it will most likely work, it may not parse as valid PHP code. Please keep this in mind when using this shortcut.

Well, I hope that this article has taught you a thing or two about PHP and its Syntax.
