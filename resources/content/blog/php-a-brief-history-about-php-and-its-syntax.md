---
title: PHP - A brief history about PHP and its Syntax
excerpt: PHP is probably the most widely used programming language around. Before I begin teaching the basics, I would like to explain a little about the history...
cover: /media/blog/php-a-brief-history-about-php-and-its-syntax/cover.webp
published_at: "2016-08-06 08:00:00 -0400"
modified_at: "2016-08-06 08:00:00 -0400"
tags: ["Development"]
---

## PHP

PHP is probably the most widely used programming language around. Before I begin teaching the basics, I would like to explain a little about the history first. There is just something about knowing how it all started that makes it easier to code in the end. Here is some information that I found relevant that I found on Wikipedia:

<strong>PHP</strong> is a server-side scripting language designed for web development but also used as a general-purpose programming language. As of January 2013, PHP was installed on more than 240 million websites (39% of those sampled) and 2.1 million web servers. Originally created by <a href="http://en.wikipedia.org/wiki/Rasmus_Lerdorf" title="Rasmus Lerdorf">Rasmus Lerdorf</a> in 1994, the reference implementation of PHP (powered by the <a href="http://www.zend.com/en/community/php" title="Zend Engine">Zend Engine</a>) is now produced by The PHP Group. While PHP originally stood for <em>Personal Home Page</em>, it now stands for <em>PHP: Hypertext Preprocessor</em>.

<hr />

## <a href="http://en.wikipedia.org/wiki/PHP#History">History</a>

<img src="/media/blog/php-a-brief-history-about-php-and-its-syntax/Rasmus_Lerdorf_August_2014_(cropped).JPG" alt="Rasmus Lerdorf, who wrote the original Common Gateway Interface (CGI) component" title="Rasmus Lerdorf" />

PHP development began in 1994 when Rasmus Lerdorf wrote a series of <a href="https://en.wikipedia.org/wiki/Common_Gateway_Interface" title="Common Gateway Interface">Common Gateway Interface</a> (CGI) binaries in C, which he used to maintain his <a href="https://en.wikipedia.org/wiki/Personal_homepage" title="personal homepage">personal homepage</a>. He extended them to add the ability to work with <a href="https://en.wikipedia.org/wiki/Web_form" title="web forms">web forms</a> and to communicate with <a href="https://en.wikipedia.org/wiki/Database" title="databases">databases</a>, and called this implementation "Personal Home Page/Forms Interpreter" or PHP/FI.

PHP/FI could be used to build simple, <a href="https://en.wikipedia.org/wiki/Dynamic_web_application" title="dynamic web applications">dynamic web applications</a>. To accelerate <a href="https://en.wikipedia.org/wiki/Software_bug" title="Software bug">bug</a> reporting and improve the code, Lerdorf initially announced the release of PHP/FI as "Personal Home Page Tools (PHP Tools) version 1.0" on the <a href="https://en.wikipedia.org/wiki/Usenet" title="Usenet">Usenet</a> discussion group <em>comp.infosystems.www.authoring.cgi</em> on June 8, 1995. This release already had the basic functionality that PHP has today. This included <a href="https://en.wikipedia.org/wiki/Local_variable#Local_variables_in_Perl" title="Local variable">Perl-like variables</a>, form handling, and the ability to embed HTML. The <a href="https://en.wikipedia.org/wiki/Syntax" title="Syntax">syntax</a> resembled that of Perl, but was simpler, more limited and less consistent.

Early PHP was not intended to be a new <a href="https://en.wikipedia.org/wiki/Programming_language_theory" title="Programming language theory">programming language</a>, and grew organically, with Lerdorf noting in retrospect: "I don't know how to stop it, there was never any intent to write a programming language [...] I have absolutely no idea how to write a programming language, I just kept adding the next logical step on the way." A development team began to form and, after months of work and <a href="https://en.wikipedia.org/wiki/Beta_development_stage" title="Beta development stage">beta</a> testing, officially released PHP/FI 2 in November 1997.

The fact that PHP was not originally designed, but instead was developed organically has led to inconsistent naming of functions and inconsistent ordering of their parameters. In some cases, the function names were chosen to match the lower-level libraries which PHP was "wrapping", while in some very early versions of PHP the length of the function names was used internally as a <a href="https://en.wikipedia.org/wiki/Hash_function" title="Hash function">hash function</a>, so names were chosen to improve the distribution of hash values.

<img src="/media/blog/php-a-brief-history-about-php-and-its-syntax/ZeevSuraski.jpg" alt="Andi Gutmans and Zeev Suraski, who rewrote the parser that formed PHP 3" title="Rasmus Lerdorf" />

<a href="http://en.wikipedia.org/wiki/Zeev_Suraski" title="Zeev Suraski">Zeev Suraski</a> and <a href="http://en.wikipedia.org/wiki/Andi_Gutmans" title="Andi Gutmans">Andi Gutmans</a> rewrote the parser in 1997 and formed the base of PHP 3, changing the language's name to the recursive acronym <em>PHP: Hypertext Preprocessor</em>. Afterwards, public testing of PHP 3 began, and the official launch came in June 1998. Suraski and Gutmans then started a new rewrite of PHP's core, producing the Zend Engine in 1999. They also founded Zend Technologies in Ramat Gan, Israel.

On May 22, 2000, PHP 4, powered by the Zend Engine 1.0, was released. As of August 2008 this branch reached version 4.4.9. PHP 4 is no longer under development nor will any security updates be released.

