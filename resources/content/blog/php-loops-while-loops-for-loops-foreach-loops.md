---
title: PHP Loops, While Loops, For Loops, Foreach Loops
excerpt: PHP Loops can sometimes be a bit tricky. PHP loops are a sequence of statements which are specified once but may be carried out several times in succession.
cover: /media/blog/php-loops-while-loops-for-loops-foreach-loops/cover.webp
published_at: "2016-08-09 08:00:00 -0400"
updated_at: "2016-08-09 08:00:00 -0400"
tags: ["Development"]
---

PHP Loops can sometimes be a bit tricky. PHP loops are a sequence of statements which are specified once but may be carried out several times in succession. The code "inside" the loop (the body of the loop) is obeyed a specified number of times, or once for each of a collection of items, or until some condition is met, or indefinitely. If you are not paying close attention to your PHP loops, you can get it stuck in what is called an Infinite Loop.

An infinite loop (also known as an endless loop or unproductive loop) is a sequence of instructions in a computer program which loops endlessly, either due to the loop having no terminating condition, having one that can never be met, or one that causes the loop to start over. These can cause the browser to become un-responsive and can also cause your web server to start using up all of its resources making it un-responsive as well. Depending on the browser and server, this loop can be terminated forcing the loop to stop.

So, now that I am done explaining what PHP Loops are, lets get started with a couple different PHP Loops so you can learn how to write them yourself. The first loop we will talk about is the <em>while</em> loop. The <em>while</em> loop executes a block of code as long as the specified condition is true. The below example first sets the variable <strong>$x</strong> to 5 (<strong>$x</strong> = 5). Then, the <em>while</em> loop will continue to run as long as <strong>$x</strong> is less than 10 (<strong>$x</strong> &lt;= 10). <strong>$x</strong> will increase by 1 each time the loop runs (<strong>$x++</strong>).

<pre><code class="language-php">&lt;?php
    while ( $x &lt;= 10 ) {
        echo 'This is line number '.$x.'.';
        $x++;
    }
?&gt;
</code></pre>

The next loop we are going to talk about is a <em>for</em> loop. A <em>for</em> loop is used when you know in advance how many times the script should run. The example below displays the numbers from 0 to 10:

<pre><code class="language-php">&lt;?php
    for ( $i = 0; $i &lt;= 10; $i++ ) {
        echo 'This is line number '.$i.'.';
    }
?&gt;
</code></pre>

Next, we will talk about the PHP <em>foreach</em> loop. These loops are only used on arrays and is used to loop through each key/value pair in an array. For every loop iteration, the value of the current array element is assigned to <strong>$value</strong> and the array pointer is moved by one, until it reaches the last array element. The following example demonstrates a loop that will output the values of the given array (<strong>$autos</strong>):

<pre><code class="language-php">&lt;?php
    $autos = array( 'Ford', 'Chevrolet', 'Chrysler', 'Dodge', 'GMC', 'Buick' );
    foreach ( $autos as $value ) {
        echo $value;
    }
?&gt;
</code></pre>

That is the basics on PHP Loops for now.