On July 14, 2004, PHP 5 was released, powered by the new Zend Engine II. PHP 5 included new features such as improved support for <a href="https://en.wikipedia.org/wiki/Object-oriented_programming" title="Object oriented programming">object-oriented programming</a>, the PHP Data Objects (PDO) extension (which defines a lightweight and consistent interface for accessing databases), and numerous performance enhancements. In 2008 PHP 5 became the only stable version under development. <a href="https://en.wikipedia.org/wiki/Late_static_binding" title="Late static binding">Late static binding</a> had been missing from PHP and was added in version 5.3.

<hr />

## <a href="http://en.wikipedia.org/wiki/PHP#Syntax" title="Syntax">Syntax</a>

The following <a href="https://en.wikipedia.org/wiki/%22Hello,_World!%22_program">"Hello, World!" program</a> is written in PHP code embedded in an <a href="https://en.wikipedia.org/wiki/HTML" title="HTML">HTML</a> document:

<pre><code class="language-php">    &lt;!DOCTYPE html&gt;
    &lt;html&gt;
        &lt;head&gt;
            &lt;title&gt;PHP "Hello, World!" program&lt;/title&gt;
        &lt;/head&gt;
        &lt;body&gt;
            &lt;?php echo '&lt;p&gt;Hello, World!&lt;/p&gt;'; ?&gt;
        &lt;/body&gt;
    &lt;/html&gt;
</code></pre>

However, as no requirement exists for PHP code to be embedded in HTML, the simplest version of <em>Hello, World!</em> may be written like this, with the closing tag omitted as preferred in files containing pure PHP code

<code class="language-php">&lt;?= 'Hello, World!'; ?&gt;</code>

As well, there is no requirement that a PHP file contain PHP code at all – the interpreter will output data outside of PHP tags unchanged so a simple text file containing "<code>Hello, World!</code>" will give the same output.

The PHP interpreter only executes PHP code within its <a href="https://en.wikipedia.org/wiki/Delimiter" title="Delimiter">delimiters</a>. Anything outside its delimiters is not processed by PHP, although non-PHP text is still subject to <a href="https://en.wikipedia.org/wiki/Control_structure" title="Control structure">control structures</a> described in PHP code. The most common delimiters are <code>&lt;?php to open and ?&gt;</code> to close PHP sections. The shortened form <code>&lt;?</code> also exists. This short delimiter makes script files less portable, since support for them can be disabled in the local PHP configuration and it is therefore discouraged; there is no recommendation against the echo short tag  <code>&lt;?=</code>. Prior to PHP 5.4.0, this short syntax for <code>echo()</code> only works with the <code>short_open_tag</code> configuration setting enabled, while for PHP 5.4.0 and later it is always available. The purpose of all these delimiters is to separate PHP code from non-PHP content, such as [JavaScript](https://en.wikipedia.org/wiki/JavaScript "JavaScript") code or HTML markup.

The first form of delimiters, <code>&lt;?php</code> and <code>?&gt;</code>, in <a href="https://en.wikipedia.org/wiki/XHTML" title="XHTML">XHTML</a> and other <a href="https://en.wikipedia.org/wiki/XML" title="XML">XML</a> documents, creates correctly formed XML processing instructions. This means that the resulting mixture of PHP code and other markup in the server-side file is itself well-formed XML.

Variables are prefixed with a <a href="https://en.wikipedia.org/wiki/Dollar_sign" title="Dollar sign">dollar symbol</a>, and a <a href="https://en.wikipedia.org/wiki/Primitive_type" title="Primitive type">type</a> does not need to be specified in advance. PHP 5 introduced <em>type hinting</em> that allows functions to force their parameters to be objects of a specific class, arrays, interfaces or <a href="https://en.wikipedia.org/wiki/Callback_function" title="Callback functions">callback functions</a>. However, before PHP 7.0, type hints could not be used with scalar types such as integer or string.

Unlike function and class names, variable names are case sensitive. Both double-quoted ("") and <a href="https://en.wikipedia.org/wiki/Heredoc" title="Heredoc">heredoc</a> strings provide the ability to interpolate a variable's value into the string. PHP treats <a href="https://en.wikipedia.org/wiki/Newline" title="Newline">newlines</a> as <a href="https://en.wikipedia.org/wiki/Whitespace_character" title="Whitespace">whitespace</a> in the manner of a <a href="https://en.wikipedia.org/wiki/Free-form_language" title="Free-form language">free-form language</a>, and statements are terminated by a semicolon. PHP has three types of <a href="https://en.wikipedia.org/wiki/Comparison_of_programming_languages_(syntax)#Comments" title="Comment syntax">comment syntax</a>: <code>/* */</code> marks block and inline comments; <code>//</code> or <code>#</code> are used for one-line comments. The <code>echo</code> statement is one of several facilities PHP provides to output text.

In terms of keywords and language syntax, PHP is similar to the C style syntax. <code>if</code> conditions, <code>for</code> and <code>while</code> loops, and function returns are similar in syntax to languages such as C, C++, C#, Java and Perl.

<hr />

That is just a brief overview of what can be found on <a href="http://en.wikipedia.org/wiki/PHP" title="Wikipedia">Wikipedia</a> that covers this topic. Wikipedia has so much information, but I felt that this was enough for you to understand the history of how it all got started. There was even some information on the syntax which is extremely important to know. If you would like for me to go into more detail about the syntax, I would be happy to do so in a future article. For now, I will leave you with this.
